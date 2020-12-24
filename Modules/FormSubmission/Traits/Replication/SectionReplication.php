<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\StudyStructure;

trait SectionReplication
{
    private function addReplicatedSection($section, $newStepId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $newSectionId = (string)Str::uuid();
        $newSection = $section->replicate();
        $newSection->id = $newSectionId;
        $newSection->phase_steps_id = $newStepId;
        $newSection->parent_id = $section->id;
        $newSection->replicating_or_cloning = $replicating_or_cloning;
        $newSection->save();
        return $newSectionId;
    }

    private function updateReplicatedSection($section, $replicatedSection)
    {
        $sectionAttributesArray = Arr::except($section->attributesToArray(), ['id', 'phase_steps_id', 'parent_id', 'replicating_or_cloning']);
        $replicatedSection->fill($sectionAttributesArray);
        $replicatedSection->update();
    }
    private function addSectionToReplicatedVisits($newSection, $isReplicating = true)
    {
        $stepObj = PhaseSteps::find($newSection->phase_steps_id);
        $phaseId = $stepObj->phase_id;

        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)
            ->get();
        foreach ($replicatedPhases as $phase) {
            foreach ($phase->steps as $step) {
                //parent_id of this step ==
                if ($step->parent_id == $newSection->phase_steps_id) {
                    $this->addReplicatedSection($newSection, $step->step_id, $isReplicating);
                }
            }
        }
    }

    private function updateSectionToReplicatedVisits($section)
    {
        $replicatedSections = Section::where('parent_id', 'like', $section->id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSections as $replicatedSection) {
            $this->updateReplicatedSection($section, $replicatedSection);
        }
    }

    private function deleteSectionToReplicatedVisits($section)
    {
        $replicatedSection = Section::where('parent_id', 'like', $section->id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSection as $replicatedSection) {
            $replicatedSection->delete();
        }
    }

    private function deleteSection($section)
    {
        foreach ($section->questions as $question) {
            $this->deleteQuestionAndItsRelatedValues($question->id);
        }
        $this->deleteSectionToReplicatedVisits($section);
        $section->delete();
    }
}
