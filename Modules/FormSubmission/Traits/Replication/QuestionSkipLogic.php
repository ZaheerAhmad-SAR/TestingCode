<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\SkipLogic;
use Modules\Admin\Entities\QuestionOption;

trait QuestionSkipLogic
{
    private function addReplicatedQuestionSkipLogic($questionSkipLogic, $replicatedQuestionId)
    {
        $newQuestionSkipLogicId = Str::uuid();
        $newQuestionSkipLogic = $questionSkipLogic->replicate();
        $newQuestionSkipLogic->id = $newQuestionSkipLogicId;
        $newQuestionSkipLogic->question_id = $replicatedQuestionId;
        $newQuestionSkipLogic->save();
    }


    private function addReplicatedQuestionOption($questionOption, $replicatedQuestionId)
    {
        $newQuestionOptionId = Str::uuid();
        $newQuestionOption = $questionOption->replicate();
        $newQuestionOption->id = $newQuestionOptionId;
        $newQuestionOption->question_id = $replicatedQuestionId;
        $newQuestionOption->save();
    }

    private function updateQuestionSkipLogicToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $questionSkipLogics = SkipLogic::where('question_id', 'like', $questionId)->get();
            foreach ($questionSkipLogics as $questionSkipLogic) {
                $this->addReplicatedQuestionSkipLogic($questionSkipLogic, $replicatedQuestion->id);
            }
            $questionOptions = QuestionOption::where('question_id', 'like', $questionId)->get();
            foreach ($questionOptions as $questionOption) {
                $this->addReplicatedQuestionOption($questionOption, $replicatedQuestion->id);
            }
        }
    }

    private function addQuestionSkipLogicToReplicatedQuestion($questionId, $replicatedQuestionId)
    {
        $questionSkipLogics = SkipLogic::where('question_id', 'like', $questionId)->get();
        foreach ($questionSkipLogics as $questionSkipLogic) {
            $this->addReplicatedQuestionSkipLogic($questionSkipLogic, $replicatedQuestionId);
        }

        $questionOptions = QuestionOption::where('question_id', 'like', $questionId)->get();
        foreach ($questionOptions as $questionOption) {
            $this->addReplicatedQuestionOption($questionOption, $replicatedQuestionId);
        }
    }

    private function deleteQuestionSkipLogicsToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            SkipLogic::where('question_id', 'like', $replicatedQuestion->id)->delete();
            QuestionOption::where('question_id', 'like', $replicatedQuestion->id)->delete();
        }
    }

    private function deleteQuestionSkipLogics($questionId)
    {
        $this->deleteQuestionSkipLogicsToReplicatedVisits($questionId);
        SkipLogic::where('question_id', $questionId)->delete();
        QuestionOption::where('question_id', $questionId)->delete();
    }
}
