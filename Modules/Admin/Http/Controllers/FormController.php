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
use Modules\Admin\Entities\Annotation;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\QuestionValidation;
use Modules\Admin\Entities\QuestionAdjudicationStatus;
use Modules\Admin\Entities\AnnotationDescription;
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
        $annotations = Annotation::all();
        return view('admin::forms.index',compact('phases','option_groups','fields','annotations'));
    }
    public function getall_options()
    {
        $options_dropdown = OptionsGroup::all();
        $optionsData['data'] = $options_dropdown;
        echo json_encode($optionsData);
    }
    public function get_phases($id)
    {
        $phases = StudyStructure::all();
        $data['data'] = $phases;
        echo json_encode($data);
    }

    public function get_steps_by_phaseId($id)
    {
        $PhaseSteps = PhaseSteps::select('*')->where('phase_id',$id)->get();
        $parentArray = $step = [];
        foreach($PhaseSteps as $phaseStep){                        
            $step['step_id'] = $phaseStep->step_id;
            $step['form_type'] = $phaseStep->formType->form_type;
            $step['step_name'] = $phaseStep->step_name;
            $parentArray[] = $step;
        }
        
        $stepsData['data'] = $parentArray;
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
    public function updateQustionsort(Request $request)
    {
        $question = Question::find($request->questionId);
        $question->question_sort  =  $request->sort_value;
        $question->save();
        $Response['data'] = 'success';
        echo json_encode($Response);
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
        /// question validation 
        $id    = Str::uuid();
        $Question_validation = [];
        for ($i = 0; $i < count($request->decision_one); $i++) {
            $Question_validation[] = [
                'id' => $id,
                'question_id' => $last_id->id,
                'decision_one' => $request->decision_one[$i],
                'opertaor_one' => $request->opertaor_one[$i],
                'dep_on_question_one_id' => $request->dep_on_question_one_id[$i],
                'condition' => $request->operator[$i],
                'decision_two' => $request->decision_two[$i],
                'opertaor_two' => $request->opertaor_two[$i],
                'error_type' => $request->error_type[$i],
                'error_message' => $request->error_message[$i]
            ];
        }
        QuestionValidation::insert($Question_validation);

        //Question dependencies 
        $id    = Str::uuid();
        $dependencies = QuestionDependency::create([
            'id' => $id,
            'question_id' => $last_id->id,
            'q_d_status' => $request->q_d_status,
            'dep_on_question_id' => $request->dep_on_question_id, 
            'opertaor' => $request->opertaor, 
            'custom_value' => $request->custom_value
            
        ]);
        // Question annotation
        $id    = Str::uuid();
        $annotation = [];
        for ($i = 0; $i < count($request->terminology_id); $i++) {
            $annotation[] = [
                'id' => $id,
                'question_id' => $last_id->id,
                'annotation_id' => $request->terminology_id[$i],
                'value' => $request->value[$i],
                'description' => $request->description[$i]
            ];
        }

        AnnotationDescription::insert($annotation);
        // Question Adjudication 
        $id    = Str::uuid();
        $adjStatus = QuestionAdjudicationStatus::create([
            'id' => $id,
            'question_id' => $last_id->id,
            'adj_status' => $request->adj_status,
            'decision_based_on' => $request->decision_based_on, 
            'opertaor' => $request->opertaor, 
            'custom_value' => $request->custom_value
            
        ]);
        return redirect()->route('forms.index');
    }
    public function update_questions(Request $request){
        dd($request->all());
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
    public function destroy($id='')
    {
        
    }
    function deleteQuestion($id){
        $question = Question::where('id',$id)->delete();
        $question = FormFields::where('question_id',$id)->delete();
        $Response['data'] = 'success';
        echo json_encode($Response); 
    }
}
