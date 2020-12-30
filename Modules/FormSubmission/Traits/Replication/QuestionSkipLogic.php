<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\SkipLogic;
use Modules\Admin\Entities\QuestionOption;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\StudyStructure;

trait QuestionSkipLogic
{
    private function addQuestionSkipLogicToReplicatedQuestion($skipLogic, $replicatedQuestionId, $isReplicating = true)
    {

        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $newSkipLogicId = (string)Str::uuid();
        $newSkipLogic = $skipLogic->replicate();
        $newSkipLogic->id = $newSkipLogicId;
        $newSkipLogic->question_id = $replicatedQuestionId;

        $newSkipLogic->parent_id = $skipLogic->id;
        $newSkipLogic->replicating_or_cloning = $replicating_or_cloning;

        $replicatedPhaseId = StudyStructure::getPhaseIdByQuestionId($replicatedQuestionId);

        // Update activate form ids
        $stepIdsArray = StudyStructure::getStepIdsInPhaseArray($replicatedPhaseId);
        $activateFormIds = arrayFilter(explode(',', $skipLogic->activate_forms));
        $activateFormArray = [];
        foreach ($activateFormIds as $activateFormId) {
            $replicatedForm = PhaseSteps::where('parent_id', 'like', $activateFormId)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->whereIn('step_id', $stepIdsArray)
                ->first();
            if (null !== $replicatedForm) {
                $activateFormArray[] = $replicatedForm->step_id;
            }
        }
        $newSkipLogic->activate_forms = implode(',', $activateFormArray);

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

        // Update activate section ids
        $sectionIdsArray = StudyStructure::getSectionIdsInPhaseArray($replicatedPhaseId);
        $activateSectionIds = arrayFilter(explode(',', $skipLogic->activate_sections));
        $activateSectionArray = [];
        foreach ($activateSectionIds as $activateSectionId) {
            $replicatedSection = Section::where('parent_id', 'like', $activateSectionId)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->whereIn('id', $sectionIdsArray)
                ->first();
            if (null !== $replicatedSection) {
                $activateSectionArray[] = $replicatedSection->id;
            }
        }
        $newSkipLogic->activate_sections = implode(',', $activateSectionArray);

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

        // Update activate question ids
        $questionIdsArray = StudyStructure::getQuestionIdsInPhaseArray($replicatedPhaseId);
        $activateQuestionIds = arrayFilter(explode(',', $skipLogic->activate_questions));
        $activateQuestionArray = [];
        foreach ($activateQuestionIds as $activateQuestionId) {
            $replicatedQuestion = Question::where('parent_id', 'like', $activateQuestionId)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->whereIn('id', $questionIdsArray)
                ->first();
            if (null !== $replicatedQuestion) {
                $activateQuestionArray[] = $replicatedQuestion->id;
            }
        }
        $newSkipLogic->activate_questions = implode(',', $activateQuestionArray);

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

    private function updateSkipLogicsToReplicatedVisits($questionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $this->deleteSkipLogicsToReplicatedVisits($questionId, $isReplicating);
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $skipLogics = SkipLogic::where('question_id', 'like', $questionId)->get();
            foreach ($skipLogics as $skipLogic) {
                $this->addQuestionSkipLogicToReplicatedQuestion($skipLogic, $replicatedQuestion->id, $isReplicating);
            }
        }
    }

    private function deleteSkipLogicsToReplicatedVisits($questionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            SkipLogic::where('question_id', 'like', $replicatedQuestion->id)->delete();
        }
    }

    private function deleteQuestionSkipLogics($questionId, $isReplicating = true)
    {
        $this->deleteSkipLogicsToReplicatedVisits($questionId, $isReplicating);
        SkipLogic::where('question_id', $questionId)->delete();
    }
}
