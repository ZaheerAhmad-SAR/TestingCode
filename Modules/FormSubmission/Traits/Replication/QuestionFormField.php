<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
trait QuestionFormField
{
    private function addReplicatedFormField($question, $newQuestionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $formField = $question->formFields()->first();

        $newFormFieldId = (string)Str::uuid();
        $newFormField = $formField->replicate();
        $newFormField->id = $newFormFieldId;
        $newFormField->question_id = $newQuestionId;
        $newFormField->parent_id = $formField->id;
        $newFormField->replicating_or_cloning = $replicating_or_cloning;
        $newFormField->save();
    }
    private function addReplicatedFormFieldForSection($question, $newQuestionId, $isReplicating = true,$request)
    {
        $variable_name = '';
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $formField = $question->formFields()->first();
        if(substr($formField->variable_name, -2) =='OD' && $request->remove_suffix == 'OD'){
            $variable_name = substr_replace($formField->variable_name,$request->add_suffix,-2);
        }else if(substr($formField->variable_name, -2) =='OS' && $request->remove_suffix == 'OS'){
            $variable_name = substr_replace($formField->variable_name,$request->add_suffix,-2);
        }else{
            $variable_name = $formField->variable_name;
        }
        // make sure variable name is unique within the form
        $get_section = Section::where('id',$question->section_id)->first();
        $get_step = PhaseSteps::where('step_id',$get_section->phase_steps_id)->first();
        $get_all_step_sections = Section::where('phase_steps_id',$get_step->step_id)->pluck('id')->toArray();
        $get_all_step_sections_questions = Question::whereIn('section_id',$get_all_step_sections)->pluck('id')->toArray();
        // check if variable name already exists for current step

        $get_variable_name = FormFields::whereIn('question_id',$get_all_step_sections_questions)
                                       ->where('variable_name',$variable_name)
                                       ->first();                               
        $sixRandomDigit = mt_rand(100000,999999);
        if($get_variable_name != null && ($get_variable_name->variable_name == $variable_name)){
            $variable_name = $variable_name.'_'.$sixRandomDigit;
        }                             
        // make sure variable name is unique within the form
        $newFormFieldId = (string)Str::uuid();
        $newFormField = $formField->replicate();
        $newFormField->id = $newFormFieldId;
        $newFormField->question_id = $newQuestionId;
        $newFormField->parent_id = $formField->id;
        $newFormField->variable_name = $variable_name;
        $newFormField->replicating_or_cloning = $replicating_or_cloning;
        $newFormField->save();
    }
    private function updateReplicatedFormField($formField, $replicatedFormField)
    {
        $formFieldAttributesArray = Arr::except($formField->attributesToArray(), ['id', 'question_id', 'parent_id', 'replicating_or_cloning']);
        $replicatedFormField->fill($formFieldAttributesArray);
        $replicatedFormField->update();
    }

    private function updateQuestionFormFieldToReplicatedVisits($formField, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $replicatedFormFields = FormFields::where('parent_id', 'like', $formField->id)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedFormFields as $replicatedFormField) {
            $this->updateReplicatedFormField($formField, $replicatedFormField);
        }
    }

    private function deleteQuestionFormFieldToReplicatedVisits($formField, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        if (null !== $formField) {
            $replicatedFormFields = FormFields::where('parent_id', 'like', $formField->id)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->get();
            foreach ($replicatedFormFields as $replicatedFormField) {
                $replicatedFormField->delete();
            }
        }
    }
}
