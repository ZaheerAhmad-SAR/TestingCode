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
    use ReplicatePhaseStructure;
    public function skip_question_on_click($id)
    {
        $options = Question::where('id', $id)->with('optionsGroup', 'skiplogic')->first();
        return view('admin::forms.skip_logic', compact('options'));
    }
    public function skip_question_on_text($id)
    {
        $num_values = Question::where('id', $id)->with('skiplogic')->first();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->get();
        return view('admin::forms.skip_question_text', compact('num_values', 'all_study_steps'));
    }
    public function skip_logic_cohort($id)
    {
        $disease_cohorts = DiseaseCohort::where('study_id', '=', $id)->get();
        $cohort_skiplogic = CohortSkipLogic::where('study_id', '=', $id)->get();
        $all_study_steps = Study::where('id', session('current_study'))->with('studySteps')->get();
        return view('admin::studies.skip_logic_cohort', compact('all_study_steps', 'disease_cohorts', 'cohort_skiplogic'));
    }

    public function add_skipLogic(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if (isset($request->option_value) && count($request->option_value) > 0) {
            $where = array('question_id' => $request->question_id);
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
                            'option_depend_on_question_type' => 'radio'
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
                            'option_depend_on_question_type' => 'radio'
                        ];
                        QuestionOption::insert($skip_options);
                    }
                }
                skipLogic::insert($skip_ques);
            }
            $this->updateSkipLogicsToReplicatedVisits($request->question_id);
            $this->updateOptionSkipLogicsToReplicatedVisits($request->question_id);
        }
        return redirect()->route('skiplogic.skipLogic', $request->question_id)->with('message', 'Checks Applied Successfully!');
    }
    public function add_skipLogic_cohort_based(Request $request)
    {
        $skip_ques = [];
        $skip_options = [];
        if (isset($request->cohort_id) && count($request->cohort_id) > 0) {
            $where = array('study_id' => $request->study_id);
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
                if (isset($request->deactivate_options[$i]) && count($request->deactivate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->deactivate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->deactivate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'cohort_skiplogic_id' => $cohort_skiplogic_id,
                            'study_id' => $request->study_id,
                            'value' => $op_content[0],
                            'option_question_id' => $op_content[1]
                        ];
                        CohortSkipLogicOption::insert($skip_options);
                    }
                }
                // Activate Questions options
                if (isset($request->activate_options[$i]) && count($request->activate_options[$i]) > 0) {
                    for ($j = 0; $j < count($request->activate_options[$i]); $j++) {
                        $op_content = explode('<<=!=>>', $request->activate_options[$i][$j]);
                        $skip_options = [
                            'id' => Str::uuid(),
                            'cohort_skiplogic_id' => $cohort_skiplogic_id,
                            'study_id' => $request->study_id,
                            'value' => $op_content[0],
                            'option_question_id' => $op_content[1]
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
