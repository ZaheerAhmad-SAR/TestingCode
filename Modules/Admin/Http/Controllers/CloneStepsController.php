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
        dd($request->all());
    }
    public function clone_steps(request $request)
    {
        $step = PhaseSteps::where('step_id','=',$request->step_id)->first();
        if(isset($request->phase) && count($request->phase) > 0) {
        ///// Clone to phases
        for($i = 0; $i < count($request->phase); $i++) {
            $id    = Str::uuid();
            PhaseSteps::create([
                'step_id'    => $id,
                'phase_id'    => $request->phase[$i],
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

                    $newSectionId = $this->addReplicatedSection($section, $id);

                    /******************************* */
                    /* Replicate Section Questions * */
                    /******************************* */
                    foreach ($section->questions as $question) {

                        $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId);

                        /******************************* */
                        /* Replicate Question Form Field */
                        /******************************* */

                        $this->addReplicatedFormField($question, $newQuestionId);

                        /******************************* */
                        /* Replicate Question Data Validation */
                        /******************************* */

                        $this->updateQuestionValidationToReplicatedVisits($question->id);

                        /******************************* */
                        /* Replicate Question Dependency */
                        /******************************* */

                        $this->addReplicatedQuestionDependency($question, $newQuestionId);

                        /******************************* */
                        /*Replicate Question Adjudication*/
                        /******************************* */

                        $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId);
                    }
            }
        }
        return redirect()->route('study.index')->with('message', 'Cloned Successfully!');        
    }
   
}

}
