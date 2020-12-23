<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;

trait ReplicatePhaseStructure
{
    use QuestionReplication;
    use QuestionValidationTrait;
    use QuestionSkipLogic;
    use CohortSkipLogicTrait;
    use QuestionOptionSkipLogic;
    use CohortOptionSkipLogic;
    use QuestionDependencyTrait;
    use SectionReplication;
    use StepReplication;
    use QuestionAnnotationDescription;
    use QuestionFormField;
    use QuestionAdjudicationRequiredStatusTrait;

    private function replicatePhaseStructure($phaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $phase = StudyStructure::find($phaseId);
        $lastChildPhase = StudyStructure::where('parent_id', $phaseId)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->orderBy('created_at', 'desc')
            ->first();
        $count = 1;
        if (null !== $lastChildPhase) {
            $count = $lastChildPhase->count + 1;
        }

        /*********  New Phase ********** */
        /******************************* */
        $newPhaseId = Str::uuid();
        $newPhase = $phase->replicate();
        $newPhase->id = $newPhaseId;
        $newPhase->parent_id = $phaseId;
        $newPhase->replicating_or_cloning = $replicating_or_cloning;
        if ($isReplicating === true) {
            $newPhase->is_repeatable = 0;
            $newPhase->count = $count;
            $newPhase->position = $count + 1;
        }
        $newPhase->save();
        /******************************** */


        /******************************* */
        /***  Replicate Phase Steps **** */
        /******************************* */
        foreach ($phase->steps as $step) {

            $newStepId = $this->addReplicatedStep($step, $newPhaseId, $isReplicating);

            /******************************* */
            /***  Replicate Step Sections ** */
            /******************************* */
            foreach ($step->sections as $section) {

                $newSectionId = $this->addReplicatedSection($section, $newStepId, $isReplicating);

                /******************************* */
                /* Replicate Section Questions * */
                /******************************* */
                foreach ($section->questions as $question) {

                    $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId, $isReplicating);

                    /******************************* */
                    /* Replicate Question Form Field */
                    /******************************* */

                    $this->addReplicatedFormField($question, $newQuestionId, $isReplicating);

                    /******************************* */
                    /* Replicate Question Data Validation */
                    /******************************* */

                    $this->addQuestionValidationToReplicatedQuestion($question->id, $newQuestionId, $isReplicating);

                    /******************************* */
                    /* Replicate Question Dependency */
                    /******************************* */

                    $this->addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating);

                    /******************************* */
                    /*Replicate Question Adjudication*/
                    /******************************* */

                    $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId, $isReplicating);

                    /******************************* */
                    /* Replicate Question Skip Logic */
                    /******************************* */

                    $this->updateSkipLogicsToReplicatedVisits($question->id, $newQuestionId, $isReplicating);

                    /******************************* */
                    /* Replicate Question Option Skip Logic */
                    /******************************* */

                    $this->updateOptionSkipLogicsToReplicatedVisits($question->id, $newQuestionId, $isReplicating);
                }
            }
        }

        /******************************* */
        /*** Replicate Cohort Skip Logic */
        /******************************* */
        foreach ($phase->cohortSkipLogics as $cohortSkipLogic) {
            $this->addPhaseSkipLogicToReplicatedPhase($cohortSkipLogic, $newPhaseId, $isReplicating);
        }

        foreach ($phase->questionOptionsCohortSkipLogics as $cohortSkipLogic) {
            $this->addPhaseOptionsSkipLogicToReplicatedPhase($cohortSkipLogic, $newPhaseId, $isReplicating);
        }

        return $newPhaseId;
    }

    private function updateReplicatedPhase($phase, $replicatedPhase)
    {
        $phaseAttributesArray = Arr::except($phase->attributesToArray(), ['id', 'parent_id', 'replicating_or_cloning']);
        $replicatedPhase->fill($phaseAttributesArray);
        $replicatedPhase->update();
    }

    private function updatePhaseToReplicatedVisits($phase)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phase->id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $this->updateReplicatedPhase($phase, $replicatedPhase);
        }
    }

    private function deletePhaseToReplicatedVisits($phase)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phase->id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $replicatedPhase->delete();
        }
    }

    private function deletePhase($phase)
    {
        $this->deletePhaseToReplicatedVisits($phase);
        foreach ($phase->steps as $step) {
            $this->deleteStep($step);
        }
        $phase->delete();
    }
}
