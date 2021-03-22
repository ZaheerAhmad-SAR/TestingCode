<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FormRevisionHistory;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionDependency;
use Modules\FormSubmission\Entities\FormVersion;
use Modules\FormSubmission\Traits\QuestionDataValidation;
use App\Helpers\ImageUploadingHelper;
use Illuminate\Support\Facades\Validator;

class SubjectFormSubmissionController extends Controller
{
    use QuestionDataValidation;

    public function submitForm(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {
            // step object
            $step = PhaseSteps::find($request->stepId);
            $editReason = $request->input('edit_reason_text', '');
            $formRevisionDataArray = ['edit_reason_text' => $editReason];
            $trailLogDataArray['trail_log'][] = $editReason;
            $sectionIds = $request->sectionId;
            foreach ($sectionIds as $sectionId) {
                $section = Section::find($sectionId);
                foreach ($section->questions as $question) {
                    $fieldType = $question->form_field_type->field_type;
                    if (($fieldType == 'Upload') || ($fieldType == 'Description')) {
                        continue;
                    }
                    $retArray = $this->putAnswer($request, $question);
                    $formRevisionDataArray['form_data'][] = $retArray['form_data'];
                    $trailLogDataArray['trail_log'][] = $retArray['trail_log'];
                }
            }
            // Final data and Adjudication decsion making here in putFormStatus methode
            $formStatusArray = FormStatus::putFormStatus($request);
            FormRevisionHistory::putFormRevisionHistory($formRevisionDataArray, $formStatusArray['id']);

            /***********************
             *  Trail Log
             */
            $formAddOrEdit = 'Add';
            if (!empty($editReason)) {
                $formAddOrEdit = 'Update';
            }

            // get form type
            $formType = $step->formType->form_type .= ' Form';
            if (!empty($trailLogDataArray['trail_log'])) {
                eventDetails(array_filter($trailLogDataArray['trail_log']), $formType, $formAddOrEdit, request()->ip, []);
            }
            /********************* */
            echo json_encode($formStatusArray);
        }
    }

    public function submitQuestion(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {
            $formRevisionDataArray = ['edit_reason_text' => ''];
            $question = Question::find($request->questionId);
            // check if this question have any dependent data to delete
            $dependentQuestion = QuestionDependency::where('dep_on_question_id',$request->questionId)->get();
            if(null !== $dependentQuestion){
               foreach($dependentQuestion as $dependQuestion){
                    $where = array(
                        'study_id' => $request->studyId,
                        'subject_id' => $request->subjectId,
                        'study_structures_id' => $request->phaseId,
                        'phase_steps_id' => $request->stepId,
                        'question_id' => $dependQuestion->question_id,
                    );
                   $checkAnswer = Answer::where($where)->first();
                    if(null !== $checkAnswer){ 
                        $removeDependentData = $this->removeDependentAnswers($where); 
                    }
               }
            }
            $formData = $this->putAnswer($request, $question);
            $formRevisionDataArray['form_data'][] = $formData['form_data'];
            $formStatusArray = FormStatus::putFormStatus($request);
            FormRevisionHistory::putFormRevisionHistory($formRevisionDataArray, $formStatusArray['id']);
            echo json_encode([
                'status' => $formStatusArray,
                'answer' => $formData['form_data']['answer'],
                'answerId' => $formData['form_data']['answerId'],
            ]);
        }
    }

