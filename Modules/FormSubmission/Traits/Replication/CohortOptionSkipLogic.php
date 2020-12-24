<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\CohortSkipLogicOption;
use Modules\Admin\Entities\Question;

trait CohortOptionSkipLogic
{
    private function addPhaseOptionsSkipLogicToReplicatedPhase($optionSkipLogic, $replicatedPhaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $newOptionSkipLogicId = (string)Str::uuid();
        $newOptionSkipLogic = $optionSkipLogic->replicate();
        $newOptionSkipLogic->id = $newOptionSkipLogicId;
        $newOptionSkipLogic->phase_id = $replicatedPhaseId;
        $newOptionSkipLogic->parent_id = $optionSkipLogic->id;
        $newOptionSkipLogic->replicating_or_cloning = $replicating_or_cloning;

        /*
        Phase ID Update
        */
        $replicatedQuestion = Question::where('parent_id', 'like', $optionSkipLogic->option_question_id)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->whereIn('id', StudyStructure::getQuestionIdsInPhaseArray($replicatedPhaseId))
            ->first();
        $newOptionSkipLogic->option_question_id = $replicatedQuestion->id;

        $newOptionSkipLogic->save();
    }

    private function updateCohortOptionSkipLogicsToReplicatedVisits($phaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $this->deleteCohortOptionSkipLogicsToReplicatedVisits($phaseId, $isReplicating);
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $optionSkipLogics = CohortSkipLogicOption::where('phase_id', 'like', $phaseId)->get();
            foreach ($optionSkipLogics as $optionSkipLogic) {
                $this->addPhaseOptionsSkipLogicToReplicatedPhase($optionSkipLogic, $replicatedPhase->id, $isReplicating);
            }
        }
    }

    private function deleteCohortOptionSkipLogicsToReplicatedVisits($phaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            CohortSkipLogicOption::where('phase_id', 'like', $replicatedPhase->id)->delete();
        }
    }

    private function deleteCohortPhaseOptionSkipLogics($phaseId, $isReplicating = true)
    {
        $this->deleteCohortOptionSkipLogicsToReplicatedVisits($phaseId, $isReplicating);
        CohortSkipLogicOption::where('phase_id', $phaseId)->delete();
    }
}
