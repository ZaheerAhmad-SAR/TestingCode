<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionAdjudicationStatus;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\QuestionValidation;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\StudyStructure;

trait QuestionReplication
{
    private function addReplicatedQuestion($question, $newSectionId)
    {
        $newQuestionId = Str::uuid();
        $newQuestion = $question->replicate();
        $newQuestion->id = $newQuestionId;
        $newQuestion->section_id = $newSectionId;
        $newQuestion->parent_id = $question->id;
        $newQuestion->save();
        return $newQuestionId;
    }

    private function updateReplicatedQuestion($question, $replicatedQuestion)
    {
        $questionAttributesArray = Arr::except($question->attributesToArray(), ['id', 'section_id', 'parent_id']);
        $replicatedQuestion->fill($questionAttributesArray);
        $replicatedQuestion->update();
    }

    private function addQuestionToReplicatedVisits($newQuestion)
    {
        $sectionObj = Section::find($newQuestion->section_id);
        $stepObj = PhaseSteps::find($sectionObj->phase_steps_id);
        $phaseId = $stepObj->phase_id;

        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)->get();
        foreach ($replicatedPhases as $phase) {
            foreach ($phase->steps as $step) {
                foreach ($step->sections as $section) {
                    if ($section->parent_id == $newQuestion->section_id) {
                        $newQuestionId = $this->addReplicatedQuestion($newQuestion, $section->id);
                        $this->addReplicatedFormField($newQuestion, $newQuestionId);
                        $this->addReplicatedQuestionValidation($newQuestion, $newQuestionId);
                        $this->addReplicatedQuestionDependency($newQuestion, $newQuestionId);
                        $this->addReplicatedQuestionAdjudicationStatus($newQuestion, $newQuestionId);
                    }
                }
            }
        }
    }

    private function updateQuestionToReplicatedVisits($question)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $question->id)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $this->updateReplicatedQuestion($question, $replicatedQuestion);
        }
    }

    private function deleteQuestionToReplicatedVisits($question)
    {
        $replicatedQuestions = Question::where('parent_id', 'like', $question->id)->get();
        foreach ($replicatedQuestions as $replicatedQuestion) {
            $replicatedQuestion->delete();
        }
    }

    /*********************** Form Field *************************** */
    private function addReplicatedFormField($question, $newQuestionId)
    {
        $formField = $question->formFields()->first();

        $newFormFieldId = Str::uuid();
        $newFormField = $formField->replicate();
        $newFormField->id = $newFormFieldId;
        $newFormField->question_id = $newQuestionId;
        $newFormField->parent_id = $formField->id;
        $newFormField->save();
    }

    private function updateReplicatedFormField($formField, $replicatedFormField)
    {
        $formFieldAttributesArray = Arr::except($formField->attributesToArray(), ['id', 'question_id', 'parent_id']);
        $replicatedFormField->fill($formFieldAttributesArray);
        $replicatedFormField->update();
    }

    private function updateQuestionFormFieldToReplicatedVisits($formField)
    {
        $replicatedFormFields = FormFields::where('parent_id', 'like', $formField->id)->get();
        foreach ($replicatedFormFields as $replicatedFormField) {
            $this->updateReplicatedFormField($formField, $replicatedFormField);
        }
    }

    private function deleteQuestionFormFieldToReplicatedVisits($formField)
    {
        if (null !== $formField) {
            $replicatedFormFields = FormFields::where('parent_id', 'like', $formField->id)->get();
            foreach ($replicatedFormFields as $replicatedFormField) {
                $replicatedFormField->delete();
            }
        }
    }

    /*************************** Question Validation *****************************/

    private function addReplicatedQuestionValidation($question, $newQuestionId)
    {
        $questionValidation = $question->questionValidation()->first();
        if (null !== $questionValidation) {

            $newQuestionValidationId = Str::uuid();
            $newQuestionValidation = $questionValidation->replicate();
            $newQuestionValidation->id = $newQuestionValidationId;
            $newQuestionValidation->question_id = $newQuestionId;
            $newQuestionValidation->parent_id = $questionValidation->id;
            $newQuestionValidation->save();
        }
    }

    private function updateReplicatedQuestionValidation($questionValidation, $replicatedQuestionValidation)
    {
        $questionValidationAttributesArray = Arr::except($questionValidation->attributesToArray(), ['id', 'question_id', 'parent_id']);
        $replicatedQuestionValidation->fill($questionValidationAttributesArray);
        $replicatedQuestionValidation->update();
    }

    private function updateQuestionValidationToReplicatedVisits($questionValidation)
    {
        $replicatedQuestionValidations = QuestionValidation::where('parent_id', 'like', $questionValidation->id)->get();
        foreach ($replicatedQuestionValidations as $replicatedQuestionValidation) {
            $this->updateReplicatedQuestionValidation($questionValidation, $replicatedQuestionValidation);
        }
    }

    private function deleteQuestionValidationToReplicatedVisits($questionValidation)
    {
        if (null !== $questionValidation) {
            $replicatedQuestionValidations = QuestionValidation::where('parent_id', 'like', $questionValidation->id)->get();
            foreach ($replicatedQuestionValidations as $replicatedQuestionValidation) {
                $replicatedQuestionValidation->delete();
            }
        }
    }

    /*************************** Question Dependencies *****************************/

    private function addReplicatedQuestionDependency($question, $newQuestionId)
    {
        $questionDependency = $question->questionDependency()->first();
        if (null !== $questionDependency) {

            $newQuestionDependencyId = Str::uuid();
            $newQuestionDependency = $questionDependency->replicate();
            $newQuestionDependency->id = $newQuestionDependencyId;
            $newQuestionDependency->question_id = $newQuestionId;
            $newQuestionDependency->parent_id = $questionDependency->id;
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

    /*************************** Question Adjudication Statuses *****************************/

    private function addReplicatedQuestionAdjudicationStatus($question, $newQuestionId)
    {
        $questionAdjudicationStatus = $question->questionAdjudicationStatus()->first();
        if (null !== $questionAdjudicationStatus) {

            $newQuestionAdjudicationStatusId = Str::uuid();
            $newQuestionAdjudicationStatus = $questionAdjudicationStatus->replicate();
            $newQuestionAdjudicationStatus->id = $newQuestionAdjudicationStatusId;
            $newQuestionAdjudicationStatus->question_id = $newQuestionId;
            $newQuestionAdjudicationStatus->parent_id = $questionAdjudicationStatus->id;
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

    private function deleteQuestionAndItsRelatedValues($questionId)
    {
        $question = Question::find($questionId);
        $formField = FormFields::where('question_id', $questionId)->first();
        $questionValidation = QuestionValidation::where('question_id', $questionId)->first();
        $questionDependency = QuestionDependency::where('question_id', $questionId)->first();
        $questionAdjudicationStatus = QuestionAdjudicationStatus::where('question_id', $questionId)->first();

        $this->deleteQuestionFormFieldToReplicatedVisits($formField);
        $this->deleteQuestionValidationToReplicatedVisits($questionValidation);
        $this->deleteQuestionDependenciesToReplicatedVisits($questionDependency);
        $this->deleteQuestionAdjudicationStatusesToReplicatedVisits($questionAdjudicationStatus);

        if (null !== $formField) {
            $formField->delete();
        }
        if (null !== $questionValidation) {
            $questionValidation->delete();
        }
        if (null !== $questionDependency) {
            $questionDependency->delete();
        }
        if (null !== $questionAdjudicationStatus) {
            $questionAdjudicationStatus->delete();
        }

        $question->delete();
    }
}
