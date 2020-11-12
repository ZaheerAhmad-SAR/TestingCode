<?php

namespace Modules\FormSubmission\Traits;

use Modules\FormSubmission\Entities\FormStatus;

trait JSQuestionDataValidation
{
    public static function generateJSFormValidationForStep($phase, $subjectId, $studyId, $isForAdjudication = false)
    {
        $stepValidationStr = '';

        foreach ($phase->steps as $step) {
            $questionValidationStr = '';
            $functionName = ($isForAdjudication) ? 'validateAdjudicationQuestion' : 'validateQuestion';
            foreach ($step->sections as $section) {
                foreach ($section->questions as $question) {
                    $questionIdStr = buildSafeStr($question->id, '');
                    $stepIdStr = buildSafeStr($step->step_id, '');
                    $questionValidationStr .= 'isFormValid = ' . $functionName . $questionIdStr . '(isFormValid, "' . $stepIdStr . '");';
                }
            }

            $stepValidationStr .= '
            if(stepIdStr == \'' . $stepIdStr . '\'){
                ' . $questionValidationStr . '
            }';
        }
        return $stepValidationStr;
    }

    public static function generateJSFormValidationForQuestion($question, $isForAdjudication = false)
    {
        $mainQuestionValidationStr = '';
        $questionValidationStr = '';
        if ($question->formFields->is_required == 'yes') {
            $questionValidationStr .= '
                    isFormValid = mustRequired(isFormValid, fieldTitle, fieldVal);
                    ';
        }
        foreach ($question->validationRules as $validationRule) {
            $validationRuleStr = '';
            if ($validationRule->is_range == 1 || (int)$validationRule->num_params == 2) {
                if (!empty($question->formFields->lower_limit) && !empty($question->formFields->upper_limit)) {
                    $validationRuleStr .= $validationRule->rule;
                    $validationRuleStr .= '(isFormValid, fieldTitle, fieldVal, ' . $question->formFields->lower_limit . ',' . $question->formFields->upper_limit . ');';
                } else {
                    $validationRuleStr .= 'abortValidationWithError();';
                }
            } elseif ((int)$validationRule->num_params == 1) {
                if (
                    !empty($question->formFields->lower_limit)
                ) {
                    $validationRuleStr .= $validationRule->rule;
                    $validationRuleStr .= '(isFormValid, fieldTitle, fieldVal, ' . $question->formFields->lower_limit . ');';
                } else {
                    $validationRuleStr .= 'abortValidationWithError();';
                }
            } elseif ((string)$validationRule->num_params == 'unlimited') {
                if (
                    !empty($question->formFields->lower_limit)
                ) {
                    $validationRuleStr .= $validationRule->rule;
                    $validationRuleStr .= '(isFormValid, fieldTitle, fieldVal, ' . $question->formFields->lower_limit . ');';
                } else {
                    $validationRuleStr .= 'abortValidationWithError();';
                }
            } else {
                $validationRuleStr .= $validationRule->rule . '(isFormValid, fieldTitle, fieldVal);';
            }
            $questionValidationStr .= '
                    isFormValid = ' . $validationRuleStr;
        }



        $fieldName = buildFormFieldName($question->formFields->variable_name);
        $fieldTitle = $question->question_text;
        $questionIdStr = buildSafeStr($question->id, '');
        $fieldId = $fieldName . '_' . $questionIdStr;

        $functionName = ($isForAdjudication) ? 'validateAdjudicationQuestion' : 'validateQuestion';

        $mainQuestionValidationStr .= '
                function ' . $functionName . $questionIdStr . '(isFormValid, stepIdStr){
                    if(isFormValid){
                        var fieldName = "' . $fieldName . '";
                        var fieldId = "' . $fieldId . '";
                        var fieldTitle = "' . $fieldTitle . '";
                        var fieldVal = getFormFieldValue(stepIdStr, fieldName, fieldId);
                        ' . $questionValidationStr . '
                    }
                    return isFormValid;
                }';


        return $mainQuestionValidationStr;
    }
}
