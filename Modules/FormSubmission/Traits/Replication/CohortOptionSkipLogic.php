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

        $newOptionSkipLogicId = Str::uuid();
        $newOptionSkipLogic = $optionSkipLogic->replicate();
        $newOptionSkipLogic->id = $newOptionSkipLogicId;
        $newOptionSkipLogic->phase_id = $replicatedPhaseId;
        $newOptionSkipLogic->parent_id = $optionSkipLogic->id;
        $newOptionSkipLogic->replicating_or_cloning = $replicating_or_cloning;

        /*
        Phase ID Update
        */
        $replicatedQuestion = Question::where('parent_id', 'like', $optionSkipLogic->option_question_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->whereIn('id', StudyStructure::getQuestionIdsInPhaseArray($replicatedPhaseId))
            ->first();
        $newOptionSkipLogic->option_question_id = $replicatedQuestion->id;

        $newOptionSkipLogic->save();
    }

    private function updateOptionSkipLogicsToReplicatedVisits($phaseId, $isReplicating = true)
    {
        $this->deleteOptionSkipLogicsToReplicatedVisits($phaseId);
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            $optionSkipLogics = CohortSkipLogicOption::where('phase_id', 'like', $phaseId)->get();
            foreach ($optionSkipLogics as $optionSkipLogic) {
                $this->addPhaseOptionsSkipLogicToReplicatedPhase($optionSkipLogic, $replicatedPhase->id, $isReplicating);
            }
        }
    }

    private function deleteOptionSkipLogicsToReplicatedVisits($phaseId)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedPhases as $replicatedPhase) {
            CohortSkipLogicOption::where('phase_id', 'like', $replicatedPhase->id)->delete();
        }
    }

    private function deletePhaseOptionSkipLogics($phaseId)
    {
        $this->deleteOptionSkipLogicsToReplicatedVisits($phaseId);
        CohortSkipLogicOption::where('phase_id', $phaseId)->delete();
    }
}
