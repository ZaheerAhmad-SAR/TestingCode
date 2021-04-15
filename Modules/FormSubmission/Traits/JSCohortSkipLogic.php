<?php

namespace Modules\FormSubmission\Traits;

use Modules\Admin\Entities\CohortSkipLogicOption;

trait JSCohortSkipLogic
{

    public static function generateCheckCohortSkipLogicFunctionForPageLoad($phase, $isForAdjudication = false)
    {
        $phaseIdStr = buildSafeStr($phase->id, '');
        $checkFunctionName = ($isForAdjudication) ? 'checkCohortSkipLogicForAdjudication' : 'checkCohortSkipLogic';
        return '
        $(document).ready(function(){
        ' . $checkFunctionName . $phaseIdStr . '();
        });
        ';
    }

    public static function generateCheckCohortSkipLogicFunction($phase, $isForAdjudication = false)
    {
        $phaseSkipLogicStr = '';
        $phaseIdStr = buildSafeStr($phase->id, '');
        $functionName = ($isForAdjudication) ? 'cohortSkipLogicForAdjudication' : 'cohortSkipLogic';
        $checkFunctionName = ($isForAdjudication) ? 'checkCohortSkipLogicForAdjudication' : 'checkCohortSkipLogic';

        foreach ($phase->cohortSkiplogics as $skipLogic) {
            $skipLogicIdStr = buildSafeStr($skipLogic->id, '');
            $phaseSkipLogicStr .= '
            ' . $functionName . $skipLogicIdStr . '();
            ';
        }

        return '
        function ' . $checkFunctionName . $phaseIdStr . '(){
            
            ' . $phaseSkipLogicStr . '
        }';
    }
    //console.log(\'' . $checkFunctionName . $phaseIdStr . '\'); get from above function
    public static function generateCohortSkipLogicFunction($phase, $isForAdjudication = false)
    {
        $phaseSkipLogicStr = '';

        $functionName = ($isForAdjudication) ? 'cohortSkipLogicForAdjudication' : 'cohortSkipLogic';

        foreach ($phase->cohortSkiplogics as $skipLogic) {

            $skipLogicIdStr = buildSafeStr($skipLogic->id, '');

            /*---------------------*/
            $deActivateFormsArray = arrayFilter(explode(',', $skipLogic->deactivate_forms));
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
            $deActivateSectionsArray = arrayFilter(explode(',', $skipLogic->deactivate_sections));
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
            $deActivateQuestionsArray = arrayFilter(explode(',', $skipLogic->deactivate_questions));
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
            $skipLogicOptions = CohortSkipLogicOption::where('cohort_skiplogic_id', 'like', $skipLogic->id)->get();
            $deActivateQuestionOptionsClsArray = [];
            foreach ($skipLogicOptions as $skipLogicOption) {
                $deActivateQuestionOptionsClsArray[] = buildSafeStr($skipLogicOption->option_question_id, 'skip_logic_' . $skipLogicOption->value);
            }

            $deActivateQuestionOptionsClsArray = array_filter($deActivateQuestionOptionsClsArray);
            $deActivatedQuestionOptionsJsArray = '[]';
            if (count($deActivateQuestionOptionsClsArray) > 0) {
                $deActivatedQuestionOptionsJsArray = '["' . implode('", "', $deActivateQuestionOptionsClsArray) . '"]';
            }
            /*---------------------*/

            $phaseSkipLogicStr .= '

            function ' . $functionName . $skipLogicIdStr . '(){

                console.log(\'' . $functionName . $skipLogicIdStr . '\');

                var deactivate_forms = ' . $deActivatedFormsJsArray . ';
                var deactivate_sections = ' . $deActivatedSectionsJsArray . ';
                var deactivate_questions = ' . $deActivatedQuestionsJsArray . ';
                var deactivate_question_options = ' . $deActivatedQuestionOptionsJsArray . ';

                if(deactivate_forms.length > 0){
                    $.each(deactivate_forms, function( index, value ) {
                        if(value != \'\'){
                            putNotRequiredImage(value);
                            disableByClass(value);
                            disableLinkByClass(value);
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

            }
            ';
        }

        return $phaseSkipLogicStr;
    }
}
