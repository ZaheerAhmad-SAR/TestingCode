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
use Modules\Admin\Entities\QuestionOption;
use Modules\Admin\Entities\CohortSkipLogic;
use Modules\Admin\Entities\CohortSkipLogicOption;
use Modules\Admin\Entities\DiseaseCohort;
use Illuminate\Support\Facades\DB;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class SkipLogicController extends Controller
{
    public function skip_question_on_click($id)
    {
        $options = Question::where('id', $id)->with('optionsGroup', 'skiplogic')->first();
        return view('admin::forms.skip_logic', compact('options'));
    }
    public function skip_question_on_text($id)
    {
        $num_values = Question::where('id', $id)->with('skiplogic')->first();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->get();
        return view('admin::forms.skip_question_text', compact('num_values','all_study_steps'));
    }
    public function skip_logic_cohort($id){
        $disease_cohorts = DiseaseCohort::where('study_id', '=', $id)->get();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->get();
        return view('admin::studies.skip_logic_cohort', compact('all_study_steps','disease_cohorts'));
    }
  
    public function git_steps_for_checks_deactivate_cohort(Request $request){
        $activate_forms_array = [];
        $deactivate_forms_array = [];
        $function_string_ac = '';
        $function_string_de = '';
        //$view = return view('admin::forms.skip_sections');
        $where = array(
            "question_id" =>$request->question_id,
            "option_title" =>$request->option_title,
            "option_value" =>$request->option_value
        );
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $activate_forms_array = explode(',', $if_exists_record->activate_forms);
            $deactivate_forms_array = explode(',', $if_exists_record->deactivate_forms);
        }
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->first();
       
        $step_contents_deactive = '<div class="col-12 col-sm-12 mt-3 current_div_de">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Deactivate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>';

        foreach ($all_study_steps->studySteps as $key => $value) {
            if(in_array($value->step_id, $deactivate_forms_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $step_contents_deactive .= '<div class="card">
                                <div class="card-body" style="padding: 0;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;background-color: #1E3D73;color: white;">
                                        <tbody>
                                            <tr>
                                                <td class="step_id" style="display: none;">' . $value->step_id . '</td>
                                                <td style="text-align: center;width: 15%">
                                                  <div class="btn-group btn-group-sm" role="group">
                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-'.$value->step_id.'-de-'.$request->index.'" onclick="deactivate_checks(\'' . $value->step_id . '\',\'de_sections_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" style="font-size: 20px;"></i>
                                                  </div>
                                                </td>
                                                <td colspan="5"><input type="checkbox" name="deactivate_forms[' .$request->index. '][]" value="'.$value->step_id.'" '.$checked.' class="deactivate_step_'.$value->step_id.'_'.$request->index.'" onclick="disabled_opposite(\'' . $value->step_id . '\',\'activate_step_\',\''.$request->index.'\',\'deactivate_step_\')"> &nbsp;&nbsp; '.$value->step_name.'('.$value->formType->form_type.'-'.$value->modility->modility_name.')</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

              <div class="card collapse row-'.$value->step_id.'-de-'.$request->index.' de_sections_list_'.$value->step_id.'_'.$request->index.'">';

            $step_contents_deactive .= '</div>';
            $function_string_de .='disabled_opposite(\'' . $value->step_id . '\',\'activate_step_\',\''.$request->index.'\',\'deactivate_step_\');';
            $function_string_de .= 'deactivate_checks(\'' . $value->step_id . '\',\'de_sections_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');';

        }

        $step_contents_deactive .= '</div>';
        // $step_contents =  $step_contents_deactive;
        $function_string = $function_string_de . $function_string_ac;
        $content_array = array(
            'html_str' => $step_contents_deactive,
            'function_str' => $function_string
        );
        return json_encode($content_array);
    }
   
    public function add_skipLogic(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if (isset($request->option_value) && count($request->option_value) > 0) {
            $where = array('question_id' =>$request->question_id);
            $remove_checks_if_already_exists = skipLogic::where($where)->delete();
            $remove_options_checks_if_exists = QuestionOption::where($where)->delete();
            for ($i = 0; $i < count($request->option_value); $i++) {
                $skiplogic_id = Str::uuid();
                $skip_ques = [
                    'id' => $skiplogic_id,
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
                // Deactivate Questions options
                if(isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0){
                  for($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                    $op_content = explode('<<=!=>>', $request->deactivate_options[$i][$j]);
                    $skip_options = [
                        'id' => Str::uuid(),
                        'skip_logic_id' => $skiplogic_id,
                        'question_id' => $request->question_id,
                        'value' => $op_content[0],
                        'type' => 'deactivate',
                        'option_question_id' => $op_content[1],
                        'option_depend_on_question_type' => 'radio'
                        ];
                    QuestionOption::insert($skip_options);
                  }
                }
                // Activate Questions options
                if(isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0){
                   for($j = 0; $j < count($request->activate_options[$i]); $j++) {
                    $op_content = explode('<<=!=>>', $request->activate_options[$i][$j]);
                    $skip_options = [
                        'id' => Str::uuid(),
                        'skip_logic_id' => $skiplogic_id,
                        'question_id' => $request->question_id,
                        'value' => $op_content[0],
                        'type' => 'activate',
                        'option_question_id' => $op_content[1],
                        'option_depend_on_question_type' => 'radio'
                        ];
                    QuestionOption::insert($skip_options);
                  }
                }
                skipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('skiplogic.skipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
    public function add_skipLogic_cohort_based(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if (isset($request->cohort_id) && count($request->cohort_id) > 0){
            $where = array('study_id' =>$request->study_id);
            $remove_checks_if_already_exists = CohortSkipLogic::where($where)->delete();
            $remove_options_checks_if_exists = CohortSkipLogicOption::where($where)->delete();
            for ($i = 0; $i < count($request->cohort_id); $i++) {
                $cohort_skiplogic_id = Str::uuid();
                $skip_ques = [
                    'id' => $cohort_skiplogic_id,
                    'study_id' => $request->study_id,
                    'cohort_name' => (isset($request->cohort_name[$i]) && $request->cohort_name[$i] != '') ? $request->cohort_name[$i] : '',
                    'cohort_id' => (isset($request->cohort_id[$i]) && $request->cohort_id[$i] != '') ? $request->cohort_id[$i] : '',
                    'deactivate_forms' => (isset($request->deactivate_forms[$i]) && $request->deactivate_forms[$i] != '') ? implode(',', $request->deactivate_forms[$i]) : '',
                    'deactivate_sections' => (isset($request->deactivate_sections[$i]) && $request->deactivate_sections[$i] != '') ? implode(',', $request->deactivate_sections[$i]) : '',
                    'deactivate_questions' => (isset($request->deactivate_questions[$i]) && $request->deactivate_questions[$i] != '') ? implode(',', $request->deactivate_questions[$i]) : ''
                ];
                // Deactivate Questions options
                if(isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0){
                  for($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                    $op_content = explode('_', $request->deactivate_options[$i][$j]);
                    $skip_options = [
                        'id' => Str::uuid(),
                        'cohort_skiplogic_id' => $cohort_skiplogic_id,
                        'study_id' => $request->study_id,
                        'value' => $op_content[0],
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2],
                        ];
                    CohortSkipLogicOption::insert($skip_options);
                  }
                }
                // Activate Questions options
                if(isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0){
                   for($j = 0; $j < count($request->activate_options[$i]); $j++) {
                    $op_content = explode('_', $request->activate_options[$i][$j]);
                    $skip_options = [
                        'id' => Str::uuid(),
                        'cohort_skiplogic_id' => $cohort_skiplogic_id,
                        'study_id' => $request->study_id,
                        'value' => $op_content[0],
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2],
                        ];
                    CohortSkipLogicOption::insert($skip_options);
                  }
                }
                CohortSkipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('skiplogic.skiponcohort', $request->study_id)->with('message', 'Checks Applied Successfully!');
    }
}
