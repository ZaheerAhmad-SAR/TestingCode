<?php

namespace Modules\Admin\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\StudyStructure;

trait StepReplication
{

    private function addReplicatedStep($step, $newPhaseId)
    {
        $newStepId = Str::uuid();
        $newStep = $step->replicate();
        $newStep->step_id = $newStepId;
        $newStep->phase_id = $newPhaseId;
        $newStep->parent_id = $step->step_id;
        $newStep->save();
        return $newStepId;
    }

    private function updateReplicatedStep($step, $replicatedStep)
    {
        $stepAttributesArray = Arr::except($step->attributesToArray(), ['step_id', 'phase_id', 'parent_id']);
        $replicatedStep->fill($stepAttributesArray);
        $replicatedStep->update();
    }

    private function addStepToReplicatedVisits($newStep)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $newStep->phase_id)->get();
        foreach ($replicatedPhases as $phase) {
            $this->addReplicatedStep($newStep, $phase->id);
        }
    }

    private function updateStepToReplicatedVisits($step)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->step_id)->get();
        foreach ($replicatedSteps as $replicatedStep) {
            $this->updateReplicatedStep($step, $replicatedStep);
        }
    }

    private function deleteStepToReplicatedVisits($step)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->id)->get();
        foreach ($replicatedSteps as $replicatedStep) {
            $replicatedStep->delete();
        }
    }

    private function deleteStep($step)
    {
        foreach ($step->sections as $section) {
            $this->deleteSection($section);
        }
        $this->deleteStepToReplicatedVisits($step);
        $step->delete();
    }
}
