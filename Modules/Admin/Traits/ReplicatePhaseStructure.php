<?php

namespace Modules\Admin\Traits;

use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;

trait ReplicatePhaseStructure
{
    public function replicatePhaseStructure($phaseId)
    {
        $phase = StudyStructure::find($phaseId);
        $lastChildPhase = StudyStructure::where('parent_id', $phaseId)->orderBy('created_at', 'desc')->first();
        $count = 1;
        if (null !== $lastChildPhase) {
            $count = $lastChildPhase->count + 1;
        }

        /******************************* */
        /*********  New Phase ********** */
        /******************************* */
        $newPhaseId = Str::uuid();
        $newPhase = $phase->replicate();
        $newPhase->id = $newPhaseId;
        $newPhase->is_repeatable = 0;
        $newPhase->parent_id = $phaseId;
        $newPhase->count = $count;
        $newPhase->position = $count + 1;
        $newPhase->save();
        /********************************** */

        /******************************* */
        /***  Replicate Phase Steps **** */
        /******************************* */
        foreach ($phase->steps as $step) {

            $newStepId = Str::uuid();
            $newStep = $step->replicate();
            $newStep->step_id = $newStepId;
            $newStep->phase_id = $newPhaseId;
            $newStep->save();

            /******************************* */
            /***  Replicate Step Sections ** */
            /******************************* */
            foreach ($step->sections as $section) {

                $newSectionId = Str::uuid();
                $newSection = $section->replicate();
                $newSection->id = $newSectionId;
                $newSection->phase_steps_id = $newStepId;
                $newSection->save();

                /******************************* */
                /* Replicate Section Questions * */
                /******************************* */
                foreach ($section->questions as $question) {

                    $newQuestionId = Str::uuid();
                    $newQuestion = $question->replicate();
                    $newQuestion->id = $newQuestionId;
                    $newQuestion->section_id = $newSectionId;
                    $newQuestion->save();

                    /******************************* */
                    /* Replicate Question Form Field */
                    /******************************* */
                    $formField = $question->formFields()->first();
                    $newFormFieldId = Str::uuid();
                    $newFormField = $formField->replicate();
                    $newFormField->id = $newFormFieldId;
                    $newFormField->question_id = $newQuestionId;
                    $newFormField->save();
                }
            }
        }
        return $newPhaseId;
    }
}
