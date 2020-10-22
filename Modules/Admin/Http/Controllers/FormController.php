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
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\skipLogic;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $phases = StudyStructure::select('*')->where('study_id', session('current_study'))->get();
        $option_groups = OptionsGroup::all();
        $fields = FormFieldType::all();
        $annotations = Annotation::all();
        return view('admin::forms.index', compact('phases', 'option_groups', 'fields', 'annotations'));
    }
    public function getall_options()
    {
        $options_dropdown = OptionsGroup::all();
        $optionsData['data'] = $options_dropdown;
        echo json_encode($optionsData);
    }
    public function get_phases($id)
    {
        $phases = StudyStructure::select('*')->where('study_id', session('current_study'))->get();
        $data['data'] = $phases;
        echo json_encode($data);
    }

    public function get_steps_by_phaseId($id)
    {
        $PhaseSteps = PhaseSteps::select('*')->where('phase_id', $id)->get();
        $parentArray = $step = [];
        foreach ($PhaseSteps as $phaseStep) {
            $step['step_id'] = $phaseStep->step_id;
            $step['form_type'] = $phaseStep->formType->form_type;
            $step['step_name'] = $phaseStep->step_name;
            $parentArray[] = $step;
        }

        $stepsData['data'] = $parentArray;
        echo json_encode($stepsData);
    }

    public function get_sections_against_step($id)
    {
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $sectionData['data'] = $section;
        echo json_encode($sectionData);
    }
    public function get_Questions($id)
    {
        $questions = Question::with('formFields', 'form_field_type', 'optionsGroup')
            ->where('question.section_id', '=', $id)->orderBy('question.question_sort', 'asc')->get();
        $questionsData['data'] = $questions;
        echo json_encode($questionsData);
    }
    public function get_section_by_stepId($id)
    {
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        $section_contents .= '<div id="accordion">';
        foreach ($section as $key => $value) {
            $show = ($key == 0) ? 'show' : '';
            $section_contents .= '<div class="card"><div class="card-header"><a class="card-link" data-toggle="collapse" href="#collapse_' . $value->id . '">' . $value->sort_number . '&nbsp;&nbsp;&nbsp;&nbsp;' . $value->name . '</a></div><div id="collapse_' . $value->id . '" class="collapse ' . $show . '" data-parent="#accordion"><div class="card-body questions_' . $value->id . '">';
            $section_contents .= $this->get_allQuestions($value->id);
            $section_contents .= '</div></div></div>';
        }
        $section_contents .= '</div>';
        return Response($section_contents);
    }
    public function sections_skip_logic($id)
    {
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            $section_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon get_ques_ac" title="Log Details" data-toggle="collapse" data-target=".row-' . $value->id . '-ac" style="font-size: 20px; color: #1e3d73;"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="activate_sections[' . $key . '][]" value="' . $value->id . '"> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody>
                                </table>
                                 </div>
                            </div>
                                    <div class="card-body collapse row-' . $value->id . '-ac " style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="ac_questions_list_' . $value->id . '">

                                    </tbody>
                                </table>  </div></div>';
        }
        return Response($section_contents);
    }
    public function sections_skip_logic_deactivate($id)
    {
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            $section_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon get_ques_de" title="Log Details" data-toggle="collapse" data-target=".row-' . $value->id . '-de" style="font-size: 20px; color: #1e3d73;"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="deactivate_sections[' . $key . '][]" value="' . $value->id . '"> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody>
                                </table>
                                 </div>
                            </div>
                                    <div class="card-body collapse row-' . $value->id . '-de " style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="de_questions_list_' . $value->id . '">

                                    </tbody>
                                </table>  </div></div>';
        }
        return Response($section_contents);
    }
    public function questions_skip_logic($id)
    {
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $key => $value) {
            $question_contents .= '<tr><td class="sec_id" style="display: none;">' . $value->id . '</td>
                                        <td style="text-align: center;width:15%;">
                                        <input type="checkbox" name="activate_questions[' . $key . '][]" value="' . $value->id . '">
                                    </td><td  colspan="5"> ' . $value->question_text . '</td>';
            $question_contents .= '</tr>';
        }
        return Response($question_contents);
    }
    public function questions_skip_logic_deactivate($id)
    {
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $key => $value) {
            $question_contents .= '<tr><td class="sec_id" style="display: none;">' . $value->id . '</td>
                                        <td style="text-align: center;width:15%;">
                                        <input type="checkbox" name="deactivate_questions[' . $key . '][]" value="' . $value->id . '">
                                    </td><td  colspan="5"> ' . $value->question_text . '</td>';
            $question_contents .= '</tr>';
        }
        return Response($question_contents);
    }
    // add question check start
    // Question activate and deactivate
    public function add_skipLogic(Request $request)
    {
        $skip_ques = [];
        if (isset($request->option_title) && count($request->option_title) > 0) {
            for ($i = 0; $i < count($request->option_title); $i++) {
                $skip_ques = [
                    'id' => Str::uuid(),
                    'question_id' => '634196f7-7efe-44f9-a4c9-fa02bb2bab3d',
                    'option_title' => $request->option_title[$i],
                    'option_value' => $request->option_value[$i],
                    'activate_forms' => implode(',', (array)$request->activate_forms[$i]),
                    'activate_sections' => implode(',', (array)$request->activate_sections[$i]),
                    'activate_questions' => implode(',', (array)$request->activate_questions[$i]),
                    'deactivate_forms' => implode(',', (array)$request->deactivate_forms[$i]),
                    'deactivate_sections' => implode(',', (array)$request->deactivate_sections[$i]),
                    'deactivate_questions' => implode(',', (array)$request->deactivate_questions[$i])
                ];
                skipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('forms.skipLogic', '634196f7-7efe-44f9-a4c9-fa02bb2bab3d')->with('message', 'Checks Applied Successfully!');
    }
    // add question check end
    public function get_allQuestions($id = '')
    {
        $questions = Question::with('formFields', 'form_field_type', 'optionsGroup', 'DependentQuestion', 'AdjStatus')
            ->where('question.section_id', '=', $id)->orderBy('question.question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $ques_value) {
            $question_contents .= '<div class="form-group row custom_fields">';
            $question_contents .= '<input type="hidden" class="question_id" value="' . $ques_value->id . '">
            <input type="hidden" class="formFields_id" value="' . $ques_value->formFields->id . '">
            <input type="hidden" class="question_sort" value="' . $ques_value->question_sort . '">
            <input type="hidden" class="question_type_id" value="' . $ques_value->form_field_type->id . '">
            <input type="hidden" class="section_id" value="' . $id . '">';
            $question_contents .= '<input type="hidden" class="option_group_id" value="' . $ques_value->option_group_id . '">
            <input type="hidden" class="c_disk" value="' . $ques_value->c_disk . '">
            <input type="hidden" class="question_text" value="' . $ques_value->question_text . '">
            <input type="hidden" class="variable_name" value="' . $ques_value->formFields->variable_name . '">
            <input type="hidden" class="text_info" value="' . $ques_value->formFields->text_info . '">
            <input type="hidden" class="is_required" value="' . $ques_value->formFields->is_required . '">
            <input type="hidden" class="is_exportable_to_xls" value="' . $ques_value->formFields->is_exportable_to_xls . '">
            <input type="hidden" class="field_width" value="' . $ques_value->formFields->field_width . '">
            <input type="hidden" class="measurement_unit" value="' . $ques_value->measurement_unit . '">
            <input type="hidden" class="lower_limit" value="' . $ques_value->formFields->lower_limit . '">
            <input type="hidden" class="upper_limit" value="' . $ques_value->formFields->upper_limit . '">';
            $question_contents .= '<input type="hidden" class="question_type" value="' . $ques_value->form_field_type->field_type . '">
            <input type="hidden" class="dependency_id" value="' . $ques_value->DependentQuestion->id . '">
            <input type="hidden" class="dependency_status" value="' . $ques_value->DependentQuestion->q_d_status . '">
            <input type="hidden" class="dependency_operator" value="' . $ques_value->DependentQuestion->opertaor . '">
            <input type="hidden" class="dependency_question" value="' . $ques_value->DependentQuestion->dep_on_question_id . '">
            <input type="hidden" class="dependency_custom_value" value="' . $ques_value->DependentQuestion->custom_value . '">
            <input type="hidden" class="adj_id" value="' . $ques_value->AdjStatus->id . '">
            <input type="hidden" class="adj_status" value="' . $ques_value->AdjStatus->adj_status . '">
            <input type="hidden" class="adj_decision_based" value="' . $ques_value->AdjStatus->decision_based_on . '">
            <input type="hidden" class="adj_operator" value="' . $ques_value->AdjStatus->opertaor . '">
            <input type="hidden" class="adj_custom_value" value="' . $ques_value->AdjStatus->custom_value . '">';
            $question_contents .= '<div class="col-sm-4">' . $ques_value->question_sort . '. ' . $ques_value->question_text . '</div>';
            if ($ques_value->form_field_type->field_type == 'Radio') {
                if ($ques_value->optionsGroup->option_layout == 'vertical') {
                    $br = '<br>';
                } else {
                    $br = '';
                }
                $option_name = explode(',', $ques_value->optionsGroup->option_name);
                $option_values = explode(',', $ques_value->optionsGroup->option_value);
                $question_contents .= '<div class="col-sm-6">';
                foreach ($option_name as $key => $name) {
                    $question_contents .= '<input type="radio" name="question_' . $ques_value->id . '" value="' . $option_values[$key] . '"> &nbsp;' . $name . '&nbsp;' . $br;
                }
                $question_contents .= '</div>';
            } elseif ($ques_value->form_field_type->field_type == 'Number') {
                $question_contents .=  '<div class="col-sm-6"> <input type="number" name="question_' . $ques_value->id .
                    '" value="" class="form-control"></div>';
            } elseif ($ques_value->form_field_type->field_type == 'Dropdown') {
                $option_name = explode(',', $ques_value->optionsGroup->option_name);
                $option_values = explode(',', $ques_value->optionsGroup->option_value);
                $question_contents .= '<div class="col-sm-6"><select name="question_' . $ques_value->id . '" class="form-control">';
                foreach ($option_name as $key => $name) {
                    $question_contents .= '<option value="' . $option_values[$key] . '">' . $name . '</option>';
                }
                $question_contents .= '</select></div>';
            } elseif ($ques_value->form_field_type->field_type == 'Checkbox') {
                if ($ques_value->optionsGroup->option_layout == 'vertical') {
                    $br = '<br>';
                } else {
                    $br = '';
                }
                $option_name = explode(',', $ques_value->optionsGroup->option_name);
                $option_values = explode(',', $ques_value->optionsGroup->option_value);
                $question_contents .= '<div class="col-sm-6">';
                foreach ($option_name as $key => $name) {
                    $question_contents .= '<input type="checkbox" name="question_' . $ques_value->id .
                        '_' . $ques_value->question_id . '" value="' . $option_values[$key] . '"> &nbsp;' . $name . '&nbsp;' . $br;
                }
                $question_contents .= '</div>';
            } elseif ($ques_value->form_field_type->field_type == 'Text') {
                $question_contents .=  '<div class="col-sm-6"> <input type="text" name="question_' . $ques_value->id .
                    '" value="" class="form-control"></div>';
            } elseif ($ques_value->form_field_type->field_type == 'Textarea') {
                $question_contents .=  '<div class="col-sm-6"><textarea name="question_' . $ques_value->id .
                    '" value="" class="form-control" row="2"></textarea></div>';
            } elseif ($ques_value->form_field_type->field_type == 'Date & Time') {
                $question_contents .=  '<div class="col-sm-6"><input type="date" name="question_' . $ques_value->id .
                    '" value="" class="form-control"></div>';
            } elseif ($ques_value->form_field_type->field_type == 'Upload') {
                $question_contents .=  '<div class="col-sm-6"><input type="file" name="question_' . $ques_value->id .
                    '" value="" class="form-control"></div>';
            }
            $question_contents .= '<div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right"><span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span><span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span><span class="dropdown-item add_checks"><a href="' . url("forms/skip_logic", $ques_value->id) . '" style="cursor:pointer;"><i class="fas fa-crop-alt"></i>&nbsp; Skip Logic </a></span></div></div></div></div>';
        }
        return $question_contents;
    }
    public function updateQustionsort(Request $request)
    {
        $question = Question::find($request->questionId);
        $question->question_sort  =  $request->sort_value;
        $question->save();
        $Response['data'] = 'success';
        echo json_encode($Response);
    }
    public function skip_question_on_click($id)
    {
        $options = Question::where('id', $id)->with('optionsGroup')->first();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->first();
        return view('admin::forms.skip_logic', compact('all_study_steps', 'options'));
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
            //'validation_rules' => $request->validation_rules,
        ]);
        /// question validation
        $Question_validation = [];
        if (isset($request->validation_rules) && count($request->validation_rules) > 0) {
            for ($i = 0; $i < count($request->validation_rules); $i++) {
                $id    = Str::uuid();
                $Question_validation[] = [
                    'id' => $id,
                    'question_id' => $last_id->id,
                    'validation_rule_id' => $request->validation_rules[$i],
                ];
            }
            QuestionValidation::insert($Question_validation);
        }
        /*
        if(isset($request->decision_one) && count($request->decision_one) >0){
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
        }
        */
        //Question dependencies
        if (isset($request->q_d_status) && $request->q_d_status == 'yes') {
            $id    = Str::uuid();
            $dependencies = QuestionDependency::create([
                'id' => $id,
                'question_id' => $last_id->id,
                'q_d_status' => $request->q_d_status,
                'dep_on_question_id' => $request->dep_on_question_id,
                'opertaor' => $request->opertaor,
                'custom_value' => $request->custom_value

            ]);
        }
        // Question annotation
        $id    = Str::uuid();
        $annotation = [];
        if (isset($request->terminology_id) && count($request->terminology_id) > 0) {
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
        }
        // Question Adjudication
        if (isset($request->adj_status) && $request->adj_status == 'yes') {
            $id    = Str::uuid();
            $adjStatus = QuestionAdjudicationStatus::create([
                'id' => $id,
                'question_id' => $last_id->id,
                'adj_status' => $request->adj_status,
                'decision_based_on' => $request->decision_based_on,
                'opertaor' => $request->opertaor,
                'custom_value' => $request->custom_value

            ]);
        }
        return redirect()->route('forms.index')->with('message', 'Record Added Successfully!');
    }
    public function update_questions(Request $request)
    {
        // update Question basic attribute
        $question_update = Question::where('id', $request->id)->first();
        $question_update->form_field_type_id = $request->form_field_type_id;
        $question_update->section_id = $request->section_id;
        $question_update->option_group_id = $request->option_group_id;
        $question_update->question_sort = $request->question_sort;
        $question_update->question_text = $request->question_text;
        $question_update->c_disk = $request->c_disk;
        $question_update->measurement_unit = $request->measurement_unit;
        $question_update->is_dependent = $request->field_dependent;
        $question_update->dependent_on = $request->dependent_on;
        $question_update->annotations = $request->dependent_on;
        $question_update->save();
        // update form fields
        $form_field = FormFields::where('id', $request->field_id)->first();
        $form_field->variable_name = $request->variable_name;
        $form_field->is_exportable_to_xls = $request->is_exportable_to_xls;
        $form_field->is_required = $request->is_required;
        $form_field->lower_limit = $request->lower_limit;
        $form_field->upper_limit = $request->upper_limit;
        $form_field->field_width = $request->field_width;
        $form_field->text_info = $request->question_info;
        $form_field->text_info = $request->text_info;
        $form_field->validation_rules = $request->validation_rules;
        $form_field->save();

        // Question dependency update
        if (!empty($request->dependency_id)) {
            $dependencies = QuestionDependency::where('id', $request->dependency_id)->first();
            $dependencies->q_d_status = $request->q_d_status;
            $dependencies->dep_on_question_id = $request->dep_on_question_id;
            $dependencies->opertaor = $request->opertaor;
            $dependencies->custom_value = $request->custom_value;
            $dependencies->save();
        }
        // update adjudication
        if (!empty($request->adj_id)) {
            $adjStatus = QuestionAdjudicationStatus::where('id', $request->adj_id)->first();
            $adjStatus->adj_status = $request->adj_status;
            $adjStatus->decision_based_on = $request->decision_based_on;
            $adjStatus->opertaor = $request->opertaor;
            $adjStatus->custom_value = $request->custom_value;
            $adjStatus->save();
        } else {
            if (isset($request->adj_status) && $request->adj_status == 'yes') {
                $id    = Str::uuid();
                $adjStatus = QuestionAdjudicationStatus::create([
                    'id' => $id,
                    'question_id' => $request->id,
                    'adj_status' => $request->adj_status,
                    'decision_based_on' => $request->decision_based_on,
                    'opertaor' => $request->opertaor,
                    'custom_value' => $request->custom_value

                ]);
            }
        }
        return redirect()->route('forms.index')->with('message', 'Record Updated Successfully!');
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
    public function destroy($id = '')
    {
    }
    function deleteQuestion($id)
    {
        Question::where('id', $id)->delete();
        FormFields::where('question_id', $id)->delete();
        QuestionValidation::where('question_id', $id)->delete();
        $Response['data'] = 'success';
        echo json_encode($Response);
    }
}
