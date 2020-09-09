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
use Modules\Admin\Entities\FormFieldType;
use Modules\Admin\Entities\FormFields;

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
        $fields = FormFieldType::all();
        return view('admin::forms.index',compact('phases','option_groups','fields'));
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
    public function get_allQuestions($id)
    {
        $questions = Question::with('form_field_type','formFields')
        ->join('form_field','form_field.question_id','=','question.id')
        ->join('options_groups','options_groups.id','=','question.option_group_id' ,'left')
        ->where('question.section_id', '=', $id)->orderBy('question.question_sort', 'asc')->get();
        $questionsData['data'] = $questions;
        echo json_encode($questionsData);
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
        $question_info = Question::create([
            'id' => $id, 
            'form_field_type_id' => $request->form_field_type_id, 
            'section_id' => $request->section_id, 
            'option_group_id' => $request->option_group_id,
            'question_sort' => $request->question_sort,
            'question_text' => $request->question_text, 
            'c_disk' => $request->c_disk, 
            'measurement_unit' => $request->measurement_unit, 
            'is_dependent' => $request->field_dependent, 
            'dependent_on' => $request->dependent_on, 
            'annotations' => $request->dependent_on 
        ]);

        $last_id = Question::select('id')->latest()->first();
        $id    = Str::uuid();
        $form_field = FormFields::create([
            'id' => $id,
            'question_id' => $last_id->id,
            'variable_name' => $request->variable_name,
            'is_exportable_to_xls' => $request->is_exportable_to_xls, 
            'is_required' => $request->is_required, 
            'lower_limit' => $request->lower_limit, 
            'upper_limit' => $request->upper_limit, 
            'field_width' => $request->field_width, 
            'question_info' => $request->question_info, 
            'text_info' => $request->text_info, 
            'validation_rules' => $request->validation_rules, 
        ]);
        return redirect()->route('forms.index');
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($phase_id, $step_id)
    {
        $phase = StudyStructure::find($phase_id);
        $step = PhaseSteps::find($step_id);
        
        return view('admin::forms.preview_form')
        ->with('phase', $phase)
        ->with('step', $step);
    }

    public function showPreviewFullFlow($id)
    {
        $phase = StudyStructure::find($id);
        $steps = PhaseSteps::select('*')->where('phase_id',$id)->get();
        
        return view('admin::forms.preview_form_full_flow')
        ->with('phase', $phase)
        ->with('steps', $steps);
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
