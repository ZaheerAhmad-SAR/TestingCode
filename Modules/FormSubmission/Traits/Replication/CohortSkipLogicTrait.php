<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\CohortSkipLogic;
use Modules\Admin\Entities\Section;

trait CohortSkipLogicTrait
{
    private function addPhaseSkipLogicToReplicatedPhase($skipLogic, $replicatedPhaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $newSkipLogicId = (string)Str::uuid();
        $newSkipLogic = $skipLogic->replicate();
        $newSkipLogic->id = $newSkipLogicId;
        $newSkipLogic->phase_id = $replicatedPhaseId;

        $newSkipLogic->parent_id = $skipLogic->id;
        $newSkipLogic->replicating_or_cloning = $replicating_or_cloning;

        $stepIdsArray = StudyStructure::getStepIdsInPhaseArray($replicatedPhaseId);
        // Update deactivate form ids
        $deactivateFormIds = arrayFilter(explode(',', $skipLogic->deactivate_forms));
        $deactivateFormArray = [];
        foreach ($deactivateFormIds as $deactivateFormId) {
            $replicatedForm = PhaseSteps::where('parent_id', 'like', $deactivateFormId)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->whereIn('step_id', $stepIdsArray)
                ->first();
            if (null !== $replicatedForm) {
                $deactivateFormArray[] = $replicatedForm->step_id;
            }
        }
        $newSkipLogic->deactivate_forms = implode(',', $deactivateFormArray);

        $sectionIdsArray = StudyStructure::getSectionIdsInPhaseArray($replicatedPhaseId);
        // Update deactivate section ids
        $deactivateSectionIds = arrayFilter(explode(',', $skipLogic->deactivate_sections));
        $deactivateSectionArray = [];
        foreach ($deactivateSectionIds as $deactivateSectionId) {
            $replicatedSection = Section::where('parent_id', 'like', $deactivateSectionId)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->whereIn('id', $sectionIdsArray)
                ->first();
            if (null !== $replicatedSection) {
                $deactivateSectionArray[] = $replicatedSection->id;
            }
        }
        $newSkipLogic->deactivate_sections = implode(',', $deactivateSectionArray);

        $questionIdsArray = StudyStructure::getQuestionIdsInPhaseArray($replicatedPhaseId);
        // Update deactivate question ids
        $deactivateQuestionIds = arrayFilter(explode(',', $skipLogic->deactivate_questions));
        $deactivateQuestionArray = [];
        foreach ($deactivateQuestionIds as $deactivateQuestionId) {
            $replicatedQuestion = Question::where('parent_id', 'like', $deactivateQuestionId)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->whereIn('id', $questionIdsArray)
                ->first();
            if (null !== $replicatedQuestion) {
                $deactivateQuestionArray[] = $replicatedQuestion->id;
            }
        }
        $newSkipLogic->deactivate_questions = implode(',', $deactivateQuestionArray);

        $newSkipLogic->save();
    }

    private function updateCohortSkipLogicsToReplicatedVisits($phaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $this->deleteCohortSkipLogicsToReplicatedVisits($phaseId, $isReplicating);
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $skipLogics = CohortSkipLogic::where('phase_id', 'like', $phaseId)->get();
            foreach ($skipLogics as $skipLogic) {
                $this->addPhaseSkipLogicToReplicatedPhase($skipLogic, $replicatedPhase->id, $isReplicating);
            }
        }
    }

    private function deleteCohortSkipLogicsToReplicatedVisits($phaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            CohortSkipLogic::where('phase_id', 'like', $replicatedPhase->id)->delete();
        }
    }

    private function deletePhaseSkipLogics($phaseId, $isReplicating = true)
    {
        $this->deleteCohortSkipLogicsToReplicatedVisits($phaseId, $isReplicating);
        CohortSkipLogic::where('phase_id', $phaseId)->delete();
    }
}
