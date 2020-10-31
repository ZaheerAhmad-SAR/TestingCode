<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\StudyStructure;

trait SectionReplication
{
    private function addReplicatedSection($section, $newStepId)
    {
        $newSectionId = Str::uuid();
        $newSection = $section->replicate();
        $newSection->id = $newSectionId;
        $newSection->phase_steps_id = $newStepId;
        $newSection->parent_id = $section->id;
        $newSection->save();
        return $newSectionId;
    }

    private function updateReplicatedSection($section, $replicatedSection)
    {
        $sectionAttributesArray = Arr::except($section->attributesToArray(), ['id', 'phase_steps_id', 'parent_id']);
        $replicatedSection->fill($sectionAttributesArray);
        $replicatedSection->update();
    }
    private function addSectionToReplicatedVisits($newSection)
    {
        $stepObj = PhaseSteps::find($newSection->phase_steps_id);
        $phaseId = $stepObj->phase_id;

        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)->get();
        foreach ($replicatedPhases as $phase) {
            foreach ($phase->steps as $step) {
                //parent_id of this step ==
                if ($step->parent_id == $newSection->phase_steps_id) {
                    $this->addReplicatedSection($newSection, $step->step_id);
                }
            }
        }
    }

    private function updateSectionToReplicatedVisits($section)
    {
        $replicatedSections = Section::where('parent_id', 'like', $section->id)->get();
        foreach ($replicatedSections as $replicatedSection) {
            $this->updateReplicatedSection($section, $replicatedSection);
        }
    }

    private function deleteSectionToReplicatedVisits($section)
    {
        $replicatedSection = Section::where('parent_id', 'like', $section->id)->get();
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
