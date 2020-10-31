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
use Modules\Admin\Traits\Replication\ReplicatePhaseStructure;

class CloneStepsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('admin::index');
    }

    // clone steps to defferent phases 
    public function clone_steps(request $request)
    {
        $step_record = PhaseSteps::where('step_id','=',$request->step_id)->first();
        $all_sections = Section::where('phase_steps_id','=',$request->step_id)->get();
        if(isset($request->phase) && count($request->phase) > 0) {
            ///// Clone to phases
            for($i = 0; $i < count($request->phase); $i++) {
                $id    = Str::uuid();
                PhaseSteps::create([
                    'step_id'    => $id,
                    'phase_id'    => $request->phase[$i],
                    'step_position'  =>  $step_record->step_position,
                    'form_type_id' =>  $step_record->form_type_id,
                    'modility_id' =>  $step_record->modility_id,
                    'step_name' =>  $step_record->step_name,
                    'step_description' =>  $step_record->step_description,
                    'graders_number' =>  $step_record->graders_number,
                    'q_c' =>  $step_record->q_c,
                    'eligibility' =>  $step_record->eligibility
                ]);
            }
            ///// end here to Clone phases
            /// clone section 
            foreach($all_sections as $section){
                $id    = Str::uuid();
                Section::create([
                    'id'    => $id,
                    'phase_steps_id'    => $request->step_id,
                    'name'  =>  $section->sec_name,
                    'description' =>  $section->sec_description,
                    'sort_number' =>  $section->sort_num
                ]);
                
            }
            /// end of clone section
        }
        return redirect()->route('study.index')->with('message', 'Cloned Successfully!');        
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
