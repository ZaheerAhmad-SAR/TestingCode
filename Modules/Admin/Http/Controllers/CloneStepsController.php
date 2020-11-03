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
use Modules\Admin\Entities\skipLogic;
use Illuminate\Support\Facades\DB;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;
class CloneStepsController extends Controller
{
    use ReplicatePhaseStructure;
    public function clone_phase(request $request)
    {
        $phase = StudyStructure::where('id','=',$request->phase_id)->first();
        $id    = Str::uuid();
        $phase = StudyStructure::create([
            'id'    => $id,
            'study_id'    => session('current_study'),
            'position'  =>  $phase->position,
            'name' =>  $request->name,
            'duration' =>  $phase->duration,
            'is_repeatable' =>  $phase->is_repeatable,
        ]);
        $new_phase = StudyStructure::find($id);
        $new_phase_id = $new_phase->id;
        $all_steps = PhaseSteps::where('phase_id',$request->phase_id)->get();
        foreach ($all_steps as $step) {
           $this->steps_data($step->step_id,$new_phase_id);
        }
        return redirect()->route('study.index')->with('message', 'Phase Cloned Successfully!');
    }
    public function clone_steps(request $request)
    {
            if(isset($request->phase) && count($request->phase) > 0) {
            ///// Clone to phases
            for($i = 0; $i < count($request->phase); $i++) {
                $this->steps_data($request->step_id,$request->phase[$i]);
            }
            return redirect()->route('study.index')->with('message', 'Cloned Successfully!');        
        }
   
    }
    public function steps_data($step_id,$new_phase_id){
        $step = PhaseSteps::where('step_id','=',$step_id)->first();
        ///// Clone to phases
        $id    = Str::uuid();
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
            'eligibility' =>  $step->eligibility
        ]);
        foreach ($step->sections as $section) {

            $newSectionId = $this->addReplicatedSection($section, $id,$isReplicating = false);

            /******************************* */
            /* Replicate Section Questions * */
            /******************************* */
            foreach ($section->questions as $question) {

                $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId, $isReplicating = false);

                /******************************* */
                /* Replicate Question Form Field */
                /******************************* */

                $this->addReplicatedFormField($question, $newQuestionId, $isReplicating = false);

                /******************************* */
                /* Replicate Question Data Validation */
                /******************************* */

                $this->updateQuestionValidationToReplicatedVisits($question->id, $isReplicating = false);

                /******************************* */
                /* Replicate Question Dependency */
                /******************************* */

                $this->addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating = false);

                /******************************* */
                /*Replicate Question Adjudication*/
                /******************************* */

                $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId,$isReplicating = false);
            }
        }
    }
}
