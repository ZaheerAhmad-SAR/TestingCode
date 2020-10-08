<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Answer;
use Modules\Admin\Entities\FormRevisionHistory;
use Modules\Admin\Entities\FormStatus;
use Modules\Admin\Entities\ValidationRule;
use Modules\Admin\Entities\Question;

class SubjectFormSubmissionController extends Controller
{
    public function submitForm(Request $request)
    {
        $form_filled_by_user_id = auth()->user()->id;
        $form_filled_by_user_role_id = auth()->user()->id;

        $sectionId = $request->sectionId;
        $section = Section::find($sectionId);
        $questions = $section->questions;

        $answerFixedArray = [];
        $formRevisionDataArray = ['edit_reason_text' => $request->edit_reason_text];
        $answerFixedArray['study_id'] = $request->studyId;
        $answerFixedArray['subject_id'] = $request->subjectId;
        $answerFixedArray['study_structures_id'] = $request->phaseId;
        $answerFixedArray['phase_steps_id'] = $request->stepId;
        $answerFixedArray['section_id'] = $request->sectionId;

        foreach ($questions as $question) {
            $form_field_name = $question->formFields->variable_name;
            $form_field_id = $question->formFields->id;
            if ($request->has($form_field_name)) {
                $answer = $request->{$form_field_name};

                $formDataArray = ['question_id' => $question->id, 'variable_name' => $form_field_name, 'field_id' => $form_field_id, 'answer' => $answer];

                $answerArray = [];
                $answerArray = $answerFixedArray;

                $answerArray['question_id'] = $question->id;
                $answerArray['field_id'] = $form_field_id;
                /************************** */
                $answerObj = Answer::where(function ($q) use ($answerArray) {
                    foreach ($answerArray as $key => $value) {
                        $q->where($key, 'like', $value);
                    }
                })->first();
                /************************** */
                if ($answerObj) {
                    $answerArray['answer'] = $answer;
                    $answerObj->update($answerArray);
                } else {
                    $answerArray['id'] = Str::uuid();
                    $answerArray['answer'] = $answer;
                    $answerObj = Answer::create($answerArray);
                }
                unset($answerArray);

                $formRevisionDataArray['form_data'][] = $formDataArray;
            }
        }

        $getFormStatusArray = [
            'form_filled_by_user_id' => $form_filled_by_user_id,
            'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
            'section_id' => $request->sectionId,
        ];
        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);

        if ($formStatusObj->form_status == 'no_status') {
            $formStatusObj = $this->insertFormStatus($request, $getFormStatusArray);
        } elseif ($request->has(buildSafeStr($request->stepId, 'terms_cond_'))) {
            $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
            $formStatusObj->edit_reason_text = $request->edit_reason_text;
            $formStatusObj->form_status = 'complete';
            $formStatusObj->update();
        }
        $this->putFormRevisionHistory($formRevisionDataArray, $formStatusObj);
        echo $formStatusObj->form_status;
    }

    private function putFormRevisionHistory($formRevisionDataArray, $formStatusObj)
    {
        $formRevisionHistory = new FormRevisionHistory();
        $formRevisionHistory->id = Str::uuid();
        $formRevisionHistory->form_submit_status_id = $formStatusObj->id;
        $formRevisionHistory->edit_reason_text = $formRevisionDataArray['edit_reason_text'];
        $formRevisionHistory->form_data = json_encode($formRevisionDataArray['form_data']);
        $formRevisionHistory->save();
    }

    public function openSubjectFormToEdit(Request $request)
    {
        $form_filled_by_user_id = auth()->user()->id;
        $form_filled_by_user_role_id = auth()->user()->id;

        $step = PhaseSteps::find($request->stepId);
        foreach ($step->sections as $section) {
            $getFormStatusArray = [
                'form_filled_by_user_id' => $form_filled_by_user_id,
                'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
                'subject_id' => $request->subjectId,
                'study_id' => $request->studyId,
                'study_structures_id' => $request->phaseId,
                'phase_steps_id' => $request->stepId,
                'section_id' => $section->id,
            ];
            $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
            if (null !== $formStatusObj) {
                $formStatusObj->form_status = 'resumable';
                $formStatusObj->update();
            }
        }
        echo $formStatusObj->form_status;
    }

    private function insertFormStatus($request, $getFormStatusArray)
    {
        $formStatusData = [
            'id' => Str::uuid(),
            'form_type_id' => $request->formTypeId,
            'edit_reason_text' => $request->edit_reason_text,
            'form_status' => 'incomplete',
        ] + $getFormStatusArray;
        return FormStatus::create($formStatusData);
    }

    public function validateSectionQuestionsForm(Request $request)
    {
        $returnArray = [];
        $returnArray['success'] = 'yes';
        $returnArray['error'] = '';

        $sectionId = $request->sectionId;
        $section = Section::find($sectionId);
        $questions = $section->questions;

        foreach ($questions as $question) {
            $returnArray = $this->validateField($request, $question);
            if ($returnArray['success'] == 'no') {
                break;
            }
        }
        echo json_encode($returnArray);
    }

    public function validateSingleQuestion(Request $request)
    {
        $questionId = $request->questionId;
        $question = Question::find($questionId);
        $returnArray = $this->validateField($request, $question);
        echo json_encode($returnArray);
    }

    private function validateField($request, $question)
    {
        $returnArray = [];
        $returnArray['success'] = 'yes';
        $returnArray['error'] = '';

        $form_field_name = $question->formFields->variable_name;
        if ($request->has($form_field_name)) {

            /************************************** */
            $validationRulesArray = [];
            if ($question->formFields->is_required == 'yes') {
                $validationRulesArray[] = 'required';
            }
            foreach ($question->validationRules as $validationRule) {
                $validationRuleStr = '';

                if ($validationRule->is_range == 1 || (int)$validationRule->num_params == 2) {
                    if (
                        !empty($question->formFields->lower_limit) &&
                        !empty($question->formFields->upper_limit)
                    ) {
                        $validationRuleStr .= $validationRule->rule;
                        $validationRuleStr .= ':' . $question->formFields->lower_limit . ',' . $question->formFields->upper_limit;
                    } else {
                        return $this->abortValidationWithError();
                    }
                } elseif ((int)$validationRule->num_params == 1) {
                    if (
                        !empty($question->formFields->lower_limit)
                    ) {
                        $validationRuleStr .= $validationRule->rule;
                        $validationRuleStr .= ':' . $question->formFields->lower_limit;
                    } else {
                        return $this->abortValidationWithError();
                    }
                } elseif ((string)$validationRule->num_params == 'unlimited') {
                    if (
                        !empty($question->formFields->lower_limit)
                    ) {
                        $validationRuleStr .= $validationRule->rule;
                        $validationRuleStr .= ':' . $question->formFields->lower_limit;
                    } else {
                        return $this->abortValidationWithError();
                    }
                } else {
                    $validationRuleStr .= $validationRule->rule;
                }

                $validationRulesArray[] = $validationRuleStr;
            }

            $validator = Validator::make([$form_field_name => $request->{$form_field_name}], [
                $form_field_name => implode('|', $validationRulesArray)
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $returnArray['success'] = 'no';
                $returnArray['error'] = $errors->first($form_field_name);
            }
            /************************************** */
            return $returnArray;
        }
    }

    private function abortValidationWithError()
    {
        $returnArray = [];
        $returnArray['success'] = 'no';
        $returnArray['error'] = 'Required parameters for validation are not available';
        return $returnArray;
    }
}
