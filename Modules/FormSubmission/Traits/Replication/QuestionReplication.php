<?php

namespace Modules\FormSubmission\Traits\Replication;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionAdjudicationStatus;
use Modules\Admin\Entities\QuestionComments;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\StudyStructure;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\FormSubmission\Entities\QuestionAdjudicationRequired;
use Modules\Queries\Entities\Query;

trait QuestionReplication
{

    private function addReplicatedQuestion($question, $newSectionId, $isReplicating = true)
    {
        $newQuestionId = Str::uuid();
        $newQuestion = $question->replicate();
        $newQuestion->id = $newQuestionId;
        $newQuestion->section_id = $newSectionId;
        if ($isReplicating === true) {
            $newQuestion->parent_id = $question->id;
        }
        $newQuestion->save();
        return $newQuestionId;
    }

    private function updateReplicatedQuestion($question, $replicatedQuestion)
    {
        $questionAttributesArray = Arr::except($question->attributesToArray(), ['id', 'section_id', 'parent_id']);
        $replicatedQuestion->fill($questionAttributesArray);
        $replicatedQuestion->update();
    }

    private function addQuestionToReplicatedVisits($question, $isReplicating = true)
    {
        $sectionObj = Section::find($question->section_id);
        $stepObj = PhaseSteps::find($sectionObj->phase_steps_id);
        $phaseId = $stepObj->phase_id;

        $replicatedPhases = StudyStructure::where('parent_id', 'like', $phaseId)->withoutGlobalScopes()->get();

        foreach ($replicatedPhases as $phase) {
            foreach ($phase->steps as $step) {
                foreach ($step->sections as $section) {
                    if ($section->parent_id == $question->section_id) {
                        $replicatedQuestionId = $this->addReplicatedQuestion($question, $section->id, $isReplicating);
                        $this->addReplicatedFormField($question, $replicatedQuestionId, $isReplicating);
                        $this->addQuestionValidationToReplicatedQuestion($question->id, $replicatedQuestionId);
                        //$this->addQuestionSkipLogicToReplicatedQuestion($question->id, $replicatedQuestionId);
                        //$this->addQuestionAnnotationDescriptionToReplicatedQuestion($question->id, $replicatedQuestionId);
                        $this->addReplicatedQuestionDependency($question, $replicatedQuestionId, $isReplicating);
                        $this->addReplicatedQuestionAdjudicationStatus($question, $replicatedQuestionId, $isReplicating);
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

    private function deleteTreeAgainstPhase($phaseid)
    {
        $phase = StudyStructure::find($phaseid);
        $steps = PhaseSteps::where('phase_id', $phaseid)->get();
        foreach ($steps as $step) {
            $this->deleteStepAndSectionsAgainstStep($step->step_id);
        }
        $phase->delete();
    }

    private function deleteStepAndSectionsAgainstStep($stepid)
    {
        $step = PhaseSteps::find($stepid);
        $sections = Section::where('phase_steps_id', $stepid)->get();
        foreach ($sections as $section) {
            $this->deleteSectionAndQuestionsAgainstSection($section->id);
        }
        $step->delete();
    }

    private function deleteSectionAndQuestionsAgainstSection($sectionid)
    {
        $section = Section::find($sectionid);
        $questions = Question::where('section_id', $sectionid)->get();
        foreach ($questions as $question) {
            $this->deleteQuestionAndItsRelatedValues($question->id);
        }
        $section->delete();
    }

    private function deleteQuestionAndItsRelatedValues($questionId)
    {
        $question = Question::find($questionId);
        $formField = FormFields::where('question_id', $questionId)->first();
        $questionDependency = QuestionDependency::where('question_id', $questionId)->first();
        $questionAdjudicationStatus = QuestionAdjudicationStatus::where('question_id', $questionId)->first();

        $this->deleteQuestionFormFieldToReplicatedVisits($formField);
        $this->deleteQuestionValidations($questionId);
        $this->deleteQuestionDependenciesToReplicatedVisits($questionDependency);
        $this->deleteQuestionAdjudicationStatusesToReplicatedVisits($questionAdjudicationStatus);
        //$this->deleteQuestionAnnotationDescriptions($questionId);
        //$this->deleteQuestionSkipLogics($questionId);

        if (null !== $formField) {
            $formField->delete();
        }

        if (null !== $questionDependency) {
            $questionDependency->delete();
        }

        if (null !== $questionAdjudicationStatus) {
            $questionAdjudicationStatus->delete();
        }

        Answer::where('question_id', $questionId)->delete();
        FinalAnswer::where('question_id', $questionId)->delete();
        Query::where('question_id', $questionId)->delete();
        QuestionComments::where('question_id', $questionId)->delete();
        QuestionAdjudicationRequired::where('question_id', $questionId)->delete();

        $question->delete();
    }
}
