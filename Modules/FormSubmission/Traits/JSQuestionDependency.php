<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\Question;

trait JSQuestionDependency
{
    public static function generateQuestionDependencyFunction($question, $isForAdjudication = false)
    {
        $questionDependencyStr = '';

        $fieldName = buildFormFieldName($question->formFields->variable_name);
        $questionIdStr = buildSafeStr($question->id, '');
        $fieldId = $fieldName . '_' . $questionIdStr;

        $questionRowIdStr = ($isForAdjudication) ? 'adjudication_question_row_' . $questionIdStr : 'question_row_' . $questionIdStr;

        $functionName = ($isForAdjudication) ? 'showHideAdjudicationQuestion' : 'showHideQuestion';
        $getValueFunctionName = ($isForAdjudication) ? 'getAdjudicationFormFieldValue' : 'getFormFieldValue';

        $questionDependency = $question->questionDependency;
        if ($questionDependency->dep_on_question_id != 'not_any') {
            $dependentOnQuestion = Question::find($questionDependency->dep_on_question_id);

            $dependentOnFieldName = buildFormFieldName($dependentOnQuestion->formFields->variable_name);
            $dependentOnQuestionIdStr = buildSafeStr($dependentOnQuestion->id, '');
            $dependentOnFieldId = $dependentOnFieldName . '_' . $dependentOnQuestionIdStr;


            $questionDependencyStr .= '
            function ' . $functionName . $dependentOnQuestionIdStr . '(stepIdStr){
                if(' . $getValueFunctionName . '(stepIdStr, \'' . $dependentOnFieldName . '\', \'' . $dependentOnFieldId . '\') ' . $questionDependency->opertaor . ' ' . $questionDependency->custom_value . '){
                    $(\'#' . $questionRowIdStr . '\').hide();
                    $(\'#' . $fieldId . '\').val(-9999);
                }else{
                    $(\'#' . $questionRowIdStr . '\').show();
                    $(\'#' . $fieldId . '\').val(\'\');
                }
            }';
        }
        return $questionDependencyStr;
    }
}
