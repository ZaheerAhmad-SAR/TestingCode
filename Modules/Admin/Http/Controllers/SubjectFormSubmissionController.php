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
        $formRevisionDataArray = ['edit_reason_text' => $request->input('edit_reason_text', '')];
        $sectionIds = $request->sectionId;
        foreach ($sectionIds as $sectionId) {
            $section = Section::find($sectionId);
            $questions = $section->questions;
            foreach ($questions as $question) {
                $formRevisionDataArray['form_data'][] = $this->putAnswer($request, $question);
            }
        }

        $formStatusArray = FormStatus::putFormStatus($request);
        FormRevisionHistory::putFormRevisionHistory($formRevisionDataArray, $formStatusArray['id']);

        echo $formStatusArray['formStatus'];
    }

    public function submitQuestion(Request $request)
    {
        $formRevisionDataArray = ['edit_reason_text' => ''];
        $question = Question::find($request->questionId);
        $formRevisionDataArray['form_data'][] = $this->putAnswer($request, $question);
        $formStatusArray = FormStatus::putFormStatus($request);
        FormRevisionHistory::putFormRevisionHistory($formRevisionDataArray, $formStatusArray['id']);
        echo $formStatusArray['formStatus'];
    }


    private function putAnswer($request, $question)
    {
        $answerFixedArray = [];
        $answerFixedArray['study_id'] = $request->studyId;
        $answerFixedArray['subject_id'] = $request->subjectId;
        $answerFixedArray['study_structures_id'] = $request->phaseId;
        $answerFixedArray['phase_steps_id'] = $request->stepId;
        $answerFixedArray['section_id'] = $question->section->id;

        $form_field_name = buildFormFieldName($question->formFields->variable_name);
        $form_field_id = $question->formFields->id;
        if ($request->has($form_field_name)) {
            $answer = $request->{$form_field_name};

            $formDataArray = ['question_id' => $question->id, 'variable_name' => $form_field_name, 'field_id' => $form_field_id, 'answer' => $answer];

            $answerArray = [];
            $answerArray = $answerFixedArray;

            $answerArray['question_id'] = $question->id;
            $answerArray['field_id'] = $form_field_id;
            /************************** */
            $answerObj = Answer::getAnswer($answerArray);
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
        }
        return $formDataArray;
    }



    public function openSubjectFormToEdit(Request $request)
    {
        $form_filled_by_user_id = auth()->user()->id;
        $form_filled_by_user_role_id = auth()->user()->id;

        $getFormStatusArray = [
            'form_filled_by_user_id' => $form_filled_by_user_id,
            'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
        ];
        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
        if (null !== $formStatusObj) {
            $formStatusObj->form_status = 'resumable';
            $formStatusObj->update();
        }

        echo $formStatusObj->form_status;
    }



    public function validateSectionQuestionsForm(Request $request)
    {
        $returnArray = [];
        $returnArray['success'] = 'yes';
        $returnArray['error'] = '';

        $sectionIds = $request->sectionId;
        foreach ($sectionIds as $sectionId) {
            $section = Section::find($sectionId);
            $questions = $section->questions;

            foreach ($questions as $question) {
                $returnArray = $this->validateField($request, $question);
                if ($returnArray['success'] == 'no') {
                    break;
                }
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

        $form_field_name = buildFormFieldName($question->formFields->variable_name);
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
