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

class SkipLogicController extends Controller
{
    public function skip_question_on_click($id)
    {
        $options = Question::where('id', $id)->with('optionsGroup', 'skiplogic')->first();
        return view('admin::forms.skip_logic', compact('options'));
    }
    public function getSteps_toskip(Request $request)
    {
        $activate_forms_array = [];
        $deactivate_forms_array = [];
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
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                <tbody>
                                    <tr>
                                        <td class="step_id" style="display: none;">' . $value->step_id . '</td>
                                        <td style="text-align: center;width: 15%">
                                          <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="activate_checks(\'' . $value->step_id . '\',\'sections_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-' .$value->step_id.'-ac-'.$request->index.'" style="font-size: 20px; color: #1e3d73;"></i>
                                          </div>
                                        </td>
                                        <td colspan="5"> <input type="checkbox" name="activate_forms[' .$request->index. '][]" value="' . $value->step_id . '" '.$checked.'> &nbsp;&nbsp;' . $value->step_name . '</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card collapse row-'.$value->step_id.'-ac-'.$request->index.' sections_list_'.$value->step_id.'_'.$request->index.'">
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
                                        <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                        <tbody>
                                            <tr>
                                                <td class="step_id" style="display: none;">' . $value->step_id . '</td>
                                                <td style="text-align: center;width: 15%">
                                                  <div class="btn-group btn-group-sm" role="group">
                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-'.$value->step_id.'-de-'.$request->index.'" onclick="deactivate_checks(\'' . $value->step_id . '\',\'de_sections_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" style="font-size: 20px; color: #1e3d73;"></i>
                                                  </div>
                                                </td>
                                                <td colspan="5"><input type="checkbox" name="deactivate_forms[' .$request->index. '][]" value="' . $value->step_id . '" '.$checked.'> &nbsp;&nbsp; ' . $value->step_name . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
              <div class="card collapse row-'.$value->step_id.'-de-'.$request->index.' de_sections_list_'.$value->step_id.'_'.$request->index.'">

                        </div>';

        }
        $step_contents_deactive .= '</div>';
        $step_contents = $step_contents_active . $step_contents_deactive;
        return $step_contents;
    }
    public function sections_skip_logic(Request $request,$id)
    {
        $activate_sections_array = [];
        $where = array(
            "question_id" =>$request->question_id,
            "option_title" =>$request->option_title,
            "option_value" =>$request->option_value
        );
        $if_exists_record = skipLogic::where($where)->first();
        
        if(null !== $if_exists_record){
            $activate_sections_array = explode(',', $if_exists_record->activate_sections);
        }
        // dd($activate_sections_array);
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            if(in_array($value->id, $activate_sections_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $section_contents .= '<div class="card-body" style="padding: 0;background-color: whitesmoke;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-' .$value->id.'-ac-'.$request->index.'" style="font-size: 20px; color: #1e3d73;" onclick="question_for_activate(\'' . $value->id . '\',\'ac_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\')"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="activate_sections[' .$request->index. '][]" value="' . $value->id . '" '.$checked.'> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody>
                                </table>
                                 </div>
                            </div>
                                    <div class="card-body collapse row-'.$value->id.'-ac-'.$request->index.' " style="padding: 0;">
                            <div class=""><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="ac_questions_list_'.$value->id.'_'.$request->index.'">

                                    </tbody>
                                </table>  </div></div>';
        }
        return Response($section_contents);
    }
    public function sections_skip_logic_deactivate(Request $request,$id)
    {
        $deactivate_sections_array = [];
        $where = array(
            "question_id" =>$request->question_id,
            "option_title" =>$request->option_title,
            "option_value" =>$request->option_value
        );
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $deactivate_sections_array = explode(',', $if_exists_record->deactivate_sections);
        }    
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            if(in_array($value->id, $deactivate_sections_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $section_contents .= '<div class="card-body" style="padding: 0;background-color: whitesmoke;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="question_for_deactivate(\'' . $value->id . '\',\'de_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-'.$value->id.'-de-'.$request->index.'" style="font-size: 20px; color: #1e3d73;"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="deactivate_sections[' .$request->index. '][]" value="' . $value->id . '" '.$checked.'> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody> 
                                </table>
                                 </div>
                            </div>
                                    <div class="card-body collapse row-'.$value->id.'-de-'.$request->index.' " style="padding: 0;">
                            <div class=""><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="de_questions_list_'.$value->id.'_'.$request->index.'">

                                    </tbody>
                                </table>  </div></div>';
        }
        return Response($section_contents);
    }
    public function questions_skip_logic(Request $request, $id)
    {
        $activate_questions_array = [];
        $where = array(
            "question_id" =>$request->question_id,
            "option_title" =>$request->option_title,
            "option_value" =>$request->option_value
        );
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $activate_questions_array = explode(',', $if_exists_record->activate_questions);
        }
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $key => $value) {
            if(in_array($value->id, $activate_questions_array)){ $checked = 'checked'; }else{ $checked = ''; }
             $question_contents .= '<div class="card-body" style="padding: 0;background-color: whitesmoke;">
                                    <div class="">
                                    <table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $question_contents .= '<tr><td style="display: none;">' . $value->id . '</td>
                                    <td style="text-align: center;width:15%;">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-' .$value->id.'-ac-'.$request->index.'" style="font-size: 20px; color: #1e3d73;" onclick="question_options_activate(\'' . $value->id . '\',\'ac_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\')">
                                            </i>
                                      </div>
                                    </td>
                                    <td  colspan="5"><input type="checkbox" name="activate_questions[' . $request->index . '][]" value="' . $value->id . '" '.$checked.'> '.$value->question_text.'</td>';
            $question_contents .= '</tr>';
            $question_contents .= '</tbody></table></div></div>';
            $question_contents .= '<div class="card-body collapse row-'.$value->id.'-ac-'.$request->index.' " style="padding: 0;">
                            <div class=""><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="ac_options_list_'.$value->id.'_'.$request->index.'">

                                    </tbody>
                                </table>  </div></div>';
        }
        return Response($question_contents);
    }
    public function questions_skip_logic_deactivate(Request $request, $id)
    {
        $deactivate_questions_array = [];
        $where = array(
            "question_id" =>$request->question_id,
            "option_title" =>$request->option_title,
            "option_value" =>$request->option_value
        );
        $if_exists_record = skipLogic::where($where)->first();
        if(null !==$if_exists_record){
            $deactivate_questions_array = explode(',', $if_exists_record->deactivate_questions);
        }    
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $key => $value) {
            if(in_array($value->id, $deactivate_questions_array)){ $checked = 'checked'; }else{ $checked = ''; }
            $question_contents .= '<div class="card-body" style="padding: 0;background-color: whitesmoke;">
                                    <div class="">
                                    <table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $question_contents .= '<tr><td class="sec_id" style="display: none;">' . $value->id . '</td>
                                    <td style="text-align: center;width:15%;">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" style="font-size: 20px; color: #1e3d73;" onclick="question_options_deactivate(\'' . $value->id . '\',\'de_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\')" data-target=".row-'.$value->id.'-de-'.$request->index.'">
                                            </i>
                                      </div>
                                    </td>
                                    <td  colspan="5"><input type="checkbox" name="deactivate_questions['.$request->index.'][]" value="'.$value->id.'" '.$checked.'> '.$value->question_text.'</td>';
            $question_contents .= '</tr>';
            $question_contents .= '</tbody></table></div></div>';
            $question_contents .= '<div class="card-body collapse row-'.$value->id.'-de-'.$request->index.' " style="padding: 0;">
                            <div class="table-responsive"><table class="table table-bordered">
                                    <tbody class="de_options_list_'.$value->id.'_'.$request->index.'">

                                    </tbody>
                                </table>  </div></div>';
        }
        return Response($question_contents);
    }
    public function options_skip_logic_deactivate(Request $request, $id)
    {
        $questions = Question::where('id', $request->id)->with('optionsGroup')->first();
        $options_contents = '';
        $options_value = explode(',', $questions->optionsGroup->option_value);
        $options_name = explode(',', $questions->optionsGroup->option_name);
        if(null !== $questions->optionsGroup){
            foreach ($options_name as $key => $value) {
                $options_contents .= '<tr><td class="sec_id" style="display: none;"></td>
                                        <td style="text-align: center;width:15%;">
                                           <input type="checkbox" name="deactivate_options['.$request->index.'][]" value="'.$options_value[$key].'">
                                        </td>
                                        <td colspan="5">'.$value.'</td>';
                $options_contents .= '</tr>';               
            }
        }    
        return Response($options_contents);
    }
    public function add_skipLogic(Request $request)
    {
        $skip_ques = [];
        if (isset($request->option_value) && count($request->option_value) > 0) {
            $where = array('question_id' =>$request->question_id);
            $remove_checks_if_already_exists = skipLogic::where($where)->delete();
            for ($i = 0; $i < count($request->option_value); $i++) {
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
        return redirect()->route('skiplogic.skipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }

}