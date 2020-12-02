<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\QuestionOption;
use Modules\Admin\Entities\Section;

trait JSQuestionSkipLogic
{

    public static function generateCheckQuestionSkipLogicFunctionForPageLoad($question, $isForAdjudication = false)
    {
        $questionIdStr = buildSafeStr($question->id, '');
        $checkFunctionName = ($isForAdjudication) ? 'checkQuestionSkipLogicForAdjudication' : 'checkQuestionSkipLogic';
        return '
        $(document).ready(function(){
        ' . $checkFunctionName . $questionIdStr . '();
        });
        ';
    }

    public static function generateCheckQuestionSkipLogicFunction($question, $isForAdjudication = false)
    {
        $questionSkipLogicStr = '';
        $questionIdStr = buildSafeStr($question->id, '');
        $functionName = ($isForAdjudication) ? 'skipLogicForAdjudication' : 'skipLogic';
        $checkFunctionName = ($isForAdjudication) ? 'checkQuestionSkipLogicForAdjudication' : 'checkQuestionSkipLogic';

        foreach ($question->skiplogic as $skipLogic) {
            $skipLogicIdStr = buildSafeStr($skipLogic->id, '');
            $questionSkipLogicStr .= '
            ' . $functionName . $skipLogicIdStr . '();
            ';
        }

        return '
        function ' . $checkFunctionName . $questionIdStr . '(){
            ' . $questionSkipLogicStr . '
        }';
    }

