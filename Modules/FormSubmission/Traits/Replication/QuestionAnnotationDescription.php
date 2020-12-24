<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\AnnotationDescription;
use Modules\Admin\Entities\Question;

trait QuestionAnnotationDescription
{
    private function addReplicatedQuestionAnnotationDescription($questionAnnotationDescription, $replicatedQuestionId)
    {
        $newQuestionAnnotationDescriptionId = (string)Str::uuid();
        $newQuestionAnnotationDescription = $questionAnnotationDescription->replicate();
        $newQuestionAnnotationDescription->id = $newQuestionAnnotationDescriptionId;
        $newQuestionAnnotationDescription->question_id = $replicatedQuestionId;
        $newQuestionAnnotationDescription->save();
    }

    private function updateQuestionAnnotationDescriptionToReplicatedVisits($questionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $questionAnnotationDescriptions = AnnotationDescription::where('question_id', 'like', $questionId)->get();
            foreach ($questionAnnotationDescriptions as $questionAnnotationDescription) {
                $this->addReplicatedQuestionAnnotationDescription($questionAnnotationDescription, $replicatedQuestion->id);
            }
        }
    }

    private function addQuestionAnnotationDescriptionToReplicatedQuestion($questionId, $replicatedQuestionId)
    {
        $questionAnnotationDescriptions = AnnotationDescription::where('question_id', 'like', $questionId)->get();
        foreach ($questionAnnotationDescriptions as $questionAnnotationDescription) {
            $this->addReplicatedQuestionAnnotationDescription($questionAnnotationDescription, $replicatedQuestionId);
        }
    }


    private function deleteQuestionAnnotationDescriptionsToReplicatedVisits($questionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            AnnotationDescription::where('question_id', 'like', $replicatedQuestion->id)->delete();
        }
    }

    private function deleteQuestionAnnotationDescriptions($questionId, $isReplicating = true)
    {
        $this->deleteQuestionAnnotationDescriptionsToReplicatedVisits($questionId, $isReplicating);
        AnnotationDescription::where('question_id', $questionId)->delete();
    }
}
