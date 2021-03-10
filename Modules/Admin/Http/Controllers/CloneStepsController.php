<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\OptionsGroup;
use Modules\Admin\Entities\FormFieldType;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\Annotation;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\QuestionValidation;
use Modules\Admin\Entities\QuestionAdjudicationStatus;
use Modules\Admin\Entities\AnnotationDescription;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\SkipLogic;
use Illuminate\Support\Facades\DB;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class CloneStepsController extends Controller
{
    use ReplicatePhaseStructure;
    public function clone_phase(request $request)
    {
        $isReplicating = false;
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $phase = StudyStructure::find($request->phase_id);
        $id    = (string)Str::uuid();
        $phase = StudyStructure::create([
            'id'    => $id,
            'study_id'    => session('current_study'),
            'position'  =>  $request->position,
            'name' =>  $request->name,
            'duration' =>  $phase->duration,
            'is_repeatable' =>  $phase->is_repeatable,
            'count' =>  $phase->count,
            'parent_id' =>  $phase->id,
            'replicating_or_cloning' =>  $replicating_or_cloning,
        ]);
        $new_phase = StudyStructure::find($id);
        $newPhaseId = $new_phase->id;
        $all_steps = PhaseSteps::where('phase_id', $request->phase_id)->get();
        $newQuestionIdsArray = [];
        foreach ($all_steps as $step) {
            $newQuestionIdsArray = array_merge($newQuestionIdsArray, $this->steps_data($step->step_id, $newPhaseId));
        }

        foreach ($newQuestionIdsArray as $questionId => $newQuestionId) {
            $question = Question::find($questionId);

            /******************************* */
            /* Replicate Question Dependency */
            /******************************* */

            $this->addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating);

            /******************************* */
            /* Replicate Question Skip Logic */
            /******************************* */

            $this->updateSkipLogicsToReplicatedVisits($question->id, $isReplicating);

            /******************************* */
            /* Replicate Question Option Skip Logic */
            /******************************* */

            $this->updateOptionSkipLogicsToReplicatedVisits($question->id, $isReplicating);
        }
        /******************************* */
        /*** Replicate Cohort Skip Logic */
        /******************************* */
        foreach ($phase->cohortSkipLogics as $cohortSkipLogic) {
            $this->addPhaseSkipLogicToReplicatedPhase($cohortSkipLogic, $newPhaseId, false);
        }

        foreach ($phase->questionOptionsCohortSkipLogics as $cohortSkipLogic) {
            $this->addPhaseOptionsSkipLogicToReplicatedPhase($cohortSkipLogic, $newPhaseId, false);
        }
        return redirect()->route('study.index')->with('message', 'Phase Cloned Successfully!');
    }
    public function clone_steps(request $request)
    {
        $isReplicating = false;
        $newQuestionIdsArray = [];
        if (isset($request->phase) && count($request->phase) > 0) {
            ///// Clone to phases
            for ($i = 0; $i < count($request->phase); $i++) {
                $newQuestionIdsArray = array_merge($newQuestionIdsArray, $this->steps_data($request->step_id, $request->phase[$i]));
            }
            foreach ($newQuestionIdsArray as $questionId => $newQuestionId) {
                $question = Question::find($questionId);

                /******************************* */
                /* Replicate Question Dependency */
                /******************************* */

                $this->addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating);

                /******************************* */
                /* Replicate Question Skip Logic */
                /******************************* */

                $this->updateSkipLogicsToReplicatedVisits($question->id, $isReplicating);

                /******************************* */
                /* Replicate Question Option Skip Logic */
                /******************************* */

                $this->updateOptionSkipLogicsToReplicatedVisits($question->id, $isReplicating);
            }
            return redirect()->route('study.index')->with('message', 'Cloned Successfully!');
        }
    }
    public function clone_section(Request $request){
        $isReplicating = false;
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }
        $cloningSection = Section::find($request->cloning_section_id);
        $id    = (string)Str::uuid();
        $cloningSection = Section::create([
            'id'    => $id,
            'phase_steps_id'    => $cloningSection->phase_steps_id,
            'name'  =>  $request->sec_name,
            'description' =>  $request->sec_description,
            'sort_number' =>  $request->sort_num,
            'replicating_or_cloning' =>  $replicating_or_cloning
        ]);
        $new_section = Section::find($id);
        $newSectionId = $new_section->id;
        $all_questions = Question::where('section_id', $request->cloning_section_id)->get();

        /******************************* */
        /* Replicate Section Questions * */
        /******************************* */
        foreach ($all_questions as $question) {

            $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId, $isReplicating);

            /******************************* */
            /* Replicate Question Form Field */
            /******************************* */

            $this->addReplicatedFormFieldForSection($question, $newQuestionId, $isReplicating,$request);

            /******************************* */
            /* Replicate Question Data Validation */
            /******************************* */

            $this->addQuestionValidationToReplicatedQuestion($question->id, $newQuestionId, $isReplicating);

            /******************************* */
            /*Replicate Question Adjudication*/
            /******************************* */

            $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId, $isReplicating);
        }
        $data = [
            'success' => true,
            'message' => 'Section Cloned successfully',
            'step_id' => $cloningSection->phase_steps_id
        ];
        echo json_encode($data);
    }
    public function steps_data($step_id, $new_phase_id)
    {
        $newQuestionIdsArray = [];
        $isReplicating = false;
        $replicating_or_cloning = 'cloning';
        if ($isReplicating === true) {
            $replicating_or_cloning = 'replicating';
        }

        $step = PhaseSteps::where('step_id', '=', $step_id)->first();
        ///// Clone to phases
        $id    = (string)Str::uuid();
        PhaseSteps::create([
            'step_id'    => $id,
            'phase_id'    => $new_phase_id,
            'step_position'  =>  $step->step_position,
            'form_type_id' =>  $step->form_type_id,
            'modility_id' =>  $step->modility_id,
            'step_name' =>  $step->step_name,
            'step_description' =>  $step->step_description,
            'graders_number' =>  $step->graders_number,
            'q_c' =>  $step->q_c,
            'eligibility' =>  $step->eligibility,
            'form_version_num' =>  $step->form_version_num,
            'parent_id' =>  $step->step_id,
            'replicating_or_cloning' =>  $replicating_or_cloning,
        ]);
        foreach ($step->sections as $section) {

            $newSectionId = $this->addReplicatedSection($section, $id, $isReplicating);

            /******************************* */
            /* Replicate Section Questions * */
            /******************************* */
            foreach ($section->questions as $question) {

                $newQuestionIdsArray[$question->id] = $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId, $isReplicating);

                /******************************* */
                /* Replicate Question Form Field */
                /******************************* */

                $this->addReplicatedFormField($question, $newQuestionId, $isReplicating);

                /******************************* */
                /* Replicate Question Data Validation */
                /******************************* */

                $this->addQuestionValidationToReplicatedQuestion($question->id, $newQuestionId, $isReplicating);

                /******************************* */
                /*Replicate Question Adjudication*/
                /******************************* */

                $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId, $isReplicating);
            }
        }
        return $newQuestionIdsArray;
    }
}
