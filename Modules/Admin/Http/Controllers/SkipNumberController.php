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

class SkipNumberController extends Controller
{
    use ReplicatePhaseStructure;

    public function skip_question_on_number($id)
    {
        $question_label = Question::where('id', $id)->first();
        $section = Section::where('id', $question_label->section_id)->first();
        $step = PhaseSteps::where('step_id', $section->phase_steps_id)->first();
        $num_values = Question::where('id', $id)->with('skiplogic')->get();
        $all_study_steps = PhaseSteps::where('phase_id', $step->phase_id)->get();
        return view('admin::forms.skip_question_num', compact('question_label', 'num_values', 'all_study_steps', 'step'));
    }
    // Add skip conditions on Questions with type Number
    public function add_skipLogic_num(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if (isset($request->number_value) && count($request->number_value) > 0) {
            for ($i = 0; $i < count($request->number_value); $i++) {
                $skiplogic_id = Str::uuid();
                $skip_ques = [
                    'id' => $skiplogic_id,
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
                if (isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->deactivate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $request->question_id,
                            'value' => $op_content[0],
                            'type' => 'deactivate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'number'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                // Activate Questions options
                if (isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->activate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->activate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $request->question_id,
                            'value' => $op_content[0],
                            'type' => 'activate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'number'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                skipLogic::insert($skip_ques);
            }
            $this->updateSkipLogicsToReplicatedVisits($request->question_id);
            $this->updateOptionSkipLogicsToReplicatedVisits($request->question_id);
        }
        return redirect()->route('skipNumber.numskipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
    public function update_skip_checks($id)
    {
        $num_values = skipLogic::where('id', $id)->first();
        $question_id = $num_values->question_id;
        $question = Question::where('id', $question_id)->first();
        $section = Section::where('id', $question->section_id)->first();
        $step = PhaseSteps::where('step_id', $section->phase_steps_id)->first();
        $all_study_steps = PhaseSteps::where('phase_id', $step->phase_id)->get();
        return view('admin::forms.update_skip_question_num', compact('num_values', 'all_study_steps', 'step'));
    }
    public function update_skip_checks_on_number(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        $num_values = skipLogic::where('id', $request->question_id)->first();
        if (isset($request->number_value) && count($request->number_value) > 0) {
            $where = array('question_id' => $num_values->question_id);
            $remove_checks_if_already_exists = skipLogic::where($where)->delete();
            $remove_options_checks_if_exists = QuestionOption::where($where)->delete();
            for ($i = 0; $i < count($request->number_value); $i++) {
                $skiplogic_id = Str::uuid();
                $skip_ques = [
                    'id' => $skiplogic_id,
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
                if (isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->deactivate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $num_values->question_id,
                            'value' => $op_content[0],
                            'type' => 'deactivate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'number'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                // Activate Questions options
                if (isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->activate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->activate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $num_values->question_id,
                            'value' => $op_content[0],
                            'type' => 'activate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'number'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                skipLogic::insert($skip_ques);
            }
            $this->updateSkipLogicsToReplicatedVisits($num_values->question_id);
            $this->updateOptionSkipLogicsToReplicatedVisits($num_values->question_id);
        }

        return redirect()->route('skipNumber.numskipLogic', $num_values->question_id)->with('message', 'Checks Applied Successfully!');
    }
    public function add_skipLogic_text(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if (isset($request->textbox_value) && count($request->textbox_value) > 0) {
            for ($i = 0; $i < count($request->textbox_value); $i++) {
                $skiplogic_id = Str::uuid();
                $skip_ques = [
                    'id' => $skiplogic_id,
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
                if (isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->deactivate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $request->question_id,
                            'value' => $op_content[0],
                            'type' => 'deactivate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'textbox'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                // Activate Questions options
                if (isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->activate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->activate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $request->question_id,
                            'value' => $op_content[0],
                            'type' => 'activate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'textbox'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                skipLogic::insert($skip_ques);
            }
            $this->updateSkipLogicsToReplicatedVisits($request->question_id);
            $this->updateOptionSkipLogicsToReplicatedVisits($request->question_id);
        }
        return redirect()->route('skiplogic.textskipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
    // update logic on Question have type Text
    public function update_skip_checks_text($id)
    {
        $num_values = skipLogic::where('id', $id)->first();
        $question_id = $num_values->question_id;
        $question = Question::where('id', $question_id)->first();
        $section = Section::where('id', $question->section_id)->first();
        $step = PhaseSteps::where('step_id', $section->phase_steps_id)->first();
        $question_info = Question::where('id', $num_values->question_id)->first();
        $all_study_steps = PhaseSteps::where('phase_id', $step->phase_id)->get();
        return view('admin::forms.update_skip_question_text', compact('question_info', 'num_values', 'all_study_steps', 'step'));
    }
    // insert updated checks and deleted previous logic
    public function update_skip_checks_on_textbox(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        $num_values = skipLogic::where('id', $request->question_id)->first();
        if (isset($request->textbox_value) && count($request->textbox_value) > 0) {
            $where = array('question_id' => $num_values->question_id);
            $remove_checks_if_already_exists = skipLogic::where($where)->delete();
            $remove_options_checks_if_exists = QuestionOption::where($where)->delete();

            for ($i = 0; $i < count($request->textbox_value); $i++) {
                $skiplogic_id = Str::uuid();
                $skip_ques = [
                    'id' => $skiplogic_id,
                    'question_id' => $num_values->question_id,
                    'textbox_value' => (isset($request->textbox_value) && $request->textbox_value != '') ? implode(',', $request->textbox_value) : '',
                    'activate_forms' => (isset($request->activate_forms[$i]) && $request->activate_forms[$i] != '') ? implode(',', $request->activate_forms[$i]) : '',
                    'activate_sections' => (isset($request->activate_sections[$i]) && $request->activate_sections[$i] != '') ? implode(',', $request->activate_sections[$i]) : '',
                    'activate_questions' => (isset($request->activate_questions[$i]) && $request->activate_questions[$i] != '') ? implode(',', $request->activate_questions[$i]) : '',
                    'deactivate_forms' => (isset($request->deactivate_forms[$i]) && $request->deactivate_forms[$i] != '') ? implode(',', $request->deactivate_forms[$i]) : '',
                    'deactivate_sections' => (isset($request->deactivate_sections[$i]) && $request->deactivate_sections[$i] != '') ? implode(',', $request->deactivate_sections[$i]) : '',
                    'deactivate_questions' => (isset($request->deactivate_questions[$i]) && $request->deactivate_questions[$i] != '') ? implode(',', $request->deactivate_questions[$i]) : ''
                ];
                // Deactivate Questions options
                if (isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->deactivate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $num_values->question_id,
                            'value' => $op_content[0],
                            'type' => 'deactivate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'textbox'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                // Activate Questions options
                if (isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->activate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->activate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'skip_logic_id' => $skiplogic_id,
                            'question_id' => $num_values->question_id,
                            'value' => $op_content[0],
                            'type' => 'activate',
                            'option_question_id' => $op_content[1],
                            'option_depend_on_question_type' => 'textbox'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                skipLogic::insert($skip_ques);
            }
            $this->updateSkipLogicsToReplicatedVisits($num_values->question_id);
            $this->updateOptionSkipLogicsToReplicatedVisits($num_values->question_id);
        }
        return redirect()->route('skiplogic.textskipLogic', $num_values->question_id)->with('message', 'Checks Applied Successfully!');
    }
}
