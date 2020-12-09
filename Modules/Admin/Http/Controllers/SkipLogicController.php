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
    public function getSteps_toskip(Request $request)
    {
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
            if(in_array($value->step_id, $activate_forms_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $step_contents_active .= '
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;background-color: #1E3D73;color: white;">
                                <tbody>
                                    <tr>
                                        <td class="step_id" style="display: none;">' . $value->step_id . '</td>
                                        <td style="text-align: center;width: 15%">
                                          <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="activate_checks(\'' . $value->step_id . '\',\'sections_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-' .$value->step_id.'-ac-'.$request->index.'" style="font-size: 20px;"></i>
                                          </div>
                                        </td>
                                        <td colspan="5"> <input type="checkbox" name="activate_forms[' .$request->index. '][]" value="' . $value->step_id . '" '.$checked.' class="activate_step_'.$value->step_id.'_'.$request->index.'" onclick="disabled_opposite(\'' . $value->step_id . '\',\'deactivate_step_\',\''.$request->index.'\',\'activate_step_\');"> &nbsp;&nbsp;'.$value->step_name.'('.$value->formType->form_type.'-'.$value->modility->modility_name.')</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card collapse row-'.$value->step_id.'-ac-'.$request->index.' sections_list_'.$value->step_id.'_'.$request->index.'">
                </div>';
            $function_string_ac .='disabled_opposite(\'' . $value->step_id . '\',\'deactivate_step_\',\''.$request->index.'\',\'activate_step_\');';
            $function_string_ac .='activate_checks(\'' . $value->step_id . '\',\'sections_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');';
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
        $step_contents = $step_contents_active . $step_contents_deactive;
        $function_string = $function_string_de . $function_string_ac;
        $content_array = array(
            'html_str' => $step_contents,
            'function_str' => $function_string
        );
        return json_encode($content_array);
    }
    public function sections_skip_logic(Request $request,$id)
    {
        $activate_sections_array = [];
        $function_string = '';
        if($request->option_value != 'q_type_num'){
           $where = array(
                "question_id" =>$request->question_id,
                "option_title" =>$request->option_title,
                "option_value" =>$request->option_value
            );
        }else{
           $where = array(
                "id" =>$request->question_id,
            );
        }
        $if_exists_record = skipLogic::where($where)->first();
        if(null !== $if_exists_record){
            $activate_sections_array = explode(',', $if_exists_record->activate_sections);
        }
        // dd($activate_sections_array);
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            if(in_array($value->id, $activate_sections_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $section_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;background-color: #EFEFEF;color: black;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-' .$value->id.'-ac-'.$request->index.'" style="font-size: 20px; color: #1e3d73;" onclick="question_for_activate(\'' . $value->id . '\',\'ac_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\')"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="activate_sections[' .$request->index. '][]" value="' . $value->id . '" '.$checked.' class="activate_section_'.$value->id.'_'.$request->index.'"  onclick="disabled_opposite(\''.$value->id.'\',\'deactivate_section_\',\''.$request->index.'\',\'activate_section_\')"> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody>
                                </table>
                                 </div>
                            </div>
                            <div class="card-body collapse row-'.$value->id.'-ac-'.$request->index.' ac_questions_list_'.$value->id.'_'.$request->index.'" style="padding: 0;">

                           </div>';
            $function_string .='disabled_opposite(\''.$value->id.'\',\'deactivate_section_\',\''.$request->index.'\',\'activate_section_\');';
            $function_string .= 'question_for_activate(\'' . $value->id . '\',\'ac_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');';
        }

        $section_array = array(
            'html_str' => $section_contents,
            'function_str' => $function_string
        );
        return json_encode($section_array);
    }
    public function sections_skip_logic_deactivate(Request $request,$id)
    {
        $deactivate_sections_array = [];
        $function_string = '';
        if($request->option_value != 'q_type_num'){
           $where = array(
                "question_id" =>$request->question_id,
                "option_title" =>$request->option_title,
                "option_value" =>$request->option_value
            );
        }else{
           $where = array(
                "id" =>$request->question_id,
            );
        }
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $deactivate_sections_array = explode(',', $if_exists_record->deactivate_sections);
        }
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            if(in_array($value->id, $deactivate_sections_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $section_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;background-color: #EFEFEF;color: black;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="question_for_deactivate(\'' . $value->id . '\',\'de_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-'.$value->id.'-de-'.$request->index.'" style="font-size: 20px; color: #1e3d73;"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="deactivate_sections[' .$request->index. '][]" value="' . $value->id . '" '.$checked.' class="deactivate_section_'.$value->id.'_'.$request->index.'"  onclick="disabled_opposite(\''.$value->id.'\',\'activate_section_\',\''.$request->index.'\',\'deactivate_section_\')"> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody>
                                </table>
                                 </div>
                            </div>
                            <div class="card-body collapse row-'.$value->id.'-de-'.$request->index.' de_questions_list_'.$value->id.'_'.$request->index.'" style="padding: 0;">
                            </div>';
            $function_string .='disabled_opposite(\''.$value->id.'\',\'activate_section_\',\''.$request->index.'\',\'deactivate_section_\');';
            $function_string .= 'question_for_deactivate(\'' . $value->id . '\',\'de_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');';

        }

        $section_array = array(
            'html_str' => $section_contents,
            'function_str' => $function_string
        );
        return json_encode($section_array);
    }
    public function questions_skip_logic(Request $request, $id)
    {
        $activate_questions_array = [];
        $function_string = '';
        if($request->option_value != 'q_type_num'){
           $where = array(
                "question_id" =>$request->question_id,
                "option_title" =>$request->option_title,
                "option_value" =>$request->option_value
            );
        }else{
           $where = array(
                "id" =>$request->question_id,
            );
        }
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $activate_questions_array = explode(',', $if_exists_record->activate_questions);
        }
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $options_ac_contents = '';
        foreach ($questions as $key => $value) {
            if(in_array($value->id, $activate_questions_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $options_ac_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;background-color: #F64E60;color:black;">
                                    <tbody>';
            $options_ac_contents .= '<tr><td class="sec_id" style="display: none;">'.$value->id.'</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-'.$value->id.'-ac-'.$request->index.'" style="font-size: 20px;color:white;" onclick="question_options_activate(\''.$value->id.'\',\'ac_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');"></i>
                                      </div>
                                    </td><td colspan="5"> <input type="checkbox" name="activate_questions[' .$request->index. '][]" value="'.$value->id.'" class="activate_question_'.$value->id.'_'.$request->index.'"  onclick="disabled_opposite(\''.$value->id.'\',\'deactivate_question_\',\''.$request->index.'\',\'activate_question_\');" '.$checked.'> ' . $value->question_text . '</td>';

            $options_ac_contents .= '</tr></tbody></table></div></div>';
            $options_ac_contents .= '<div class="card-body collapse row-'.$value->id.'-ac-'.$request->index.' " style="padding: 0;"><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="ac_options_list_'.$value->id.'_'.$request->index.'">
                                    </tbody>
                                </table> </div>';
            $function_string .='disabled_opposite(\''.$value->id.'\',\'deactivate_question_\',\''.$request->index.'\',\'activate_question_\');';
            $function_string .='question_options_activate(\''.$value->id.'\',\'ac_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');';
        }
        $options_ac_array = array(
            'html_str' => $options_ac_contents,
            'function_str' => $function_string
        );
        return json_encode($options_ac_array);
    }
    public function questions_skip_logic_deactivate(Request $request, $id)
    {
        $deactivate_questions_array = [];
        $function_str = '';
        if($request->option_value != 'q_type_num'){
           $where = array(
                "question_id" =>$request->question_id,
                "option_title" =>$request->option_title,
                "option_value" =>$request->option_value
            );
        }else{
           $where = array(
                "id" =>$request->question_id,
            );
        }
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $deactivate_questions_array = explode(',', $if_exists_record->deactivate_questions);
        }
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $key => $value) {
            if(in_array($value->id, $deactivate_questions_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $question_contents .= '<div class="card-body" style="padding: 0;background-color: #F64E60;color:black;">
                                    <table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $question_contents .= '<tr><td class="sec_id" style="display: none;">' . $value->id . '</td>
                                    <td style="text-align: center;width:15%;">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" style="font-size: 20px;color:white;" onclick="question_options_deactivate(\''.$value->id.'\',\'de_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-'.$value->id.'-de-'.$request->index.'">
                                            </i>
                                      </div>
                                    </td>
                                    <td  colspan="5"><input type="checkbox" name="deactivate_questions['.$request->index.'][]" value="'.$value->id.'" '.$checked.' class="deactivate_question_'.$value->id.'_'.$request->index.'"  onclick="disabled_opposite(\''.$value->id.'\',\'activate_question_\',\''.$request->index.'\',\'deactivate_question_\')"> '.$value->question_text.'</td>';

            $question_contents .= '</tr>';
            $question_contents .= '</tbody></table></div>';
            $question_contents .= '<div class="card-body collapse row-'.$value->id.'-de-'.$request->index.' " style="padding: 0;">
                            <div class="table-responsive"><table class="table table-bordered">
                                    <tbody class="de_options_list_'.$value->id.'_'.$request->index.'">

                                    </tbody>
                                </table>  </div></div>';
            $function_str .='disabled_opposite(\''.$value->id.'\',\'activate_question_\',\''.$request->index.'\',\'deactivate_question_\');';
            $function_str .='question_options_deactivate(\''.$value->id.'\',\'de_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');';
        }
        $question_contents_array = array(
            'html_str' => $question_contents,
            'function_str' => $function_str
        );
        return json_encode($question_contents_array);
    }
    public function options_skip_logic_activate(Request $request,$id)
    {
        $questions = Question::where('id', $request->id)->with('optionsGroup')->first();
        $options_contents = '';
        $function_string = '';
        $options_value = explode(',', $questions->optionsGroup->option_value);
        $options_name = explode(',', $questions->optionsGroup->option_name);
        if(null !== $questions->optionsGroup){
            if(count($options_name) >= 2){
                foreach ($options_name as $key => $value) {
                    if($request->option_value != 'q_type_num'){
                        $where = array(
                            "option_question_id" =>$request->id,
                            "title" =>$value,
                            "value" =>$options_value[$key],
                            "type" => 'activate'
                        );
                    }else{
                        $where = array(
                            "option_question_id" =>$request->id,
                            "title" =>$value,
                            "value" =>$options_value[$key],
                            "type" => 'activate'
                        );
                    }
                    $if_exists_record = QuestionOption::where($where)->first();
                    if(null !==$if_exists_record && ($if_exists_record->value == $options_value[$key])){
                        $checked = "checked";
                    }else{
                        $checked = "";
                    }
                    if(null !==$if_exists_record){
                        $deactivate_questions_array = explode(',', $if_exists_record->deactivate_questions);
                    }
                    $options_contents .= '<tr>
                                            <td style="text-align: center;width:15%;">
                                               <input type="checkbox" name="activate_options['.$request->index.'][]" value="'.$options_value[$key].'_'.$value.'_'.$questions->id.'" '.$checked.' class="activate_option_'.$questions->id.$value.'_'.$request->index.'"  onclick="disabled_opposite(\''.$questions->id.$value.'\',\'deactivate_option_\',\''.$request->index.'\',\'activate_option_\')">
                                            </td>
                                            <td colspan="5">'.$value.'</td>';
                    $options_contents .= '</tr>';
                    $function_string .='disabled_opposite(\''.$questions->id.$value.'\',\'deactivate_option_\',\''.$request->index.'\',\'activate_option_\');';
                }
            }else{
                $options_contents .='<tr><td colspan="6">Records Not found</td></tr>';
            }
        }
        $options_contents_array = array(
            'html_str' => $options_contents,
            'function_str' => $function_string
        );
        return json_encode($options_contents_array);
        // return Response($options_contents);
    }
    public function options_skip_logic_deactivate(Request $request, $id)
    {
        $questions = Question::where('id', $request->id)->with('optionsGroup')->first();
        $options_contents = '';
        $function_string = '';
        $options_value = explode(',', $questions->optionsGroup->option_value);
        $options_name = explode(',', $questions->optionsGroup->option_name);
        if(null !== $questions->optionsGroup){
            if(count($options_name) >= 2){
                foreach ($options_name as $key => $value) {
                    if($request->option_value == 'q_type_num'){
                        $where = array(
                            "option_question_id" =>$request->id,
                            "title" =>$value,
                            "value" =>$options_value[$key],
                            "type" => 'deactivate',
                            "option_depend_on_question_type" => 'number'
                        );
                    }elseif($request->option_value == 'q_type_text'){
                        $where = array(
                            "option_question_id" =>$request->id,
                            "title" =>$value,
                            "value" =>$options_value[$key],
                            "type" => 'deactivate',
                            "option_depend_on_question_type" => 'textbox'
                        );
                    }else{
                        $where = array(
                            "option_question_id" =>$request->id,
                            "title" =>$value,
                            "value" =>$options_value[$key],
                            "type" => 'deactivate',
                            "option_depend_on_question_type" => 'radio'
                        );
                    }

                    $if_exists_record = QuestionOption::where($where)->first();
                    if(null !==$if_exists_record && ($if_exists_record->value == $options_value[$key])){
                        $checked = "checked";
                    }else{
                        $checked = "";
                    }
                    $options_contents .= '<tr>
                                            <td style="text-align: center;width:15%;">
                                               <input type="checkbox" name="deactivate_options['.$request->index.'][]" value="'.$options_value[$key].'_'.$value.'_'.$questions->id.'" '.$checked.' class="deactivate_option_'.$questions->id.$value.'_'.$request->index.'"  onclick="disabled_opposite(\''.$questions->id.$value.'\',\'activate_option_\',\''.$request->index.'\',\'deactivate_option_\')">
                                            </td>
                                            <td colspan="5">'.$value.'</td>';
                    $options_contents .= '</tr>';
                    $function_string .='disabled_opposite(\''.$questions->id.$value.'\',\'activate_option_\',\''.$request->index.'\',\'deactivate_option_\');';
                }
            }else{
                $options_contents .='<tr><td colspan="6">Records Not found</td></tr>';
            }
        }
        $options_contents_array = array(
            'html_str' => $options_contents,
            'function_str' => $function_string
        );
        return json_encode($options_contents_array);
        // return Response($options_contents);
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
                    $op_content = explode('_', $request->deactivate_options[$i][$j]);
                    $skip_options = [
                        'id' => Str::uuid(),
                        'skip_logic_id' => $skiplogic_id,
                        'question_id' => $request->question_id,
                        'value' => $op_content[0],
                        'type' => 'deactivate',
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2],
                        'option_depend_on_question_type' => 'radio'
                        ];
                    QuestionOption::insert($skip_options);
                  }
                }
                // Activate Questions options
                if(isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0){
                   for($j = 0; $j < count($request->activate_options[$i]); $j++) {
                    $op_content = explode('_', $request->activate_options[$i][$j]);
                    $skip_options = [
                        'id' => Str::uuid(),
                        'skip_logic_id' => $skiplogic_id,
                        'question_id' => $request->question_id,
                        'value' => $op_content[0],
                        'type' => 'activate',
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2],
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

}
