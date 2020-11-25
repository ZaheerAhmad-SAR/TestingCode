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
use Modules\Admin\Entities\QuestionOption;
use Illuminate\Support\Facades\DB;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class SkipNumberController extends Controller
{
    public function skip_question_on_number($id)
    {
        $num_values = Question::where('id', $id)->with('skiplogic')->get();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->get();
        return view('admin::forms.skip_question_num', compact('num_values','all_study_steps'));
    }
    // get sections to activate Sections with Question having type Number
    public function sections_skip_logic(Request $request,$id)
    {
        $activate_sections_array = [];
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            $section_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">'.$value->id.'</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-' .$value->id.'-ac-'.$request->index.'" style="font-size: 20px; color: #1e3d73;" onclick="question_for_activate(\'' . $value->id . '\',\'ac_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\')"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="activate_sections[' .$request->index. '][]" value="' . $value->id . '"> ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody>
                                </table>
                                 </div>
                            </div>
                            <div class="card-body collapse row-'.$value->id.'-ac-'.$request->index.' ac_questions_list_'.$value->id.'_'.$request->index.'" style="padding: 0;">

                           </div>';
        }
        
        $section_array = array(
            'html_str' => $section_contents
        );
        return json_encode($section_array);
    }
    // end function here
    // get sections to deactivate Sections with Question having type Number
    public function sections_skip_logic_deactivate(Request $request,$id)
    {
        $deactivate_sections_array = [];
        $section = Section::select('*')->where('phase_steps_id', $id)->orderBy('sort_number', 'asc')->get();
        $section_contents = '';
        foreach ($section as $key => $value) {
            $section_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $section_contents .= '<tr class=""><td class="sec_id" style="display: none;">' . $value->id . '</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="question_for_deactivate(\'' . $value->id . '\',\'de_questions_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-'.$value->id.'-de-'.$request->index.'" style="font-size: 20px; color: #1e3d73;"></i>
                                      </div>
                                    </td><td  colspan="5"> <input type="checkbox" name="deactivate_sections[' .$request->index. '][]" value="' . $value->id . '" > ' . $value->name . '</td>';
            $section_contents .= '</tr>';
            $section_contents .= '</tbody> 
                                </table>
                                 </div>
                            </div>
                            <div class="card-body collapse row-'.$value->id.'-de-'.$request->index.' de_questions_list_'.$value->id.'_'.$request->index.'" style="padding: 0;">
                            </div>';
        }
        
        $section_array = array(
            'html_str' => $section_contents
        );
        return json_encode($section_array);
    }
    // end function here
    
    //---------------------------------------------------
    // get all question for skip with against specific section
    public function questions_skip_logic(Request $request, $id)
    {
        $activate_questions_array = [];
       
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $options_ac_contents = '';
        foreach ($questions as $key => $value) {
            $options_ac_contents .= '<div class="card-body" style="padding: 0;">
                            <div class="table-responsive "><table class="table table-bordered" style="margin-bottom:0px;background-color: whitesmoke;">
                                    <tbody>';
            $options_ac_contents .= '<tr><td class="sec_id" style="display: none;">'.$value->id.'</td><td style="text-align: center;width:15%;">
                                      <div class="btn-group btn-group-sm" role="group">
                                        <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-'.$value->id.'-ac-'.$request->index.'" style="font-size: 20px; color: #1e3d73;" onclick="question_options_activate(\''.$value->id.'\',\'ac_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\')"></i>
                                      </div>
                                    </td><td colspan="5"> <input type="checkbox" name="activate_questions[' .$request->index. '][]" value="' . $value->id . '"> ' . $value->question_text . '</td>';
            $options_ac_contents .= '</tr></tbody></table></div></div>';
            $options_ac_contents .= '<div class="card-body collapse row-'.$value->id.'-ac-'.$request->index.' " style="padding: 0;">
                                <table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody class="ac_options_list_'.$value->id.'_'.$request->index.'">
                                    </tbody>
                                </table> </div>';

        }
        $options_ac_array = array(
            'html_str' => $options_ac_contents
        );
        return json_encode($options_ac_array);
    }
    // End function
    //---------------------------------------------------
    //---------------------------------------------------
    // get all question for skip with against specific section for deactivate
    public function questions_skip_logic_deactivate(Request $request, $id)
    {
        $deactivate_questions_array = [];
        $questions = Question::select('*')->where('section_id', $id)->orderBy('question_sort', 'asc')->get();
        $question_contents = '';
        foreach ($questions as $key => $value) {
            $question_contents .= '<div class="card-body" style="padding: 0;background-color: whitesmoke;">
                                    <table class="table table-bordered" style="margin-bottom:0px;">
                                    <tbody>';
            $question_contents .= '<tr><td class="sec_id" style="display: none;">' . $value->id . '</td>
                                    <td style="text-align: center;width:15%;">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" style="font-size: 20px; color: #1e3d73;" onclick="question_options_deactivate(\''.$value->id.'\',\'de_options_list_\',\''.$request->index.'\',\''.$request->question_id.'\',\''.$request->option_value.'\',\''.$request->option_title.'\');" data-target=".row-'.$value->id.'-de-'.$request->index.'">
                                            </i>
                                      </div>
                                    </td>
                                    <td  colspan="5"><input type="checkbox" name="deactivate_questions['.$request->index.'][]" value="'.$value->id.'"> '.$value->question_text.'</td>';
            $question_contents .= '</tr>';
            $question_contents .= '</tbody></table></div>';
            $question_contents .= '<div class="card-body collapse row-'.$value->id.'-de-'.$request->index.' " style="padding: 0;">
                            <div class="table-responsive"><table class="table table-bordered">
                                    <tbody class="de_options_list_'.$value->id.'_'.$request->index.'">

                                    </tbody>
                                </table>  </div></div>';
            
        }
        $question_contents_array = array(
            'html_str' => $question_contents
        );
        return json_encode($question_contents_array);
    }
    // End function
    //---------------------------------------------------
    //---------------------------------------------------
    // get all options to skip on Number field
    public function options_skip_logic_activate(Request $request,$id)
    {
        $questions = Question::where('id', $request->id)->with('optionsGroup')->first();
        $options_contents = '';
        $options_value = explode(',', $questions->optionsGroup->option_value);
        $options_name = explode(',', $questions->optionsGroup->option_name);
        if(null !== $questions->optionsGroup){
            foreach ($options_name as $key => $value) {
                $options_contents .= '<tr>
                                        <td style="text-align: center;width:15%;">
                                           <input type="checkbox" name="activate_options['.$request->index.'][]" value="'.$options_value[$key].'_'.$value.'_'.$questions->id.'">
                                        </td>
                                        <td colspan="5">'.$value.'</td>';
                $options_contents .= '</tr>';               
            }
        }    
        return Response($options_contents);
    }
    // end function

    // start function
    public function options_skip_logic_deactivate(Request $request, $id)
    {
        $questions = Question::where('id', $request->id)->with('optionsGroup')->first();
        $options_contents = '';
        $options_value = explode(',', $questions->optionsGroup->option_value);
        $options_name = explode(',', $questions->optionsGroup->option_name);
        if(null !== $questions->optionsGroup){
            foreach ($options_name as $key => $value) {
                $options_contents .= '<tr>
                                        <td style="text-align: center;width:15%;">
                                           <input type="checkbox" name="deactivate_options['.$request->index.'][]" value="'.$options_value[$key].'_'.$value.'_'.$questions->id.'">
                                        </td>
                                        <td colspan="5">'.$value.'</td>';
                $options_contents .= '</tr>';               
            }
        }    
        return Response($options_contents);
    }
    //---------------------------------------------------
    // Add skip conditions on Questions with type Number
    public function add_skipLogic_num(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if(isset($request->number_value) && count($request->number_value) > 0){
            for ($i = 0; $i < count($request->number_value); $i++) {
                $skip_ques = [
                    'id' => Str::uuid(),
                    'question_id' => $request->question_id,
                    'number_value' => (isset($request->number_value) && $request->number_value != '') ? implode(',', $request->number_value) : '',
                    'operator' => (isset($request->operator) && $request->operator != '') ? implode(',', $request->operator) : '',
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
                        'question_id' => $request->question_id,
                        'value' => $op_content[0], 
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2]
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
                        'question_id' => $request->question_id,
                        'value' => $op_content[0], 
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2]
                        ];
                    QuestionOption::insert($skip_options);
                  } 
                }
                skipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('skipNumber.numskipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
    public function update_skip_checks($id)
    {
        $num_values = skipLogic::where('id', $id)->first();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->get();
        return view('admin::forms.update_skip_question_num', compact('num_values','all_study_steps'));
    }
    public function update_skip_checks_on_number(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        $num_values = skipLogic::where('id', $request->question_id)->first();
        if(isset($request->number_value) && count($request->number_value) > 0){
            $where = array('question_id' =>$num_values->question_id);
            $remove_checks_if_already_exists = skipLogic::where($where)->delete();
            $remove_options_checks_if_exists = QuestionOption::where($where)->delete();

            for ($i = 0; $i < count($request->number_value); $i++) {
                $skip_ques = [
                    'id' => Str::uuid(),
                    'question_id' => $num_values->question_id,
                    'number_value' => (isset($request->number_value) && $request->number_value != '') ? implode(',', $request->number_value) : '',
                    'operator' => (isset($request->operator) && $request->operator != '') ? implode(',', $request->operator) : '',
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
                        'question_id' => $num_values->question_id,
                        'value' => $op_content[0], 
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2]
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
                        'question_id' => $num_values->question_id,
                        'value' => $op_content[0], 
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2]
                        ];
                    QuestionOption::insert($skip_options);
                  } 
                }
                skipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('skipNumber.numskipLogic', $num_values->question_id)->with('message', 'Checks Applied Successfully!'); 
    }
    public function add_skipLogic_text(Request $request){
        if (isset($request->textbox_value) && count($request->textbox_value) > 0) {
            $where = array('question_id' =>$request->question_id);
            $remove_checks_if_already_exists = skipLogic::where($where)->delete();
            for ($i = 0; $i < count($request->textbox_value); $i++) {
                $skip_ques = [
                    'id' => Str::uuid(),
                    'question_id' => $request->question_id,
                    'textbox_value' => (isset($request->textbox_value) && $request->textbox_value != '') ? implode(',', $request->textbox_value) : '',
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
                        'question_id' => $request->question_id,
                        'value' => $op_content[0], 
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2]
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
                        'question_id' => $request->question_id,
                        'value' => $op_content[0], 
                        'title' => $op_content[1],
                        'option_question_id' => $op_content[2]
                        ];
                    QuestionOption::insert($skip_options);
                  } 
                }
                skipLogic::insert($skip_ques);
            }
        }
        return redirect()->route('skiplogic.textskipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
}