    public static function generateQuestionSkipLogicFunction($question, $isForAdjudication = false)
    {
        $questionSkipLogicStr = '';

        $section = Section::find($question->section_id);
        $step = PhaseSteps::find($section->phase_steps_id);
        $stepIdStr = buildSafeStr($step->step_id, '');

        $fieldName = buildFormFieldName($question->formFields->variable_name);
        $questionIdStr = buildSafeStr($question->id, '');
        $fieldId = $fieldName . '_' . $questionIdStr;

        $functionName = ($isForAdjudication) ? 'skipLogicForAdjudication' : 'skipLogic';
        $getValueFunctionName = ($isForAdjudication) ? 'getAdjudicationFormFieldValue' : 'getFormFieldValue';

        foreach ($question->skiplogic as $skipLogic) {

            $skipLogicIdStr = buildSafeStr($skipLogic->id, '');

            /*---------------------*/
            $activateFormsArray = explode(',', $skipLogic->activate_forms);
            $activateFormsClsArray = [];
            foreach ($activateFormsArray as $id) {
                $activateFormsClsArray[] = buildSafeStr($id, 'skip_logic_');
            }
            $activateFormsClsArray = array_filter($activateFormsClsArray);

            $activatedFormsJsArray = '[]';
            if (count($activateFormsClsArray) > 0) {
                $activatedFormsJsArray = '["' . implode('", "', $activateFormsClsArray) . '"]';
            }

            /*---------------------*/

            /*---------------------*/
            $activateSectionsArray = explode(',', $skipLogic->activate_sections);
            $activateSectionsClsArray = [];
            foreach ($activateSectionsArray as $id) {
                $activateSectionsClsArray[] = buildSafeStr($id, 'skip_logic_');
            }
            $activateSectionsClsArray = array_filter($activateSectionsClsArray);

            $activatedSectionsJsArray = '[]';
            if (count($activateSectionsClsArray) > 0) {
                $activatedSectionsJsArray = '["' . implode('", "', $activateSectionsClsArray) . '"]';
            }
            /*---------------------*/

            /*---------------------*/
            $activateQuestionsArray = explode(',', $skipLogic->activate_questions);
            $activateQuestionsClsArray = [];
            foreach ($activateQuestionsArray as $id) {
                $activateQuestionsClsArray[] = buildSafeStr($id, 'skip_logic_');
            }
            $activateQuestionsClsArray = array_filter($activateQuestionsClsArray);

            $activatedQuestionsJsArray = '[]';
            if (count($activateQuestionsClsArray) > 0) {
                $activatedQuestionsJsArray = '["' . implode('", "', $activateQuestionsClsArray) . '"]';
            }
            /*---------------------*/

            /*---------------------*/
            $deActivateFormsArray = explode(',', $skipLogic->deactivate_forms);
            $deActivateFormsClsArray = [];
            foreach ($deActivateFormsArray as $id) {
                $deActivateFormsClsArray[] = buildSafeStr($id, 'skip_logic_');
            }
            $deActivateFormsClsArray = array_filter($deActivateFormsClsArray);

            $deActivatedFormsJsArray = '[]';
            if (count($deActivateFormsClsArray) > 0) {
                $deActivatedFormsJsArray = '["' . implode('", "', $deActivateFormsClsArray) . '"]';
            }
            /*---------------------*/

            /*---------------------*/
            $deActivateSectionsArray = explode(',', $skipLogic->deactivate_sections);
            $deActivateSectionsClsArray = [];
            foreach ($deActivateSectionsArray as $id) {
                $deActivateSectionsClsArray[] = buildSafeStr($id, 'skip_logic_');
            }
            $deActivateSectionsClsArray = array_filter($deActivateSectionsClsArray);

            $deActivatedSectionsJsArray = '[]';
            if (count($deActivateSectionsClsArray) > 0) {
                $deActivatedSectionsJsArray = '["' . implode('", "', $deActivateSectionsClsArray) . '"]';
            }
            /*---------------------*/

            /*---------------------*/
            $deActivateQuestionsArray = explode(',', $skipLogic->deactivate_questions);
            $deActivateQuestionsClsArray = [];
            foreach ($deActivateQuestionsArray as $id) {
                $deActivateQuestionsClsArray[] = buildSafeStr($id, 'skip_logic_');
            }
            $deActivateQuestionsClsArray = array_filter($deActivateQuestionsClsArray);

            $deActivatedQuestionsJsArray = '[]';
            if (count($deActivateQuestionsClsArray) > 0) {
                $deActivatedQuestionsJsArray = '["' . implode('", "', $deActivateQuestionsClsArray) . '"]';
            }
            /*---------------------*/

            /*---------------------*/
            $skipLogicOptions = QuestionOption::where('skip_logic_id', 'like', $skipLogic->id)->get();
            $activateQuestionOptionsClsArray = [];
            $deActivateQuestionOptionsClsArray = [];
            foreach ($skipLogicOptions as $skipLogicOption) {
                if ($skipLogicOption->type == 'activate') {
                    $activateQuestionOptionsClsArray[] = buildSafeStr($skipLogicOption->option_question_id, 'skip_logic_' . $skipLogicOption->title . '_' . $skipLogicOption->value);
                }
                if ($skipLogicOption->type == 'deactivate') {
                    $deActivateQuestionOptionsClsArray[] = buildSafeStr($skipLogicOption->option_question_id, 'skip_logic_' . $skipLogicOption->title . '_' . $skipLogicOption->value);
                }
            }

            $activateQuestionOptionsClsArray = array_filter($activateQuestionOptionsClsArray);
            $activatedQuestionOptionsJsArray = '[]';
            if (count($activateQuestionOptionsClsArray) > 0) {
                $activatedQuestionOptionsJsArray = '["' . implode('", "', $activateQuestionOptionsClsArray) . '"]';
            }

            $deActivateQuestionOptionsClsArray = array_filter($deActivateQuestionOptionsClsArray);
            $deActivatedQuestionOptionsJsArray = '[]';
            if (count($deActivateQuestionOptionsClsArray) > 0) {
                $deActivatedQuestionOptionsJsArray = '["' . implode('", "', $deActivateQuestionOptionsClsArray) . '"]';
            }
            /*---------------------*/

            $skipLogicOperator = (!empty($skipLogic->operator)) ? $skipLogic->operator : '==';

            $questionSkipLogicStr .= '

            function ' . $functionName . $skipLogicIdStr . '(){

                var stepIdStr = \'' . $stepIdStr . '\';
                var fieldVal = ' . $getValueFunctionName . '(stepIdStr, \'' . $fieldName . '\', \'' . $fieldId . '\');

                var option_title = \'' . $skipLogic->option_title . '\';
                var option_value = \'' . $skipLogic->option_value . '\';
                var textbox_value = \'' . $skipLogic->textbox_value . '\';
                var number_value = \'' . $skipLogic->number_value . '\';
                var operator = \'' . $skipLogic->operator . '\';

                var activate_forms = ' . $activatedFormsJsArray . ';
                var activate_sections = ' . $activatedSectionsJsArray . ';
                var activate_questions = ' . $activatedQuestionsJsArray . ';
                var activate_question_options = ' . $activatedQuestionOptionsJsArray . ';

                var deactivate_forms = ' . $deActivatedFormsJsArray . ';
                var deactivate_sections = ' . $deActivatedSectionsJsArray . ';
                var deactivate_questions = ' . $deActivatedQuestionsJsArray . ';
                var deactivate_question_options = ' . $deActivatedQuestionOptionsJsArray . ';

                var isRunActivateDeactivate = false;

                if(option_value != \'\'){
                    if(option_value == fieldVal){
                        isRunActivateDeactivate = true;
                    }
                }else if(number_value != \'\'){
                    if(number_value ' . $skipLogicOperator . ' fieldVal){
                        isRunActivateDeactivate = true;
                    }
                }else if(textbox_value != \'\'){
                    if(textbox_value == fieldVal){
                        isRunActivateDeactivate = true;
                    }
                }

                if(isRunActivateDeactivate == true){

                    if(deactivate_forms.length > 0){
                        $.each(deactivate_forms, function( index, value ) {
                            if(value != \'\'){
                                disableByClass(value);
                            }
                        });
                    }

                    if(deactivate_sections.length > 0){
                        $.each(deactivate_sections, function( index, value ) {
                            if(value != \'\'){
                                disableByClass(value);
                            }
                        });
                    }

                    if(deactivate_questions.length > 0){
                        $.each(deactivate_questions, function( index, value ) {
                            if(value != \'\'){
                                disableByClass(value);
                            }
                        });
                    }

                    if(deactivate_question_options.length > 0){
                        $.each(deactivate_question_options, function( index, value ) {
                            if(value != \'\'){
                                disableByClass(value);
                            }
                        });
                    }

                    if(activate_forms.length > 0){
                        $.each(activate_forms, function( index, value ) {
                            if(value != \'\'){
                                enableByClass(value);
                            }
                        });
                    }

                    if(activate_sections.length > 0){
                        $.each(activate_sections, function( index, value ) {
                            if(value != \'\'){
                                enableByClass(value);
                            }
                        });
                    }

                    if(activate_questions.length > 0){
                        $.each(activate_questions, function( index, value ) {
                            if(value != \'\'){
                                enableByClass(value);
                            }
                        });
                    }

                    if(activate_question_options.length > 0){
                        $.each(activate_question_options, function( index, value ) {
                            if(value != \'\'){
                                enableByClass(value);
                            }
                        });
                    }

                }

            }
            ';
        }

        return $questionSkipLogicStr;
    }
}
