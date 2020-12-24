<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\QuestionAdjudicationStatus;

trait QuestionAdjudicationRequiredStatusTrait
{
    /*************************** Question Adjudication Statuses *****************************/

    private function addReplicatedQuestionAdjudicationStatus($question, $newQuestionId, $isReplicating = true)
    {
        $questionAdjudicationStatus = $question->questionAdjudicationStatus()->first();
        if (null !== $questionAdjudicationStatus) {
            $replicating_or_cloning = 'cloning';
            if ($isReplicating === true) {
                $replicating_or_cloning = 'replicating';
            }
            $newQuestionAdjudicationStatusId = (string)Str::uuid();
            $newQuestionAdjudicationStatus = $questionAdjudicationStatus->replicate();
            $newQuestionAdjudicationStatus->id = $newQuestionAdjudicationStatusId;
            $newQuestionAdjudicationStatus->question_id = $newQuestionId;
            $newQuestionAdjudicationStatus->parent_id = $questionAdjudicationStatus->id;
            $newQuestionAdjudicationStatus->replicating_or_cloning = $replicating_or_cloning;
            $newQuestionAdjudicationStatus->save();
        }
    }

    private function updateReplicatedQuestionAdjudicationStatus($questionAdjudicationStatus, $replicatedQuestionAdjudicationStatus)
    {
        $questionAdjudicationStatusAttributesArray = Arr::except($questionAdjudicationStatus->attributesToArray(), ['id', 'question_id', 'parent_id', 'replicating_or_cloning']);
        $replicatedQuestionAdjudicationStatus->fill($questionAdjudicationStatusAttributesArray);
        $replicatedQuestionAdjudicationStatus->update();
    }

    private function updateQuestionAdjudicationStatusesToReplicatedVisits($questionAdjudicationStatus, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $replicatedQuestionAdjudicationStatuses = QuestionAdjudicationStatus::where('parent_id', 'like', $questionAdjudicationStatus->id)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedQuestionAdjudicationStatuses as $replicatedQuestionAdjudicationStatus) {
            $this->updateReplicatedQuestionAdjudicationStatus($questionAdjudicationStatus, $replicatedQuestionAdjudicationStatus);
        }
    }

    private function deleteQuestionAdjudicationStatusesToReplicatedVisits($questionAdjudicationStatus, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        if (null !== $questionAdjudicationStatus) {
            $replicatedQuestionAdjudicationStatuses = QuestionAdjudicationStatus::where('parent_id', 'like', $questionAdjudicationStatus->id)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->get();
            foreach ($replicatedQuestionAdjudicationStatuses as $replicatedQuestionAdjudicationStatus) {
                $replicatedQuestionAdjudicationStatus->delete();
            }
        }
    }
}
