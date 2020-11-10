<?php

namespace Modules\FormSubmission\Traits;

use Illuminate\Http\Request;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Question;
use Illuminate\Support\Facades\Validator;


trait QuestionDataValidation
{
    public function validateSectionQuestionsForm(Request $request)
    {

        $sectionIds = $request->sectionId;
        foreach ($sectionIds as $sectionId) {
            $section = Section::find($sectionId);
            $questions = $section->questions;
            foreach ($questions as $question) {
                $returnArray = $this->validateField($request, $question);
                if (null !== $returnArray && $returnArray['success'] == 'no') {
                    break 2;
                }
            }
        }
        echo json_encode($returnArray);
    }

    public function validateSingleQuestion(Request $request)
    {
        $questionId = $request->questionId;
        $question = Question::find($questionId);
        $returnArray = $this->validateField($request, $question);
        echo json_encode($returnArray);
    }

    private function validateField($request, $question)
    {
        $form_field_name = buildFormFieldName($question->formFields->variable_name);

        if ($request->has($form_field_name)) {
            /************************************** */
            $validationRulesArray = [];
            if ($question->formFields->is_required == 'yes') {
                $validationRulesArray[] = 'required';
            }
            foreach ($question->validationRules as $validationRule) {
                $validationRuleStr = '';

                if ($validationRule->is_range == 1 || (int)$validationRule->num_params == 2) {
                    if (
                        !empty($question->formFields->lower_limit) &&
                        !empty($question->formFields->upper_limit)
                    ) {
                        $validationRuleStr .= $validationRule->rule;
                        $validationRuleStr .= ':' . $question->formFields->lower_limit . ',' . $question->formFields->upper_limit;
                    } else {
                        return $this->abortValidationWithError();
                    }
                } elseif ((int)$validationRule->num_params == 1) {
                    if (
                        !empty($question->formFields->lower_limit)
                    ) {
                        $validationRuleStr .= $validationRule->rule;
                        $validationRuleStr .= ':' . $question->formFields->lower_limit;
                    } else {
                        return $this->abortValidationWithError();
                    }
                } elseif ((string)$validationRule->num_params == 'unlimited') {
                    if (
                        !empty($question->formFields->lower_limit)
                    ) {
                        $validationRuleStr .= $validationRule->rule;
                        $validationRuleStr .= ':' . $question->formFields->lower_limit;
                    } else {
                        return $this->abortValidationWithError();
                    }
                } else {
                    $validationRuleStr .= $validationRule->rule;
                }

                $validationRulesArray[] = $validationRuleStr;
            }
            $validationRulesString = implode('|', $validationRulesArray);
            $validator = Validator::make(
                [$form_field_name => trim($request->{$form_field_name})],
                [$form_field_name => $validationRulesString]
            );

            if ($validator->fails()) {
                $errors = $validator->errors();
                return ['success' => 'no', 'error' => $errors->first($form_field_name)];
            }
        }
        return ['success' => 'yes', 'error' => ''];
    }

    private function abortValidationWithError()
    {
        $returnArray = [];
        $returnArray['success'] = 'no';
        $returnArray['error'] = 'Required parameters for validation are not available';
        return $returnArray;
    }
}
