<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionDependency;

trait QuestionDependencyTrait
{
    private function addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating = true)
    {
        $questionDependency = $question->questionDependency()->first();
        if (null !== $questionDependency) {
            $replicating_or_cloning = 'cloning';
            if ($isReplicating === true) {
                $replicating_or_cloning = 'replicating';
            }

            $newQuestionDependencyId = Str::uuid();
            $newQuestionDependency = $questionDependency->replicate();
            $newQuestionDependency->id = $newQuestionDependencyId;
            $newQuestionDependency->question_id = $newQuestionId;
            $newQuestionDependency->parent_id = $questionDependency->id;
            $newQuestionDependency->replicating_or_cloning = $replicating_or_cloning;

            /*
            Dependent on Question ID Update
            */
            $replicatedQuestion = Question::where('parent_id', 'like', $question->dep_on_question_id)
                ->where('replicating_or_cloning', 'like', 'replicating')
                ->first();
            $newQuestionDependency->dep_on_question_id = $replicatedQuestion->id;

            $newQuestionDependency->save();
        }
    }

    private function updateReplicatedQuestionDependency($questionDependency, $replicatedQuestionDependency)
    {
        $questionDependencyAttributesArray = Arr::except($questionDependency->attributesToArray(), ['id', 'question_id', 'parent_id', 'replicating_or_cloning']);
        $replicatedQuestionDependency->fill($questionDependencyAttributesArray);
        $replicatedQuestionDependency->update();
    }

    private function updateQuestionDependenciesToReplicatedVisits($questionDependency)
    {
        $replicatedQuestionDependencies = QuestionDependency::where('parent_id', 'like', $questionDependency->id)
            ->where('replicating_or_cloning', 'like', 'replicating')
            ->get();
        foreach ($replicatedQuestionDependencies as $replicatedQuestionDependency) {
            $this->updateReplicatedQuestionDependency($questionDependency, $replicatedQuestionDependency);
        }
    }

    private function deleteQuestionDependenciesToReplicatedVisits($questionDependency)
    {
        if (null !== $questionDependency) {
            $replicatedQuestionDependencies = QuestionDependency::where('parent_id', 'like', $questionDependency->id)
                ->where('replicating_or_cloning', 'like', 'replicating')
                ->get();
            foreach ($replicatedQuestionDependencies as $replicatedQuestionDependency) {
                $replicatedQuestionDependency->delete();
            }
        }
    }
}
