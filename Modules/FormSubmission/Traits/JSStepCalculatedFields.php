<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\Question;

trait JSStepCalculatedFields
{
    public static function generateCalculatedFieldsJSFunctions($step)
    {
        $questionCalculatedFieldsFunctionsStr = '';
        $stepIdStr = buildSafeStr($step->step_id, '');
        foreach ($step->sections as $section) {
            $questions = Question::where('section_id', 'like', $section->id)->where('form_field_type_id', 12)->get();
            if (count($questions) > 0) {
                foreach ($questions as $question) {

                    $firstQuestion = Question::find($question->first_question_id);
                    $secondQuestion = Question::find($question->second_question_id);

                    $firstFieldName = buildFormFieldName($firstQuestion->formFields->variable_name);
                    $firstQuestionIdStr = buildSafeStr($firstQuestion->id, '');
                    $firstFieldId = $firstFieldName . '_' . $firstQuestionIdStr;

                    if (null !== $secondQuestion) {
                        $secondFieldName = buildFormFieldName($secondQuestion->formFields->variable_name);
                        $secondQuestionIdStr = buildSafeStr($secondQuestion->id, '');
                        $secondFieldId = $secondFieldName . '_' . $secondQuestionIdStr;
                    } else {
                        $secondFieldName = '';
                        $secondQuestionIdStr = '';
                        $secondFieldId = '';
                    }

                    $sectionIdStr = buildSafeStr($section->id, '');
                    $field_name = buildFormFieldName($question->formFields->variable_name);
                    $questionIdStr = buildSafeStr($question->id, '');
                    $fieldId = $field_name . '_' . $questionIdStr;

                    $questionCalculatedFieldsFunctionsStr .= '
                    calculateField(\'' . $firstFieldId . '\', \'' . $secondFieldId . '\', \'' . $question->operator_calculate . '\', \'' . $question->make_decision . '\', \'' . $question->calculate_with_costum_val . '\', \'' . $stepIdStr . '\', \'' . $sectionIdStr . '\', \'' . $question->id . '\', \'' . $questionIdStr . '\', ' . $step->form_type_id . ', \'' . $field_name . '\', \'' . $fieldId . '\');';
                }
            }
        }

        return $questionCalculatedFieldsFunctionsStr;
    }
}
