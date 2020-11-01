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
use Illuminate\Support\Facades\DB;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class FormController extends Controller
{
    use ReplicatePhaseStructure;
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
    public function skip_question_on_click($id)
    {
        $options = Question::where('id', $id)->with('optionsGroup', 'skiplogic')->first();
        return view('admin::forms.skip_logic', compact('options'));
    }
    public function getSteps_toskip()
    {
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->first();
        $step_contents_active = '<div class="col-12 col-sm-6 mt-3 current_div_ac">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Activate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>';
        foreach ($all_study_steps->studySteps as $key => $value) {
            $step_contents_active .= '
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                <tbody>
                                    <tr>
                                        <td class="step_id" style="display: none;">' . $value->step_id . '</td>
                                        <td style="text-align: center;width: 15%">
                                          <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="activate_checks(\'' . $value->step_id . '\',\'sections_list_\');" data-target=".row-' . $value->step_id . '-ac" style="font-size: 20px; color: #1e3d73;"></i>
                                          </div>
                                        </td>
                                        <td colspan="5"> <input type="checkbox" name="activate_forms[' . $key . '}}][]" value="' . $value->step_id . '"> &nbsp;&nbsp;' . $value->step_name . '</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card collapse row-' . $value->step_id . '-ac sections_list_' . $value->step_id . '">
                </div>';

        }
        $step_contents_active .= '</div>';
        $step_contents_deactive = '<div class="col-12 col-sm-6 mt-3 current_div_de">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Activate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>';
        foreach ($all_study_steps->studySteps as $key => $value) {
            $step_contents_deactive .= '<div class="card">
                                <div class="card-body" style="padding: 0;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                        <tbody>
                                            <tr>
                                                <td class="step_id" style="display: none;">' . $value->step_id . '</td>
                                                <td style="text-align: center;width: 15%">
                                                  <div class="btn-group btn-group-sm" role="group">
                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-' . $value->step_id . '-de" onclick="deactivate_checks(\'' . $value->step_id . '\',\'de_sections_list_\');" style="font-size: 20px; color: #1e3d73;"></i>
                                                  </div>
                                                </td>
                                                <td colspan="5"><input type="checkbox" name="deactivate_forms[' . $key . '][]" value="' . $value->step_id . '"> &nbsp;&nbsp; ' . $value->step_name . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
              <div class="card collapse row-' . $value->step_id . '-de de_sections_list_' . $value->step_id . '">

                        </div>';
        }
        $step_contents_deactive .= '</div>';
        $step_contents = $step_contents_active . $step_contents_deactive;
        return $step_contents;
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
        dd($request->all());
        $skip_ques = [];
        if (isset($request->option_title) && count($request->option_title) > 0) {
            for ($i = 0; $i < count($request->option_title); $i++) {
                $skip_ques = [
                    'id' => Str::uuid(),
                    'question_id' => $request->question_id,
                    'option_title' => (isset($request->option_title[$i]) && $request->option_title[$i] != '') ? $request->option_title[$i] : '',
                    'option_value' => (isset($request->option_value[$i]) && $request->option_value[$i] != '') ? $request->option_value[$i] : '',
                    'activate_forms' => (isset($request->activate_forms[$i]) && $request->activate_forms[$i] != '') ? implode(',', $request->activate_forms[$i]) : '',
                    'activate_sections' => (isset($request->activate_sections[$i]) && $request->activate_sections[$i] != '') ? implode(',', $request->activate_sections[$i]) : '',
                    'activate_questions' => (isset($request->activate_questions[$i]) && $request->activate_questions[$i] != '') ? implode(',', $request->activate_questions[$i]) : '',
                    'deactivate_forms' => (isset($request->deactivate_forms[$i]) && $request->deactivate_forms[$i] != '') ? implode(',', $request->deactivate_forms[$i]) : '',
                    'deactivate_sections' => (isset($request->deactivate_sections[$i]) && $request->deactivate_sections[$i] != '') ? implode(',', $request->deactivate_sections[$i]) : '',
                    'deactivate_questions' => (isset($request->deactivate_questions[$i]) && $request->deactivate_questions[$i] != '') ? implode(',', $request->deactivate_questions[$i]) : ''
                ];
                skipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('forms.skipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
    // add question check end
    public function get_allQuestions($id = '')
    {
        $questions = Question::with('formFields', 'form_field_type', 'optionsGroup', 'questionDependency', 'questionAdjudicationStatus')
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
            <input type="hidden" class="upper_limit" value="' . $ques_value->formFields->upper_limit . '">
            <input type="hidden" class="decimal_point" value="' . $ques_value->formFields->decimal_point . '">';
            $question_contents .= '<input type="hidden" class="question_type" value="' . $ques_value->form_field_type->field_type . '">
            <input type="hidden" class="dependency_id" value="' . $ques_value->questionDependency->id . '">
            <input type="hidden" class="dependency_status" value="' . $ques_value->questionDependency->q_d_status . '">
            <input type="hidden" class="dependency_operator" value="' . $ques_value->questionDependency->opertaor . '">
            <input type="hidden" class="dependency_question" value="' . $ques_value->questionDependency->dep_on_question_id . '">
            <input type="hidden" class="dependency_custom_value" value="' . $ques_value->questionDependency->custom_value . '">
            <input type="hidden" class="adj_id" value="' . $ques_value->questionAdjudicationStatus->id . '">
            <input type="hidden" class="adj_status" value="' . $ques_value->questionAdjudicationStatus->adj_status . '">
            <input type="hidden" class="adj_decision_based" value="' . $ques_value->questionAdjudicationStatus->decision_based_on . '">
            <input type="hidden" class="adj_operator" value="' . $ques_value->questionAdjudicationStatus->opertaor . '">
            <input type="hidden" class="adj_custom_value" value="' . $ques_value->questionAdjudicationStatus->custom_value . '">';
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
            } elseif ($ques_value->form_field_type->field_type == 'Certification') {

                $question_contents .= '<div class="col-sm-6"><select name="question_list" class="form-control">';

                if ($ques_value->certification_type == 'devices') {
                    $list = DB::connection('mysql2')->table('certify_device')->select('certify_device.*', DB::Raw('GROUP_CONCAT(trans_no SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(c_id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'), DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'))->groupBy('certify_device.device_categ')->get();
                    foreach ($list as $key => $item) {
                        $question_contents .= '<option value="">' . $item->device_sn . ' && ' . $item->device_model . ' && ' . $item->device_categ . '</option>';
                    }
                } else {
                    $list = DB::connection('mysql2')->table('photographer_data')->select('photographer_data.*', DB::Raw('CONCAT(first_name, " ", last_name) as photographer_name'), DB::Raw('GROUP_CONCAT(transmission_number SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'), DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'))->groupBy('photographer_name')->get();
                    foreach ($list as $key => $item) {
                        $name = $item->first_name . ' ' . $item->last_name;
                        $question_contents .= '<option value="">' . $name . ' && ' . $item->imaging_modality_req . '</option>';
                    }
                }
                $question_contents .= '</select></div>';
            } elseif ($ques_value->form_field_type->field_type == 'Description') {
                $question_contents .= '<div class="col-sm-6">' . $ques_value->formFields->text_info . '</div>';
            }
            $question_contents .= '<div class="col-sm-2"><div class="d-flex mt-3 mt-md-0 ml-auto float-right"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right">';
            if ($ques_value->form_field_type->field_type == 'Certification') {
            } elseif ($ques_value->form_field_type->field_type == 'Description') {
                $question_contents .= '<span class="dropdown-item edit_desc"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span>';
            } else {
                $question_contents .= '<span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span>';
            }
            $question_contents .= '<span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span>';
            if ($ques_value->form_field_type->field_type == 'Radio') {
                $question_contents .= '<span class="dropdown-item add_checks"><a href="' . url("forms/skip_logic", $ques_value->id) . '" style="cursor:pointer;"><i class="fas fa-crop-alt"></i>&nbsp; Skip Logic </a></span>';
            } else {
            }
            $question_contents .= '</div></div></div></div>';
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
    // check variable name if exist in same form
    public function check_variable_name(Request $request){
        $section_ids = Section::where('phase_steps_id', $request->step_id)->pluck('id')->toArray();
        $question_ids = Question::whereIn('section_id', $section_ids)->pluck('id')->toArray();
        $formFields = FormFields::whereIn('question_id', $question_ids)->where('variable_name',$request->name)->get();
        if(count($formFields) < 1){
            echo 'no_field_found';
        }else{
            echo 'field_found';
        }
    }
    public function add_questions(Request $request)
    {
        $id    = Str::uuid();
        Question::create([
            'id' => $id,
            'old_id'    => $id,
            'form_field_type_id' => $request->form_field_type_id,
            'section_id' => $request->section_id,
            'option_group_id' => $request->option_group_id,
            'question_sort' => $request->question_sort,
            'question_text' => $request->question_text,
            'c_disk' => $request->c_disk,
            'measurement_unit' => $request->measurement_unit,
            'is_dependent' => $request->field_dependent,
            'dependent_on' => $request->dependent_on,
            'annotations' => $request->dependent_on,
            'certification_type' => $request->certification_type
        ]);
        $questionObj = Question::find($id);

        $this->createQuestionFormField($request, $questionObj);
        $this->createQuestionDataValidations($request, $questionObj);
        $this->createQuestionDependencies($request, $questionObj);
        $this->createQuestionAnnotations($request, $questionObj);
        $this->createQuestionAdjudicationStatus($request, $questionObj);

        /*
         * Replicate Question in replicated visits
         */
        $this->addQuestionToReplicatedVisits($questionObj);

        return redirect()->route('forms.index')->with('message', 'Record Added Successfully!');
    }
    public function update_questions(Request $request)
    {
        // update Question basic attribute
        $questionObj = Question::where('id', $request->id)->first();
        $questionObj->form_field_type_id = $request->form_field_type_id;
        $questionObj->section_id = $request->section_id;
        $questionObj->option_group_id = $request->option_group_id;
        $questionObj->question_sort = $request->question_sort;
        $questionObj->question_text = $request->question_text;
        $questionObj->c_disk = $request->c_disk;
        $questionObj->measurement_unit = $request->measurement_unit;
        $questionObj->is_dependent = $request->field_dependent;
        $questionObj->dependent_on = $request->dependent_on;
        $questionObj->annotations = $request->dependent_on;
        $questionObj->save();

        $this->updateQuestionToReplicatedVisits($questionObj);

        /**************************************************/
        $this->updateFormField($request);
        $this->updateQuestionValidation($request, $questionObj);
        $this->updateQuestionDependency($request, $questionObj);
        $this->updateQuestionAdjudicationStatus($request, $questionObj);

        return redirect()->route('forms.index')->with('message', 'Record Updated Successfully!');
    }

    private function createQuestionFormField($request, $questionObj)
    {
        $id    = Str::uuid();
        $form_field = FormFields::create([
            'id' => $id,
            'question_id' => $questionObj->id,
            'variable_name' => $request->variable_name,
            'is_exportable_to_xls' => $request->is_exportable_to_xls,
            'is_required' => $request->is_required,
            'lower_limit' => $request->lower_limit,
            'upper_limit' => $request->upper_limit,
            'decimal_point' => $request->decimal_point,
            'field_width' => $request->field_width,
            'question_info' => $request->question_info,
            'text_info' => $request->text_info,
            //'validation_rules' => $request->validation_rules,
        ]);
    }

    private function updateFormField($request)
    {
        // update form fields
        $form_field = FormFields::where('id', $request->field_id)->first();
        $form_field->variable_name = $request->variable_name;
        $form_field->is_exportable_to_xls = $request->is_exportable_to_xls;
        $form_field->is_required = $request->is_required;
        $form_field->lower_limit = $request->lower_limit;
        $form_field->upper_limit = $request->upper_limit;
        $form_field->decimal_point = $request->decimal_point;
        $form_field->field_width = $request->field_width;
        $form_field->text_info = $request->question_info;
        $form_field->text_info = $request->text_info;
        $form_field->validation_rules = $request->validation_rules;
        $form_field->save();

        $this->updateQuestionFormFieldToReplicatedVisits($form_field);
    }
    private function createQuestionAdjudicationStatus($request, $questionObj)
    {
        if (isset($request->adj_status) && $request->adj_status == 'yes') {
            $id    = Str::uuid();
            $adjStatus = QuestionAdjudicationStatus::create([
                'id' => $id,
                'question_id' => $questionObj->id,
                'adj_status' => $request->adj_status,
                'decision_based_on' => $request->decision_based_on,
                'opertaor' => $request->opertaor,
                'custom_value' => $request->custom_value

            ]);
        }
    }

    private function updateQuestionAdjudicationStatus($request, $questionObj)
    {
        // update adjudication
        if (!empty($request->adj_id)) {
            $adjStatus = QuestionAdjudicationStatus::where('id', $request->adj_id)->first();
            $adjStatus->adj_status = $request->adj_status;
            $adjStatus->decision_based_on = $request->decision_based_on;
            $adjStatus->opertaor = $request->opertaor;
            $adjStatus->custom_value = $request->custom_value;
            $adjStatus->save();

            $this->updateQuestionAdjudicationStatusesToReplicatedVisits($adjStatus);
        } else {
            $this->createQuestionAdjudicationStatus($request, $questionObj);
        }
    }

    private function createQuestionAnnotations($request, $questionObj)
    {
        $id    = Str::uuid();
        $annotation = [];
        if (isset($request->terminology_id) && count($request->terminology_id) > 0) {
            for ($i = 0; $i < count($request->terminology_id); $i++) {
                $annotation[] = [
                    'id' => $id,
                    'question_id' => $questionObj->id,
                    'annotation_id' => $request->terminology_id[$i],
                    'value' => $request->value[$i],
                    'description' => $request->description[$i]
                ];
            }
            AnnotationDescription::insert($annotation);
        }
    }

    private function createQuestionDependencies($request, $questionObj)
    {
        if (isset($request->q_d_status) && $request->q_d_status == 'yes') {
            $id    = Str::uuid();
            $dependencies = QuestionDependency::create([
                'id' => $id,
                'question_id' => $questionObj->id,
                'q_d_status' => $request->q_d_status,
                'dep_on_question_id' => $request->dep_on_question_id,
                'opertaor' => $request->opertaor,
                'custom_value' => $request->custom_value

            ]);
        }
    }

    private function updateQuestionDependency($request, $questionObj)
    {
        // Question dependency update
        if (!empty($request->dependency_id)) {
            $dependencies = QuestionDependency::where('id', $request->dependency_id)->first();
            $dependencies->q_d_status = $request->q_d_status;
            $dependencies->dep_on_question_id = $request->dep_on_question_id;
            $dependencies->opertaor = $request->opertaor;
            $dependencies->custom_value = $request->custom_value;
            $dependencies->save();
            $this->updateQuestionDependenciesToReplicatedVisits($dependencies);
        } else {
            $this->createQuestionDependencies($request, $questionObj);
        }
    }

    private function createQuestionDatavalidations($request, $questionObj)
    {
        $validationRuleIdsArray = array_unique($request->validation_rules);

        if (count($validationRuleIdsArray) > 0) {
            foreach ($validationRuleIdsArray as $validationRuleId) {
                $id    = Str::uuid();
                $validation = [
                    'id' => $id,
                    'question_id' => $questionObj->id,
                    'validation_rule_id' => $validationRuleId,
                ];
                QuestionValidation::insert($validation);
            }
        }
        /*
        if (isset($request->decision_one) && count($request->decision_one) > 0) {
            for ($i = 0; $i < count($request->decision_one); $i++) {
                $id    = Str::uuid();
                $Question_validation[] = [
                    'id' => $id,
                    'question_id' => $questionObj->id,
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
    }

    private function updateQuestionValidation($request, $questionObj)
    {
        $this->deleteQuestionValidations($questionObj->id);
        $this->createQuestionDatavalidations($request, $questionObj);
        $this->updateQuestionValidationToReplicatedVisits($questionObj->id);
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
    function deleteQuestion($questionId)
    {
        $this->deleteQuestionAndItsRelatedValues($questionId);
        $Response['data'] = 'success';
        echo json_encode($Response);
    }
}
