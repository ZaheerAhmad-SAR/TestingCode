<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FormRevisionHistory;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\Question;
use Modules\FormSubmission\Traits\QuestionDataValidation;

class SubjectFormSubmissionController extends Controller
{
    use QuestionDataValidation;

    public function submitForm(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {
            $editReason = $request->input('edit_reason_text', '');
            $formRevisionDataArray = ['edit_reason_text' => $editReason];
            $trailLogDataArray['trail_log']['edit_reason'] = $editReason;

            $sectionIds = $request->sectionId;
            foreach ($sectionIds as $sectionId) {
                $section = Section::find($sectionId);
                $questions = $section->questions;
                foreach ($questions as $question) {
                    $retArray = $this->putAnswer($request, $question);
                    $formRevisionDataArray['form_data'][] = $retArray['form_data'];
                    $trailLogDataArray['trail_log'][] = $retArray['trail_log'];
                }
            }
            $formStatusArray = FormStatus::putFormStatus($request);
            FormRevisionHistory::putFormRevisionHistory($formRevisionDataArray, $formStatusArray['id']);

            /***********************
             *  Trail Log
             */
            $formAddOrEdit = 'Add';
            if (!empty($editReason)) {
                $formAddOrEdit = 'Update';
            }
            eventDetails($trailLogDataArray['trail_log'], 'Form', $formAddOrEdit, request()->ip, []);
            /********************* */
            echo json_encode($formStatusArray);
        }
    }

    public function submitQuestion(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {
            $formRevisionDataArray = ['edit_reason_text' => ''];
            $question = Question::find($request->questionId);
            $formRevisionDataArray['form_data'][] = $this->putAnswer($request, $question);
            $formStatusArray = FormStatus::putFormStatus($request);
            FormRevisionHistory::putFormRevisionHistory($formRevisionDataArray, $formStatusArray['id']);
            echo json_encode($formStatusArray);
        }
    }


    private function putAnswer($request, $question)
    {
        $formDataArray = [];
        $finalFormDataArray = [];
        $trailLogArray = [];
        $answerFixedArray = [];
        $answerFixedArray['study_id'] = $request->studyId;
        $answerFixedArray['subject_id'] = $request->subjectId;
        $answerFixedArray['study_structures_id'] = $request->phaseId;
        $answerFixedArray['phase_steps_id'] = $request->stepId;
        $answerFixedArray['section_id'] = $question->section->id;
        $answerFixedArray['form_filled_by_user_id'] = auth()->user()->id;

        $step = PhaseSteps::find($request->stepId);

        $form_field_name = buildFormFieldName($question->formFields->variable_name);
        $form_field_id = $question->formFields->id;
        if ($request->has($form_field_name)) {

            $answer = $request->{$form_field_name};
            if (is_array($answer)) {
                $answer = implode(',', $answer);
            }

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
            $trailLogArray = $answerArray;
            $trailLogArray['form_type_id'] = $step->form_type_id;
            $trailLogArray['form_type'] = ($step->form_type_id == 1) ? 'qc' : 'grading';
            $trailLogArray['modility_id'] = $step->modility_id;
            $trailLogArray['answer_id'] = $answerObj->id;

            $finalFormDataArray['trail_log'] = $trailLogArray;
            unset($answerArray);
        }
        $finalFormDataArray['form_data'] = $formDataArray;
        return $finalFormDataArray;
    }



    public function openSubjectFormToEdit(Request $request)
    {
        $form_filled_by_user_id = auth()->user()->id;

        $getFormStatusArray = [
            'form_filled_by_user_id' => $form_filled_by_user_id,
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
}
