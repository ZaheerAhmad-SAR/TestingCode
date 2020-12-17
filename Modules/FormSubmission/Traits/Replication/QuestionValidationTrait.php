<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionValidation;

trait QuestionValidationTrait
{
    private function addReplicatedQuestionValidation($questionValidation, $replicatedQuestionId)
    {
        $newQuestionValidationId = Str::uuid();
        $newQuestionValidation = $questionValidation->replicate();
        $newQuestionValidation->id = $newQuestionValidationId;
        $newQuestionValidation->question_id = $replicatedQuestionId;
        $newQuestionValidation->save();
    }

    private function updateQuestionValidationToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $questionValidations = QuestionValidation::where('question_id', 'like', $questionId)->get();
            foreach ($questionValidations as $questionValidation) {
                $this->addReplicatedQuestionValidation($questionValidation, $replicatedQuestion->id);
            }
        }
    }

    private function addQuestionValidationToReplicatedQuestion($questionId, $replicatedQuestionId)
    {
        $questionValidations = QuestionValidation::where('question_id', 'like', $questionId)->get();
        foreach ($questionValidations as $questionValidation) {
            $this->addReplicatedQuestionValidation($questionValidation, $replicatedQuestionId);
        }
    }

    private function deleteQuestionValidationToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            QuestionValidation::where('question_id', 'like', $replicatedQuestion->id)->delete();
        }
    }

    private function deleteQuestionValidations($questionId)
    {
        $this->deleteQuestionValidationToReplicatedVisits($questionId);
        QuestionValidation::where('question_id', $questionId)->delete();
    }
}
