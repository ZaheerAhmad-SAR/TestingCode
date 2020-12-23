<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\OptionSkipLogic;
use Modules\Admin\Entities\QuestionOption;
use Modules\Admin\Entities\StudyStructure;

trait QuestionOptionSkipLogic
{
    private function addQuestionOptionsSkipLogicToReplicatedQuestion($optionSkipLogic, $replicatedQuestionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $newOptionSkipLogicId = Str::uuid();
        $newOptionSkipLogic = $optionSkipLogic->replicate();
        $newOptionSkipLogic->id = $newOptionSkipLogicId;
        $newOptionSkipLogic->question_id = $replicatedQuestionId;
        $newOptionSkipLogic->parent_id = $optionSkipLogic->id;
        $newOptionSkipLogic->replicating_or_cloning = $replicating_or_cloning;

        /*
        Question ID Update
        */
        $replicatedPhaseId = StudyStructure::getPhaseIdByQuestionId($replicatedQuestionId);
        $replicatedQuestion = Question::where('parent_id', 'like', $optionSkipLogic->option_question_id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->whereIn('id', StudyStructure::getQuestionIdsInPhaseArray($replicatedPhaseId))
            ->first();
        $newOptionSkipLogic->option_question_id = $replicatedQuestion->id;

        $newOptionSkipLogic->save();
    }

    private function updateOptionSkipLogicsToReplicatedVisits($questionId, $isReplicating = true)
    {
        $this->deleteOptionSkipLogicsToReplicatedVisits($questionId);
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $optionSkipLogics = QuestionOption::where('question_id', 'like', $questionId)->get();
            foreach ($optionSkipLogics as $optionSkipLogic) {
                $this->addQuestionOptionsSkipLogicToReplicatedQuestion($optionSkipLogic, $replicatedQuestion->id, $isReplicating);
            }
        }
    }

    private function deleteOptionSkipLogicsToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            QuestionOption::where('question_id', 'like', $replicatedQuestion->id)->delete();
        }
    }

    private function deleteQuestionOptionSkipLogics($questionId)
    {
        $this->deleteOptionSkipLogicsToReplicatedVisits($questionId);
        QuestionOption::where('question_id', $questionId)->delete();
    }
}
