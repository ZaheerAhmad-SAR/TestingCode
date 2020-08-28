<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\OptionsGroup;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $phases = StudyStructure::all();
        $option_groups = OptionsGroup::all();
        return view('admin::forms.index',compact('phases','option_groups'));
    }
    public function get_steps_by_phaseId($id)
    {
        $PhaseSteps = PhaseSteps::select('*')->where('phase_id',$id)->get();
        $stepsData['data'] = $PhaseSteps;
        echo json_encode($stepsData);
    }
    public function get_section_by_stepId($id)
    {
        $section = Section::select('*')->where('phase_steps_id',$id)->orderBy('sort_number', 'asc')->get();
        $sectionData['data'] = $section;
        echo json_encode($sectionData);
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }
    public function add_questions(Request $request)
    {
        $id    = Str::uuid();
        $basic = array(
            'section_id' => $request->section_id, 
            'question_label' => $request->question_label, 
            'c-disc' => $request->c_disc, 
            'Variable_name' => $request->Variable_name, 
            'question_type' => $request->question_type,
            'question_info' => $request->question_info 
        );
        $basic = json_encode(serialize($basic));
        $question = Question::create([
            'id'    => $id,
            'study_id'  =>  '1bcd707e-2f8f-4a22-9b4a-c667a9479b8f',
            'section_id'  =>  $request->section_id,
            'type' =>  $request->question_type,
            'basic' =>  $basic,
            'data_validation' =>  'null',
            'dependencies' =>  'null',
            'annotations' =>  'null',
            'advanced' =>  'null'
        ]);
        return redirect()->route('forms.index');
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id='')
    {
        return view('admin::forms.form_loader');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
