<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;

trait JSQuestionDependency
{
    public static function generateQuestionDependencyFunction($question, $isForAdjudication = false)
    {
        $questionDependencyStr = '';

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


            $questionDependencyStr .= '
            $( document ).ready(function() {
                disableAllFormFields(\'' . $questionRowIdStr . '\');
                //$(\'#form_' . $stepIdStr . ' #' . $fieldId . '\').val(-9999);
            });
            function ' . $functionName . $dependentOnQuestionIdStr . '(stepIdStr){
                if(' . $getValueFunctionName . '(stepIdStr, \'' . $dependentOnFieldName . '\', \'' . $dependentOnFieldId . '\') ' . $questionDependency->opertaor . ' \'' . $questionDependency->custom_value . '\'){
                    enableAllFormFields(\'' . $questionRowIdStr . '\');
                    if($(\'#form_' . $stepIdStr . ' #' . $fieldId . '\').val() == -9999){
                        //$(\'#form_' . $stepIdStr . ' #' . $fieldId . '\').val(\'\');
                    }
                }else{
                    disableAllFormFields(\'' . $questionRowIdStr . '\');
                    //$(\'#form_' . $stepIdStr . ' #' . $fieldId . '\').val(-9999);
                }
            }';
        }
        return $questionDependencyStr;
    }
}
