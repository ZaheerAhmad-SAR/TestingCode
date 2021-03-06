<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\Section;

trait JSQuestionDependency
{
    public static function generateQuestionDependencyFunction($question, $isForAdjudication = false)
    {
        $questionDependencyStr = '';
        $disabledAllfields = '';
        $load_function_with_dom = '';
        $questionDependencyIdStr = '';
        $dependency_conditions = '';
        $disabled_all_dependent_questions = '';
        $section = Section::find($question->section_id);
        $step = PhaseSteps::find($section->phase_steps_id);
        $stepIdStr = buildSafeStr($step->step_id, '');

        $fieldName = buildFormFieldName($question->formFields->variable_name);
        $questionIdStr = buildSafeStr($question->id, '');
        $fieldId = $fieldName . '_' . $questionIdStr;

        $questionRowIdStr = ($isForAdjudication) ? 'adjudication_question_row_' . $questionIdStr : 'question_row_' . $questionIdStr;

        $functionName = ($isForAdjudication) ? 'showHideAdjudicationQuestion' : 'showHideQuestion';
        $getValueFunctionName = ($isForAdjudication) ? 'getAdjudicationFormFieldValue' : 'getFormFieldValue';

        $questionDependency = $question->questionDependency;

        if (null !== $questionDependency->dep_on_question_id && $questionDependency->dep_on_question_id != 'not_any') {
            $dependentOnQuestion = Question::find($questionDependency->dep_on_question_id);

            $dependentOnFieldName = buildFormFieldName($dependentOnQuestion->formFields->variable_name);
            $dependentOnQuestionIdStr = buildSafeStr($dependentOnQuestion->id, '');
            $dependentOnFieldId = $dependentOnFieldName . '_' . $dependentOnQuestionIdStr;
            // 
            $questionDependencyIdStr = buildSafeStr($questionDependency->id, '');
            // dependency conditions
            // dd('abid'.$questionDependency->dep_on_question_id);
            $getAllAppliedLogics = QuestionDependency::where('dep_on_question_id',$dependentOnQuestion->id)->get();
      
            if(null !== $getAllAppliedLogics){
                foreach($getAllAppliedLogics as $fields){
                    // new id's get form dependencey table
                    $questionIdStr_new = buildSafeStr($fields->question_id, '');
                    $questionRowIdStr_new = ($isForAdjudication) ? 'adjudication_question_row_' . $questionIdStr_new : 'question_row_' . $questionIdStr_new;
                    $dependency_conditions .= 'if(' . $getValueFunctionName . '(stepIdStr, \'' . $dependentOnFieldName . '\', \'' . $dependentOnFieldId . '\') ' . $fields->opertaor . ' \'' . $fields->custom_value . '\'){
                            enableAllFormFields(\'' . $questionRowIdStr_new . '\');
                        }else{
                            disableAllFormFields(\'' . $questionRowIdStr_new . '\');
                        }';
                    // loading of function on documents load
                }
            }
            $load_function_with_dom .= $functionName.$dependentOnQuestionIdStr .'_'. $questionDependencyIdStr.'(\'' . $stepIdStr . '\');';
            $questionDependencyStr .= '
                function ' . $functionName . $dependentOnQuestionIdStr .'_'. $questionDependencyIdStr .'(stepIdStr){
                    '.$dependency_conditions.';
                }
                
               $( document ).ready(function() {
                    '.$load_function_with_dom.'
                }); ';
        }
        return $questionDependencyStr;
    }
}
