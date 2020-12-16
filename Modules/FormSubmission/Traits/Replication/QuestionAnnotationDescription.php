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
        $newQuestionAnnotationDescriptionId = Str::uuid();
        $newQuestionAnnotationDescription = $questionAnnotationDescription->replicate();
        $newQuestionAnnotationDescription->id = $newQuestionAnnotationDescriptionId;
        $newQuestionAnnotationDescription->question_id = $replicatedQuestionId;
        $newQuestionAnnotationDescription->save();
    }

    private function updateQuestionAnnotationDescriptionToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)->get();
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


    private function deleteQuestionAnnotationDescriptionsToReplicatedVisits($questionId)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $questionId)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            AnnotationDescription::where('question_id', 'like', $replicatedQuestion->id)->delete();
        }
    }

    private function deleteQuestionAnnotationDescriptions($questionId)
    {
        $this->deleteQuestionAnnotationDescriptionsToReplicatedVisits($questionId);
        AnnotationDescription::where('question_id', $questionId)->delete();
    }
}
