<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\StudyStructure;

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

            $newQuestionDependencyId = (string)Str::uuid();
            $newQuestionDependency = $questionDependency->replicate();
            $newQuestionDependency->id = $newQuestionDependencyId;
            $newQuestionDependency->question_id = $newQuestionId;
            $newQuestionDependency->parent_id = $questionDependency->id;
            $newQuestionDependency->replicating_or_cloning = $replicating_or_cloning;

            /*
            Dependent on Question ID Update
            */
            $phaseId = StudyStructure::getPhaseIdByQuestionId($newQuestionId);
            $questionIds = StudyStructure::getQuestionIdsInPhaseArray($phaseId);
            $replicatedQuestion = Question::where('parent_id', 'like', $questionDependency->dep_on_question_id)
                ->where('replicating_or_cloning', 'like', 'replicating')
                ->whereIn('id', $questionIds)
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
