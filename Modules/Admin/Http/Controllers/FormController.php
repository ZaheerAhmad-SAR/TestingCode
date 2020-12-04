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
use Modules\Admin\Entities\SkipLogic;
use Illuminate\Support\Facades\DB;
use Modules\FormSubmission\Entities\FormVersion;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;
use Session;

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
        // $parentArray = $step = [];
        $step_id_sess = session('filter_step');
        $options = '<option value="">---Select Step---</option>';
        foreach ($PhaseSteps as $phaseStep) {
            if ($phaseStep->step_id == $step_id_sess) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            // $step['step_id'] = $phaseStep->step_id;
            // $step['form_type'] = $phaseStep->formType->form_type;
            // $step['step_name'] = $phaseStep->step_name;
            // $parentArray[] = $step;
            $options .= '<option value ="' . $phaseStep->step_id . '" ' . $selected . '>' . $phaseStep->formType->form_type . '-' . $phaseStep->step_name . '</option>';
        }
        // $stepsData['data'] = $parentArray;
        // echo json_encode($stepsData);
        return $options;
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
        $section_contents .= '<div id="accordion"><div id="formVersionDiv"></div><br>';
        foreach ($section as $key => $value) {
            $show = ($key == 0) ? 'show' : '';
            $section_contents .= '<div class="card"><div class="card-header"><a class="card-link" data-toggle="collapse" href="#collapse_' . $value->id . '">' . $value->sort_number . '&nbsp;&nbsp;&nbsp;&nbsp;' . $value->name . '</a></div><div id="collapse_' . $value->id . '" class="collapse ' . $show . '" data-parent="#accordion"><div class="card-body questions_' . $value->id . '">';
            $section_contents .= $this->get_allQuestions($value->id);
            $section_contents .= '</div></div></div>';
        }
        $section_contents .= '</div>';
        return Response($section_contents);
    }

    public function getStepVersion($id)
    {
        $formVersion = PhaseSteps::getFormVersion($id);
        echo $formVersion;
    }

    public function isStepActive(Request $request)
    {
        $step = PhaseSteps::find($request->step_id);
        echo $step->is_active;
    }

    public function isThisStepHasData(Request $request)
    {
        echo PhaseSteps::isThisStepHasData($request->stepId);
    }

    // add question check start
    // Question activate and deactivate

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
            <input type="hidden" class="section_id" value="' . $id . '">
            <input type="hidden" class="first_question_id" value="' . $ques_value->first_question_id . '">
            <input type="hidden" class="operator_calculate" value="' . $ques_value->operator_calculate . '">
            <input type="hidden" class="second_question_id" value="' . $ques_value->second_question_id . '">
            <input type="hidden" class="make_decision" value="' . $ques_value->make_decision . '">
            <input type="hidden" class="certification_type" value="' . $ques_value->certification_type . '">
            <input type="hidden" class="calculate_with_costum_val" value="' . $ques_value->calculate_with_costum_val . '">';
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
            } elseif ($ques_value->form_field_type->field_type == 'Calculated') {
                $question_contents .= '<div class="col-sm-6"><input type="text" name="question_' . $ques_value->id .
                    '" value="" class="form-control" disabled style="background:lightgray"></div>';
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
                $question_contents .= '<div class="col-sm-6">' . html_entity_decode($ques_value->formFields->text_info) . '</div>';
            }
            //$eye = '<span class="d-flex mt-3 mt-md-0 ml-auto float-right"><i type="button" class="far fa-eye" data-toggle="tooltip" data-placement="top" title="' . html_entity_decode($ques_value->formFields->text_info) . '"  style="margin-top:6px;"></i></span>';
            $eye = '';
            $question_contents .= '<div class="col-sm-2">' . $eye . '<div class="d-flex mt-3 mt-md-0 ml-auto" style="width:50%;display:inline-block !important;"><span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 6px;"></i></span><div class="dropdown-menu p-0 m-0 dropdown-menu-right">';
            if ($ques_value->form_field_type->field_type == 'Certification') {
                $question_contents .= '<span class="dropdown-item edit_certify"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span>';
            } elseif ($ques_value->form_field_type->field_type == 'Description') {
                $question_contents .= '<span class="dropdown-item edit_desc"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span>';
            } elseif ($ques_value->form_field_type->field_type == 'Calculated') {
                $question_contents .= '<span class="dropdown-item edit_calculated_field"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span>';
            } else {
                $question_contents .= '<span class="dropdown-item Edit_ques"><a href="#"><i class="far fa-edit"></i>&nbsp; Edit </a></span>';
            }
            $question_contents .= '<span class="dropdown-item delete_ques"><a href="#"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span><span class="dropdown-item change_ques_sort"><a href="#"><i class="fas fa-arrows-alt"></i>&nbsp; Change Sort # </a></span>';
            if ($ques_value->form_field_type->field_type == 'Radio') {
                $question_contents .= '<span class="dropdown-item add_checks"><a href="' . url("skiplogic/skip_logic", $ques_value->id) . '" style="cursor:pointer;"><i class="fas fa-crop-alt"></i>&nbsp; Skip Logic </a></span>';
            } elseif ($ques_value->form_field_type->field_type == 'Number') {
                $question_contents .= '<span class="dropdown-item add_checks"><a href="' . url("skipNumber/num_skip_logic", $ques_value->id) . '" style="cursor:pointer;"><i class="fas fa-crop-alt"></i>&nbsp; Skip Logic </a></span>';
            } elseif ($ques_value->form_field_type->field_type == 'Text') {
                $question_contents .= '<span class="dropdown-item add_checks"><a href="' . url("skiplogic/text_skip_logic", $ques_value->id) . '" style="cursor:pointer;"><i class="fas fa-crop-alt"></i>&nbsp; Skip Logic </a></span>';
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
       $questions = Question::where('section_id', $question->section_id)->get();
       foreach ($questions as $ques_value) {
            if($ques_value->question_sort >= $request->sort_value){

            }
        }
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
    public function check_variable_name(Request $request)
    {
        $section_ids = Section::where('phase_steps_id', $request->step_id)->pluck('id')->toArray();
        $question_ids = Question::whereIn('section_id', $section_ids)->pluck('id')->toArray();
        $formFields = FormFields::whereIn('question_id', $question_ids)->where('variable_name', $request->name)->get();
        if (count($formFields) < 1) {
            echo 'no_field_found';
        } else {
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
            'first_question_id' => $request->first_question_id,
            'operator_calculate' => $request->operator_calculate,
            'make_decision' => $request->make_decision,
            'calculate_with_costum_val' => $request->calculate_with_costum_val,
            'second_question_id' => $request->second_question_id,
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
        $this->addQuestionToReplicatedVisits($questionObj, true);

        return redirect()->route('forms.index')->with('message', 'Record Added Successfully!');
    }
    public function update_questions(Request $request)
    {
        // update Question basic attribute
        $questionObj = Question::where('id', $request->id)->first();
        $questionObj->form_field_type_id = $request->form_field_type_id;
        $questionObj->section_id = $request->section_id;
        $questionObj->option_group_id = $request->option_group_id;
        $questionObj->first_question_id = $request->first_question_id;
        $questionObj->operator_calculate = $request->operator_calculate;
        $questionObj->make_decision = $request->make_decision;
        $questionObj->calculate_with_costum_val = $request->calculate_with_costum_val;
        $questionObj->second_question_id = $request->second_question_id;
        $questionObj->question_sort = $request->question_sort;
        $questionObj->question_text = $request->question_text;
        $questionObj->c_disk = $request->c_disk;
        $questionObj->measurement_unit = $request->measurement_unit;
        $questionObj->is_dependent = $request->field_dependent;
        $questionObj->dependent_on = $request->dependent_on;
        $questionObj->annotations = $request->dependent_on;
        $questionObj->certification_type = $request->certification_type;
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
            'old_question_id' => $questionObj->id,
            'variable_name' => $request->variable_name,
            'is_exportable_to_xls' => $request->is_exportable_to_xls,
            'is_required' => $request->is_required,
            'lower_limit' => $request->lower_limit,
            'upper_limit' => $request->upper_limit,
            'decimal_point' => $request->decimal_point,
            'field_width' => $request->field_width,
            'question_info' => $request->question_info,
            'text_info' => htmlentities($request->text_info),

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
        //$form_field->question_info = $request->question_info;
        $form_field->text_info = htmlentities($request->text_info);
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
            $data = [
                'id' => $id,
                'question_id' => $questionObj->id,
                'q_d_status' => $request->q_d_status,
                'dep_on_question_id' => $request->dep_on_question_id,
                'opertaor' => $request->dependency_opertaor,
                'custom_value' => $request->dependency_custom_value
            ];
            //dd($data);
            $dependencies = QuestionDependency::create($data);
        }
    }

    private function updateQuestionDependency($request, $questionObj)
    {
        // Question dependency update
        if (!empty($request->dependency_id) && $request->dependency_id != 'no-id-123') {
            $dependencies = QuestionDependency::where('id', $request->dependency_id)->first();
            $dependencies->q_d_status = $request->q_d_status;
            $dependencies->dep_on_question_id = $request->dep_on_question_id;
            $dependencies->opertaor = $request->dependency_opertaor;
            $dependencies->custom_value = $request->dependency_custom_value;
            $dependencies->save();
            $this->updateQuestionDependenciesToReplicatedVisits($dependencies);
        } else {
            $this->createQuestionDependencies($request, $questionObj);
        }
    }

    private function createQuestionDatavalidations($request, $questionObj)
    {
        //$validationRuleIdsArray = array_unique((array)$request->validation_rules);

        if (count((array)$request->validation_rules) > 0) {
            for ($counter = 0; $counter < count($request->validation_rules); $counter++) {
                $id    = Str::uuid();
                $validation = [
                    'id' => $id,
                    'question_id' => $questionObj->id,
                    'validation_rule_id' => $request->validation_rules[$counter],
                    'parameter_1' => $request->parameter_1[$counter],
                    'parameter_2' => $request->parameter_2[$counter],
                    'message_type' => $request->message_type[$counter],
                    'message' => $request->message[$counter],
                    'sort_order' => $request->sort_order[$counter],
                ];
                QuestionValidation::insert($validation);
            }
        }
    }

    private function updateQuestionValidation($request, $questionObj, $isReplicating = true)
    {
        $this->deleteQuestionValidations($questionObj->id);
        $this->createQuestionDatavalidations($request, $questionObj);
        $this->updateQuestionValidationToReplicatedVisits($questionObj->id, $isReplicating);
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


    function deleteQuestion($questionId)
    {
        $this->deleteQuestionAndItsRelatedValues($questionId);
        $Response['data'] = 'success';
        echo json_encode($Response);
    }
    public function get_questions_calculation(Request $request, $id)
    {
        $section_ids = Section::where('phase_steps_id', $request->step_id)->pluck('id')->toArray();
        $questions = Question::whereIn('section_id', $section_ids)->where('form_field_type_id', 1)->with('formFields')->get();
        $options = '<option value="">select question for auto calculation</option>';
        foreach ($questions as $key => $value) {
            $options .= '<option value="' . $value->id . '">' . $value->question_text . '</option>';
        }
        return $options;
    }
    public function create_filter_session(Request $request)
    {
        // Make new session
        $old_session_filter_phase = session('filter_phase');
        $old_session_filter_step = session('filter_step');
        unset($old_session_filter_phase);
        unset($old_session_filter_step);
        session(['filter_phase' => $request->phase_id, 'filter_step' => $request->step_id]);
        // session()->put('filter_phase', $request->phase_id);
        // session()->put('filter_step', $request->step_id);
    }
}
