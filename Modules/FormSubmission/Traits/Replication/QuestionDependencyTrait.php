<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\QuestionDependency;

trait QuestionDependencyTrait
{
    private function addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating = true)
    {
        $questionDependency = $question->questionDependency()->first();
        if (null !== $questionDependency) {

            $newQuestionDependencyId = Str::uuid();
            $newQuestionDependency = $questionDependency->replicate();
            $newQuestionDependency->id = $newQuestionDependencyId;
            $newQuestionDependency->question_id = $newQuestionId;
            if ($isReplicating === true) {
                $newQuestionDependency->parent_id = $questionDependency->id;
            }
            $newQuestionDependency->save();
        }
    }

    private function updateReplicatedQuestionDependency($questionDependency, $replicatedQuestionDependency)
    {
        $questionDependencyAttributesArray = Arr::except($questionDependency->attributesToArray(), ['id', 'question_id', 'parent_id']);
        $replicatedQuestionDependency->fill($questionDependencyAttributesArray);
        $replicatedQuestionDependency->update();
    }

    private function updateQuestionDependenciesToReplicatedVisits($questionDependency)
    {
        $replicatedQuestionDependencies = QuestionDependency::where('parent_id', 'like', $questionDependency->id)->get();
        foreach ($replicatedQuestionDependencies as $replicatedQuestionDependency) {
            $this->updateReplicatedQuestionDependency($questionDependency, $replicatedQuestionDependency);
        }
    }

    private function deleteQuestionDependenciesToReplicatedVisits($questionDependency)
    {
        if (null !== $questionDependency) {
            $replicatedQuestionDependencies = QuestionDependency::where('parent_id', 'like', $questionDependency->id)->get();
            foreach ($replicatedQuestionDependencies as $replicatedQuestionDependency) {
                $replicatedQuestionDependency->delete();
            }
        }
    }
}
