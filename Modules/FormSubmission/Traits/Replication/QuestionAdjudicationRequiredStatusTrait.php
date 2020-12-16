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

            $newQuestionAdjudicationStatusId = Str::uuid();
            $newQuestionAdjudicationStatus = $questionAdjudicationStatus->replicate();
            $newQuestionAdjudicationStatus->id = $newQuestionAdjudicationStatusId;
            $newQuestionAdjudicationStatus->question_id = $newQuestionId;
            if ($isReplicating === true) {
                $newQuestionAdjudicationStatus->parent_id = $questionAdjudicationStatus->id;
            }
            $newQuestionAdjudicationStatus->save();
        }
    }

    private function updateReplicatedQuestionAdjudicationStatus($questionAdjudicationStatus, $replicatedQuestionAdjudicationStatus)
    {
        $questionAdjudicationStatusAttributesArray = Arr::except($questionAdjudicationStatus->attributesToArray(), ['id', 'question_id', 'parent_id']);
        $replicatedQuestionAdjudicationStatus->fill($questionAdjudicationStatusAttributesArray);
        $replicatedQuestionAdjudicationStatus->update();
    }

    private function updateQuestionAdjudicationStatusesToReplicatedVisits($questionAdjudicationStatus)
    {
        $replicatedQuestionAdjudicationStatuses = QuestionAdjudicationStatus::where('parent_id', 'like', $questionAdjudicationStatus->id)->get();
        foreach ($replicatedQuestionAdjudicationStatuses as $replicatedQuestionAdjudicationStatus) {
            $this->updateReplicatedQuestionAdjudicationStatus($questionAdjudicationStatus, $replicatedQuestionAdjudicationStatus);
        }
    }

    private function deleteQuestionAdjudicationStatusesToReplicatedVisits($questionAdjudicationStatus)
    {
        if (null !== $questionAdjudicationStatus) {
            $replicatedQuestionAdjudicationStatuses = QuestionAdjudicationStatus::where('parent_id', 'like', $questionAdjudicationStatus->id)->get();
            foreach ($replicatedQuestionAdjudicationStatuses as $replicatedQuestionAdjudicationStatus) {
                $replicatedQuestionAdjudicationStatus->delete();
            }
        }
    }
}
