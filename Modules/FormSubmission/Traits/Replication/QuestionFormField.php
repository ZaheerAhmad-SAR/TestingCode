<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\FormFields;

trait QuestionFormField
{
    private function addReplicatedFormField($question, $newQuestionId, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $formField = $question->formFields()->first();

        $newFormFieldId = (string)Str::uuid();
        $newFormField = $formField->replicate();
        $newFormField->id = $newFormFieldId;
        $newFormField->question_id = $newQuestionId;
        $newFormField->parent_id = $formField->id;
        $newFormField->replicating_or_cloning = $replicating_or_cloning;
        $newFormField->save();
    }

    private function updateReplicatedFormField($formField, $replicatedFormField)
    {
        $formFieldAttributesArray = Arr::except($formField->attributesToArray(), ['id', 'question_id', 'parent_id', 'replicating_or_cloning']);
        $replicatedFormField->fill($formFieldAttributesArray);
        $replicatedFormField->update();
    }

    private function updateQuestionFormFieldToReplicatedVisits($formField, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $replicatedFormFields = FormFields::where('parent_id', 'like', $formField->id)
            ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
            ->get();
        foreach ($replicatedFormFields as $replicatedFormField) {
            $this->updateReplicatedFormField($formField, $replicatedFormField);
        }
    }

    private function deleteQuestionFormFieldToReplicatedVisits($formField, $isReplicating = true)
    {
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        if (null !== $formField) {
            $replicatedFormFields = FormFields::where('parent_id', 'like', $formField->id)
                ->where('replicating_or_cloning', 'like', $replicating_or_cloning)
                ->get();
            foreach ($replicatedFormFields as $replicatedFormField) {
                $replicatedFormField->delete();
            }
        }
    }
}
