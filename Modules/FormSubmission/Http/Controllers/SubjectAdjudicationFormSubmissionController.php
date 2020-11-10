<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\FormSubmission\Entities\AdjudicationFormRevisionHistory;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\Admin\Entities\Question;
use Modules\FormSubmission\Entities\QuestionAdjudicationRequired;
use Modules\FormSubmission\Traits\QuestionDataValidation;

class SubjectAdjudicationFormSubmissionController extends Controller
{
    use QuestionDataValidation;

    public function submitAdjudicationForm(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {

            $step = PhaseSteps::find($request->stepId);

            $editReason = $request->input('adjudication_form_edit_reason_text', '');
            $adjudicationFormRevisionDataArray = ['adjudication_form_edit_reason_text' => $editReason];
            $trailLogDataArray['trail_log'][] = $editReason;
            $sectionIds = $request->sectionId;
            foreach ($sectionIds as $sectionId) {
                $section = Section::find($sectionId);
                $questions = $section->questions;
                foreach ($questions as $question) {
                    $retArray = $this->putFinalAnswer($request, $question);
                    $adjudicationFormRevisionDataArray['adjudication_form_data'][] = $retArray['form_data'];
                    $trailLogDataArray['trail_log'][] = $retArray['trail_log'];
                }
            }

            $adjudicationFormStatusArray = AdjudicationFormStatus::putAdjudicationFormStatus($request);
            AdjudicationFormRevisionHistory::putAdjudicationFormRevisionHistory($adjudicationFormRevisionDataArray, $adjudicationFormStatusArray['id']);

            /**************************** */
            /**************************** */
            $questionAdjudicationRequiredArray = [
                'study_id' => $request->studyId,
                'subject_id' => $request->subjectId,
                'study_structures_id' => $request->phaseId,
                'phase_steps_id' => $request->stepId,
            ];
            QuestionAdjudicationRequired::deleteAdjudicationRequiredQuestion($questionAdjudicationRequiredArray);
            /**************************** */
            /**************************** */

            /***********************
             *  Trail Log
             */
            $formAddOrEdit = 'Add';
            if (!empty($editReason)) {
                $formAddOrEdit = 'Update';
            }
            // get form type
            $formType = 'Adjudication Form';

            eventDetails($trailLogDataArray['trail_log'], $formType, $formAddOrEdit, request()->ip, []);
            /********************* */

            echo json_encode($adjudicationFormStatusArray);
        }
    }

    public function submitAdjudicationFormQuestion(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {
            $adjudicationFormRevisionDataArray = ['adjudication_form_edit_reason_text' => ''];
            $question = Question::find($request->questionId);
            $adjudicationFormRevisionDataArray['adjudication_form_data'][] = $this->putFinalAnswer($request, $question);
            $adjudicationFormStatusArray = AdjudicationFormStatus::putAdjudicationFormStatus($request);
            AdjudicationFormRevisionHistory::putAdjudicationFormRevisionHistory($adjudicationFormRevisionDataArray, $adjudicationFormStatusArray['id']);
            echo json_encode($adjudicationFormStatusArray);
        }
    }


    private function putFinalAnswer($request, $question)
    {
        $step = PhaseSteps::find($request->stepId);
        $answerFixedArray = [];
        $formDataArray = [];
        $trailLogArray = [];
        $answerFixedArray['study_id'] = $request->studyId;
        $answerFixedArray['subject_id'] = $request->subjectId;
        $answerFixedArray['study_structures_id'] = $request->phaseId;
        $answerFixedArray['phase_steps_id'] = $request->stepId;
        $answerFixedArray['section_id'] = $question->section->id;

        $form_field_name = buildFormFieldName($question->formFields->variable_name);
        $form_field_id = $question->formFields->id;
        if ($request->has($form_field_name)) {

            $answer = $request->{$form_field_name};
            if (is_array($answer)) {
                $answer = implode(',', $answer);
            }

            $formDataArray = ['question_id' => $question->id, 'field_id' => $form_field_id, 'answer' => $answer];

            $answerArray = [];
            $answerArray = $answerFixedArray;

            $answerArray['question_id'] = $question->id;
            $answerArray['field_id'] = $form_field_id;
            /************************** */
            $answerObj = FinalAnswer::getFinalAnswer($answerArray);
            /************************** */
            if ($answerObj->id !== 'no-id-123') {
                $answerArray['answer'] = $answer;
                $answerObj->update($answerArray);
            } else {
                $answerArray['id'] = Str::uuid();
                $answerArray['answer'] = $answer;
                $answerObj = FinalAnswer::create($answerArray);
            }

            $trailLogArray = $answerArray;
            $trailLogArray['form_type_id'] = $step->form_type_id;
            $trailLogArray['form_type'] = 'Adjudication Form';
            $trailLogArray['modility_id'] = $step->modility_id;
            $trailLogArray['answer_id'] = $answerObj->id;

            $formDataArray['trail_log'] = $trailLogArray;

            unset($answerArray);
        }
        return $formDataArray;
    }



    public function openSubjectAdjudicationFormToEdit(Request $request)
    {
        $form_adjudicated_by_id = auth()->user()->id;

        $getAdjudicationFormStatusArray = [
            'form_adjudicated_by_id' => $form_adjudicated_by_id,
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
            'form_type_id' => $request->formTypeId,
            'modility_id' => $request->modilityId,
        ];
        $adjudicationFormStatusObj = AdjudicationFormStatus::getAdjudicationFormStatusObj($getAdjudicationFormStatusArray);
        if (null !== $adjudicationFormStatusObj) {
            $adjudicationFormStatusObj->adjudication_status = 'resumable';
            $adjudicationFormStatusObj->update();
        }

        echo $adjudicationFormStatusObj->adjudication_status;
    }
}