    private function removeDependentAnswers($where)
    {
        $answer=Answer::where($where)->first();
        $answer->forceDelete(); //returns true/false
        return true;
    }
    private function putAnswer($request, $question)
    {
        $mimes = [
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/png',
            'application/pdf',
        ];

        $needToDeleteFiles = false;
        $answer = '';

        $step = PhaseSteps::find($request->stepId);
        $formVersion = PhaseSteps::getFormVersion($step->step_id);

        $formDataArray = [];
        $finalFormDataArray = [
            'form_data' => '',
            'trail_log' => '',
        ];
        $trailLogArray = [];
        $answerFixedArray = [];
        $answerFixedArray['study_id'] = $request->studyId;
        $answerFixedArray['subject_id'] = $request->subjectId;
        $answerFixedArray['study_structures_id'] = $request->phaseId;
        $answerFixedArray['phase_steps_id'] = $request->stepId;
        $answerFixedArray['section_id'] = $question->section->id;
        $answerFixedArray['form_filled_by_user_id'] = auth()->user()->id;

        $form_field_name = buildFormFieldName($question->formFields->variable_name);
        $form_field_id = $question->formFields->id;
        if ($request->has($form_field_name) || $request->hasFile($form_field_name . '0')) {

            if ($request->hasFile($form_field_name . '0')) {
                $formFilesStr = '';
                for ($x = 0; $x < $request->TotalFiles; $x++) {
                    if ($request->hasFile($form_field_name . $x)) {
                        $file = $request->file($form_field_name . $x);

                        $rules = [
                            $form_field_name . $x => 'mimetypes:' . implode(',', $mimes),
                        ];
                        $validator = Validator::make($request->all(), $rules);
                        if (!$validator->fails()) {
                            $needToDeleteFiles = true;
                            $fileName = ImageUploadingHelper::UploadDoc('form_files', $file);
                            $formFilesStr .= $fileName . '<<|!|>>';
                        }
                    }
                }
                $answer = $formFilesStr;
            } else {
                $answer = $request->{$form_field_name};
                if (is_array($answer)) {
                    $answer = implode(',', $answer);
                }
            }

            $formDataArray = ['question_id' => $question->id, 'variable_name' => $form_field_name, 'field_id' => $form_field_id, 'answer' => $answer];

            $answerArray = [];
            $answerArray = $answerFixedArray;

            $answerArray['question_id'] = $question->id;
            $answerArray['variable_name'] = $form_field_name;
            $answerArray['field_id'] = $form_field_id;
            /************************** */
            $answerObj = Answer::getAnswer($answerArray);
            /************************** */
            if ($answerObj) {
                if ($needToDeleteFiles === true) {
                    $oldFilesArray = explode('<<|!|>>', $answerObj->answer);
                    foreach ($oldFilesArray as $oldFile) {
                        File::delete(ImageUploadingHelper::real_public_path() . 'form_files/' . $oldFile);
                    }
                }
                $answerArray['answer'] = $answer;
                $answerArray['form_version_num'] = $formVersion;
                $answerObj->update($answerArray);
            } else {
                $answerArray['id'] = (string)Str::uuid();
                $answerArray['answer'] = $answer;
                $answerArray['form_version_num'] = $formVersion;
                $answerObj = Answer::create($answerArray);
            }
            $formDataArray['answerId'] = $answerObj->id;
            $trailLogArray = $answerArray;
            $trailLogArray['form_type_id'] = $step->form_type_id;
            $trailLogArray['form_type'] = $step->formType->form_type;
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
        $current_user_id = auth()->user()->id;

        $getFormStatusArray = [
            'form_filled_by_user_id' => $current_user_id,
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

    public function lockFormData(Request $request)
    {
        $getFormStatusArray = [
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
        ];
        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
        if (null !== $formStatusObj) {
            $formStatusObj->is_data_locked = 1;
            $formStatusObj->update();
        }

        echo $formStatusObj->is_data_locked;
    }
    public function unlockFormData(Request $request)
    {
        $getFormStatusArray = [
            'subject_id' => $request->subjectId,
            'study_id' => $request->studyId,
            'study_structures_id' => $request->phaseId,
            'phase_steps_id' => $request->stepId,
        ];
        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
        if (null !== $formStatusObj) {
            $formStatusObj->is_data_locked = 0;
            $formStatusObj->update();
        }

        echo $formStatusObj->is_data_locked;
    }

    public function deleteFormUploadFile(Request $request)
    {
        $answerId = $request->answerId;
        $fileName = $request->fileName;

        $answer = Answer::find($answerId);

        $filesArray = explode('<<|!|>>', $answer->answer);
        $newFilesArray = array_diff($filesArray, (array)$fileName);

        $answer->answer = implode('<<|!|>>', $newFilesArray);
        $answer->update();

        File::delete(ImageUploadingHelper::real_public_path() . 'form_files/' . $fileName);
        echo 'deleteFormUploadFile : ' . $fileName;
    }
}
