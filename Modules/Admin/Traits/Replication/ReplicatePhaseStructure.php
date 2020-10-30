<?php

namespace Modules\Admin\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;

trait ReplicatePhaseStructure
{
    use QuestionReplication;
    use SectionReplication;
    use StepReplication;

    private function replicatePhaseStructure($phaseId)
    {
        $phase = StudyStructure::find($phaseId);
        $lastChildPhase = StudyStructure::where('parent_id', $phaseId)->orderBy('created_at', 'desc')->first();
        $count = 1;
        if (null !== $lastChildPhase) {
            $count = $lastChildPhase->count + 1;
        }

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

            $newStepId = $this->addReplicatedStep($step, $newPhaseId);

            /******************************* */
            /***  Replicate Step Sections ** */
            /******************************* */
            foreach ($step->sections as $section) {

                $newSectionId = $this->addReplicatedSection($section, $newStepId);

                /******************************* */
                /* Replicate Section Questions * */
                /******************************* */
                foreach ($section->questions as $question) {

                    $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId);

                    /******************************* */
                    /* Replicate Question Form Field */
                    /******************************* */

                    $this->addReplicatedFormField($question, $newQuestionId);

                    /******************************* */
                    /* Replicate Question Data Validation */
                    /******************************* */

                    $this->addReplicatedQuestionValidation($question, $newQuestionId);

                    /******************************* */
                    /* Replicate Question Dependency */
                    /******************************* */

                    $this->addReplicatedQuestionDependency($question, $newQuestionId);

                    /******************************* */
                    /*Replicate Question Adjudication*/
                    /******************************* */

                    $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId);
                }
            }
        }
        return $newPhaseId;
    }

    private function updateReplicatedPhase($phase, $replicatedPhase)
    {
        $phaseAttributesArray = Arr::except($phase->attributesToArray(), ['id', 'parent_id']);
        $replicatedPhase->fill($phaseAttributesArray);
        $replicatedPhase->update();
    }

    private function updatePhaseToReplicatedVisits($phase)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phase->id)
        ->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)
        ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $this->updateReplicatedPhase($phase, $replicatedPhase);
        }
    }

    private function deletePhaseToReplicatedVisits($phase)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phase->id)
        ->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)
        ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $replicatedPhase->delete();
        }
    }

    private function deletePhase($phase)
    {
        foreach ($phase->steps as $step) {
            $this->deleteStep($step);
        }
        $this->deletePhaseToReplicatedVisits($phase);
        $phase->delete();
    }
}
