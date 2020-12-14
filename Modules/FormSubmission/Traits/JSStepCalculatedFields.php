<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;

trait JSStepCalculatedFields
{
    public static function runCalculatedFieldsJSFunctions($step)
    {
        $questionCalculatedFieldsFunctionsStr = '';
        $sectionIds = Section::where('phase_steps_id', $step->step_id)->pluck('id')->toArray();

        $questions = Question::whereIn('section_id', $sectionIds)->where('form_field_type_id', 12)->get();

        if (count($questions) > 0) {
            foreach ($questions as $question) {
                $questionIdStr = buildSafeStr($question->id, '');
                $questionCalculatedFieldsFunctionsStr .= '
                    calculateField' . $questionIdStr . '(triggeringQuestionIdStr);
                ';
            }
        }

        return $questionCalculatedFieldsFunctionsStr;
    }

    public static function generateCalculatedFieldsJSFunctions($step)
    {
        $mathFunctionsArray = ['sqrt', 'pow'];
        $questionCalculatedFieldsFunctionsStr = '';
        $stepIdStr = buildSafeStr($step->step_id, '');
        $sectionIds = Section::where('phase_steps_id', $step->step_id)->pluck('id')->toArray();
        $questionIds = Question::whereIn('section_id', $sectionIds)->where('form_field_type_id', 1)->pluck('id')->toArray();
        $variableNamesArray = FormFields::whereIn('question_id', $questionIds)->pluck('variable_name')->toArray();
        $variableNamesArray = array_filter($variableNamesArray);
        $questions = Question::whereIn('section_id', $sectionIds)->where('form_field_type_id', 12)->get();

        $triggeringQuestionIdsArray = [];
        if (count($questions) > 0) {
            foreach ($questions as $question) {
                $sectionIdStr = buildSafeStr($question->section_id, '');
                $field_name = buildFormFieldName($question->formFields->variable_name);
                $questionIdStr = buildSafeStr($question->id, '');
                $fieldId = $field_name . '_' . $questionIdStr;

                $customFormula = $question->custom_formula;
                $ifStr = '
                var doCalc = true;
                ';

                foreach ($variableNamesArray as $variableName) {
                    if (strpos($customFormula, '[' . $variableName . ']') !== false) {
                        $formField = FormFields::where('variable_name', 'like', $variableName)
                            ->whereIn('question_id', $questionIds)
                            ->first();
                        $newQuestionId = $formField->question_id;
                        $newFieldName = buildFormFieldName($variableName);
                        $newQuestionIdStr = buildSafeStr($newQuestionId, '');
                        $newFieldId = $newFieldName . '_' . $newQuestionIdStr;
                        $triggeringQuestionIdsArray[] = $newQuestionIdStr;
                        $ifStr .= '
                        if(getFormFieldValue(\'' . $stepIdStr . '\', \'' . $newFieldName . '\', \'' . $newFieldId . '\') ==  \'\'){
                            doCalc = false;
                        }';
                        $customFormula = str_replace('[' . $variableName . ']', 'Number(getFormFieldValue(\'' . $stepIdStr . '\', \'' . $newFieldName . '\', \'' . $newFieldId . '\'))', $customFormula);
                    }
                }

                foreach ($mathFunctionsArray as $mathFunction) {
                    if (strpos($customFormula, $mathFunction) !== false) {
                        $customFormula = str_replace($mathFunction, 'Math.' . $mathFunction, $customFormula);
                    }
                }

                $triggeringQuestionIdsJsArray = '[]';
                if (count($triggeringQuestionIdsArray) > 0) {
                    $triggeringQuestionIdsJsArray = '["' . implode('", "', $triggeringQuestionIdsArray) . '"]';
                }

                $questionCalculatedFieldsFunctionsStr .= '
                    function calculateField' . $questionIdStr . '(triggeringQuestionIdStr){
                        var triggeringQuestionIdsJsArray = ' . $triggeringQuestionIdsJsArray . ';
                        if(triggeringQuestionIdsJsArray.length > 0 && triggeringQuestionIdsJsArray.includes(triggeringQuestionIdStr)){
                            var answer = \'\';
                            ' . $ifStr . '
                            if(doCalc == true){
                                answer = ' . $customFormula . ';
                                $(\'#form_' . $stepIdStr . ' #' . $fieldId . '\').val(answer);
                                validateAndSubmitField(\'' . $stepIdStr . '\', \'' . $sectionIdStr . '\', \'' . $question->id . '\', \'' . $questionIdStr . '\', ' . $step->formType->form_type . ', \'' . $field_name . '\', \'' . $fieldId . '\');
                            }
                        }
                    }';
            }
        }

        return $questionCalculatedFieldsFunctionsStr;
    }
}
