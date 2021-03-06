<?php

namespace Modules\FormSubmission\Traits;

use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Entities\ValidationRule;

trait JSQuestionDataValidation
{
    public static function generateJSFormValidationForStep($step, $isForAdjudication = false)
    {
        $stepValidationStr = '';
        $questionValidationStr = '';
        $stepIdStr = buildSafeStr($step->step_id, '');
        $functionName = ($isForAdjudication) ? 'validateAdjudicationQuestion' : 'validateQuestion';
        foreach ($step->sections as $section) {
            foreach ($section->questions as $question) {
                if($question->form_field_type_id != 11){
                    $questionIdStr = buildSafeStr($question->id, '');
                    $questionValidationStr .= '
                        isFormValid = ' . $functionName . $questionIdStr . '(isFormValid, "' . $stepIdStr . '");';
                }
            }
        }

        $stepValidationStr .= $questionValidationStr;
        return $stepValidationStr;
    }

    public static function generateJSFormValidationForQuestion($question, $isForAdjudication = false)
    {
        $mainQuestionValidationStr = '';
        $questionValidationStr = '';
        $messageTypeStr = '';

        if ($question->formFields->is_required == 'yes') {
            $questionValidationStr .= '
                    isFormValid = mustRequired(isFormValid, fieldTitle, fieldVal, questionRowId);
                    ';
        }
        //dd($question->questionValidations);
        foreach ($question->questionValidations as $questionValidation) {
            $validationRule = ValidationRule::find($questionValidation->validation_rule_id);
            $validationRuleStr = '';

            $messageType = $questionValidation->message_type;
            $message = $questionValidation->message;

            if ($validationRule->is_range == 1 || (int)$validationRule->num_params == 2) {
                if (!empty($questionValidation->parameter_1) && !empty($questionValidation->parameter_2)) {
                    $validationRuleStr .= $validationRule->rule;
                    $validationRuleStr .= '(isFormValid, fieldTitle, fieldVal, questionRowId, ' . $questionValidation->parameter_1 . ',' . $questionValidation->parameter_2 . ', \'' . $messageType . '\', \'' . $message . '\');';
                } else {
                    $validationRuleStr .= 'abortValidationWithError();';
                }
            } elseif ((int)$validationRule->num_params == 1) {
                if (
                    !empty($questionValidation->parameter_1)
                ) {
                    $validationRuleStr .= $validationRule->rule;
                    $validationRuleStr .= '(isFormValid, fieldTitle, fieldVal, questionRowId, ' . $questionValidation->parameter_1 . ', \'' . $messageType . '\', \'' . $message . '\');';
                } else {
                    $validationRuleStr .= 'abortValidationWithError();';
                }
            } elseif ((string)$validationRule->num_params == 'unlimited') {
                if (
                    !empty($questionValidation->parameter_1)
                ) {
                    $validationRuleStr .= $validationRule->rule;
                    $validationRuleStr .= '(isFormValid, fieldTitle, fieldVal, questionRowId, ' . $questionValidation->parameter_1 . ', \'' . $messageType . '\', \'' . $message . '\');';
                } else {
                    $validationRuleStr .= 'abortValidationWithError();';
                }
            } else {
                $validationRuleStr .= $validationRule->rule . '(isFormValid, fieldTitle, fieldVal, questionRowId, messageType, message);';
            }
            $questionValidationStr .= '
                    isFormValid = ' . $validationRuleStr;
        }



        $fieldName = buildFormFieldName($question->formFields->variable_name);
        $fieldTitle = json_encode($question->question_text);
        $questionIdStr = buildSafeStr($question->id, '');
        $fieldId = $fieldName . '_' . $questionIdStr;


        $functionName = ($isForAdjudication) ? 'validateAdjudicationQuestion' : 'validateQuestion';
        $getValueFunctionName = ($isForAdjudication) ? 'getAdjudicationFormFieldValueForRequired' : 'getFormFieldValueForRequired';
        $questionRowId = ($isForAdjudication) ? 'adjudication_question_row_' . $questionIdStr : 'question_row_' . $questionIdStr;
        $mainQuestionValidationStr .= '
                function ' . $functionName . $questionIdStr . '(isFormValid, stepIdStr){
                    if(isFormValid){
                        var fieldName = "' . $fieldName . '";
                        var fieldId = "' . $fieldId . '";
                        var fieldTitle = ' . $fieldTitle . ';
                        var questionRowId = "' . $questionRowId . '";
                        ' . $messageTypeStr . '

                        var fieldVal = ' . $getValueFunctionName . '(stepIdStr, fieldName, fieldId);

                        ' . $questionValidationStr . '
                    }
                    return isFormValid;
                }';

        
        return $mainQuestionValidationStr;
    }
}
