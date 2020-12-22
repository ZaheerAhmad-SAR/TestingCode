<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\StudyStructure;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FormRevisionHistory;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Entities\FormVersion;

trait StepReplication
{

    private function addReplicatedStep($step, $newPhaseId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $newStepId = Str::uuid();
        $newStep = $step->replicate();
        $newStep->step_id = $newStepId;
        $newStep->phase_id = $newPhaseId;
        $newStep->parent_id = $step->step_id;
        $newStep->replicating_or_cloning = $replicating_or_cloning;
        $newStep->save();
        $this->addFormVersionToReplicatedStep($step->step_id, $newStepId);
        return $newStepId;
    }

    private function updateReplicatedStep($step, $replicatedStep)
    {
        $stepAttributesArray = Arr::except($step->attributesToArray(), ['step_id', 'phase_id', 'parent_id', 'replicating_or_cloning']);
        $replicatedStep->fill($stepAttributesArray);
        $replicatedStep->update();
    }

    private function addStepToReplicatedVisits($newStep, $isReplicating = true)
    {
        $replicatedPhases = StudyStructure::where('parent_id', 'like', $newStep->phase_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->withoutGlobalScopes()->get();
        foreach ($replicatedPhases as $phase) {
            $this->addReplicatedStep($newStep, $phase->id, $isReplicating);
        }
    }

    private function addFormVersionToReplicatedStep($stepId, $replicatedStepId)
    {
        $stepFormVersions = FormVersion::where('step_id', 'like', $stepId)->get();
        foreach ($stepFormVersions as $stepFormVersion) {
            $this->addReplicatedStepFormVersion($stepFormVersion, $replicatedStepId);
        }
    }

    private function addReplicatedStepFormVersion($stepFormVersion, $replicatedStepId)
    {

        $newStepFormVersionId = Str::uuid();
        $newStepFormVersion = $stepFormVersion->replicate();
        $newStepFormVersion->id = $newStepFormVersionId;
        $newStepFormVersion->step_id = $replicatedStepId;
        $newStepFormVersion->save();
    }

    private function updateStepToReplicatedVisits($step)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->step_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSteps as $replicatedStep) {
            $this->updateReplicatedStep($step, $replicatedStep);
        }
    }

    private function deleteStepToReplicatedVisits($step)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->step_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSteps as $replicatedStep) {
            FormStatus::where('phase_steps_id', $replicatedStep->step_id)->delete();
            AdjudicationFormStatus::where('phase_steps_id', $replicatedStep->step_id)->delete();
            $replicatedStep->delete();
            $this->deleteFormVersion($replicatedStep);
        }

        FormStatus::where('phase_steps_id', $step->step_id)->delete();
        AdjudicationFormStatus::where('phase_steps_id', $step->step_id)->delete();
        $step->delete();
        $this->deleteFormVersion($step);
    }

    private function deleteFormVersion($step)
    {
        FormVersion::where('step_id', 'like', $step->step_id)->delete();
    }

    private function activateStepToReplicatedVisits($step, $default_data_option)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->step_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSteps as $replicatedStep) {
            $this->activateThisStep($replicatedStep, $default_data_option);
        }
        $this->activateThisStep($step, $default_data_option);
    }

    private function deActivateStepToReplicatedVisits($step)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->step_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSteps as $replicatedStep) {
            $this->deActivateThisStep($replicatedStep);
        }
        $this->deActivateThisStep($step);
    }

    private function putStepFormVersion($step)
    {
        $formVersion = FormVersion::getFormVersionObj($step->step_id);
        if (null !== $formVersion) {
            $newFormVersionNum = (int)$formVersion->form_version_num + 1;
            $formVersion->is_active = 0;
            $formVersion->update();
            ///////////////////////////////////////
            FormVersion::createFormVersion($step, $newFormVersionNum);
        } else {
            FormVersion::createFormVersion($step, 1);
        }
    }

    private function putDefaultAnswersInNewQuestionsOfAllSteps($step)
    {
        $replicatedSteps = PhaseSteps::where('parent_id', 'like', $step->step_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedSteps as $replicatedStep) {
            $this->putDefaultAnswersInNewQuestions($replicatedStep);
        }
        $this->putDefaultAnswersInNewQuestions($step);
    }

    private function putDefaultAnswersInNewQuestions($step)
    {
        $getSubjectsIdsAnswerArray = [
            'phase_steps_id' => $step->step_id,
        ];
        $answersArray = Answer::getAnswersAgainstStepId($getSubjectsIdsAnswerArray);
        if (count($answersArray) > 0) {
            foreach ($step->sections as $section) {
                foreach ($section->questions as $question) {
                    $getAnswerArray = [
                        'study_structures_id' => $step->phase_id,
                        'phase_steps_id' => $step->step_id,
                        'section_id' => $section->id,
                        'question_id' => $question->id,
                    ];
                    if (null === Answer::getAnswer($getAnswerArray)) {
                        $combinationArray = [];
                        foreach ($answersArray as $previousAnswer) {
                            //-------------------------------------
                            $combineString = $previousAnswer->form_filled_by_user_id . $previousAnswer->subject_id;
                            if (in_array($combineString, $combinationArray)) {
                                continue;
                            }
                            $combinationArray[] = $combineString;
                            //-------------------------------------

                            $defaultAnswerArray = [
                                'id' => Str::uuid(),
                                'study_id' => session('current_study'),
                                'form_filled_by_user_id' => $previousAnswer->form_filled_by_user_id,
                                'subject_id' => $previousAnswer->subject_id,
                                'study_structures_id' => $step->phase_id,
                                'phase_steps_id' => $step->step_id,
                                'section_id' => $section->id,
                                'question_id' => $question->id,
                                'field_id' => $question->formFields->id,
                                'answer' => '-9999',
                            ];
                            Answer::create($defaultAnswerArray);
                            /*----------------------------------*/
                        }
                    }
                }
            }
        }
    }

    private function activateThisStep($step, $default_data_option)
    {
        $step->is_active = 1;
        $step->update();
        $this->putStepFormVersion($step);
        if ($default_data_option == 'default_data_and_production_mode') {
            $this->putDefaultAnswersInNewQuestions($step);
        }
    }

    private function deActivateThisStep($step)
    {
        $step->is_active = 0;
        $step->update();
    }

    private function deleteStep($step)
    {
        foreach ($step->sections as $section) {
            $this->deleteSection($section);
        }
        $this->deleteStepToReplicatedVisits($step);
    }
}
