<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\FormSubmission\Entities\QuestionAdjudicationRequired;
use Modules\FormSubmission\Entities\FormRevisionHistory;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\SkipLogic;
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
                    // Remove answers Against skip logic if condition get fail and Question attempt before submission
                    $deleteResponse = $this->removeDependentAnswers_on_skiplogic($request, $question);
                    // end
                    //  Remove answers over Dependecny
                    $delete = $this->removeDependentAnswers_on_dependency($request, $question);
                    //  end
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
    private function removeDependentAnswers_on_skiplogic($request, $question){
        //$question->id first parameter
        $form_field_name = buildFormFieldName($question->formFields->variable_name);
        $answer = $request->{$form_field_name};
        $where_array = array(
            'question_id' => $question->id,
            'option_value' => $answer
        );
        // get skip logic deactivate fields only if skip logic applied and during submission those Questions are graded out (disabled) and they answer before applying skip logic
        $skiplogics = SkipLogic::where($where_array)->get();
        if(null !== $skiplogics){
            foreach ($skiplogics as $skiplogic) {
                if(null !==$skiplogic){
                    // check and delete if Questions already attempt
                    $deactivate_forms_array = explode(',', $skiplogic->deactivate_forms);
                    $deactivate_forms_array = array_filter($deactivate_forms_array);
                    if(count($deactivate_forms_array) > 0){
                        $force_delete_by_form_id = $this->deleteAnswer($request,$deactivate_forms_array,'form');
                    }
                    $deactivate_sections_array = explode(',', $skiplogic->deactivate_sections);
                    $deactivate_sections_array = array_filter($deactivate_sections_array);
                    if(count($deactivate_sections_array) > 0){

                        $force_delete_by_section_id = $this->deleteAnswer($request,$deactivate_sections_array,'section');
                    }
                    $deactivate_questions_array = explode(',', $skiplogic->deactivate_questions);
                    $deactivate_questions_array = array_filter($deactivate_questions_array);
                    if(count($deactivate_questions_array) > 0){
                        $force_delete_by_question_id = $this->deleteAnswer($request,$deactivate_questions_array,'question');
                    }
                }
            }
        }
        // End here the process of deleteing answers over skip logic

    }
    
    private function deleteAnswer($request, $deactivateids_array,$type){
        
        foreach ($deactivateids_array as $key => $value) {
            
            if($type =='form'){
                $where1 = array('study_id' => $request->studyId,'subject_id' => $request->subjectId,'study_structures_id' => $request->phaseId);
                $where2 = array('phase_steps_id' => $value);
                $where = array_merge($where1,$where2);
                // if found dependend for so we need to delete final data and update status of form
                $finalData = $this->removeFinalData_and_delete_status($where);
            }
            if($type =='section'){
                $where1 = array('study_id' => $request->studyId,'subject_id' => $request->subjectId,'study_structures_id' => $request->phaseId);
                $where2 = array('section_id' => $value);
                $where = array_merge($where1,$where2);
                // if any section of grading form dependent on QC form Question
                $updateDeletefinalData = $this->removeFinalData_and_update_status($where,$request->stepId);
            }
            if($type =='question'){
                $where1 = array('study_id' => $request->studyId,'subject_id' => $request->subjectId,'study_structures_id' => $request->phaseId,'phase_steps_id' => $request->stepId);
                $where2 = array('question_id' => $value);
                $where = array_merge($where1,$where2);
            }
            $answer=Answer::where($where);
            if(null !==$answer){
                $this->force_delete_object($answer);
            }
        }
        return true;
    }
    // This function will be in action if any of grading form is dependent on Qc Question over skiplogic
    private function removeFinalData_and_delete_status($where){
        $finalAnswers = FinalAnswer::where($where);
        $questionAdjRequired = QuestionAdjudicationRequired::where($where);
        $graddingStatus = FormStatus::where($where);
        if(null !== $finalAnswers){
            $this->force_delete_object($finalAnswers);
        }
        if(null !== $questionAdjRequired){
            $this->force_delete_object($questionAdjRequired);
        }
        if(null !== $graddingStatus){
            $this->force_delete_object($graddingStatus);
        }
    }
    // This function will be in action if any of grading section are dependent on QC Questions over skiplogic
    private function removeFinalData_and_update_status($where,$phase_steps_id){

        $finalAnswers = FinalAnswer::where($where);
        $questionAdjRequired = QuestionAdjudicationRequired::where($where);
        $getStep = PhaseSteps::where('step_id',$phase_steps_id)->first();
        $where_array = array('modility_id' => $getStep->modility_id,'form_type_id' => 2,'phase_id' =>$where['study_structures_id']);
        $getGradingStep = PhaseSteps::where($where_array)->first();
        if(null !==$getGradingStep){
            $graddingStatuses = FormStatus::where('phase_steps_id',$getGradingStep->step_id)->get();
            if(null !== $graddingStatuses){
                foreach ($graddingStatuses as $graddingStatus) {
                    if($graddingStatus->form_status == 'complete'){
                        $update_array = array('form_status' => 'resumable');
                        $graddingStatus->update($update_array);
                    }
                }
            }
        }
        if(null !== $finalAnswers){
            $this->force_delete_object($finalAnswers);
        }
        if(null !== $questionAdjRequired){
            $this->force_delete_object($questionAdjRequired);
        }
        
    }

    private function removeDependentAnswers_on_dependency($request, $question){
              // check if this question have any dependent data to delete
            $dependentQuestions = QuestionDependency::where('dep_on_question_id',$question->id)->get();
            if(null !== $dependentQuestions){
               foreach($dependentQuestions as $dependQuestion){
                    $where = array(
                        'study_id' => $request->studyId,
                        'subject_id' => $request->subjectId,
                        'study_structures_id' => $request->phaseId,
                        'phase_steps_id' => $request->stepId,
                        'question_id' => $dependQuestion->question_id,
                    );
                    $answer=Answer::where($where);
                    if(null !==$answer){
                        $this->force_delete_object($answer);
                    }
               }
            }
    }
    private function force_delete_object($object){
        $object->forceDelete(); //returns true/false
        return true;
    }
    public function submitQuestion(Request $request)
    {
        if (PhaseSteps::isStepActive($request->stepId)) {
            $formRevisionDataArray = ['edit_reason_text' => ''];
            $question = Question::find($request->questionId);
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
