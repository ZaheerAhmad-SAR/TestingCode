<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionValidation;
use Modules\FormSubmission\Entities\ValidationRule;

class ValidationRuleController extends Controller
{

    public function filterRulesDataValidation(Request $request)
    {
        $questionType = $request->questionType;
        $validationRulesArray = ValidationRule::where('rule_group', 'like', '%' . $questionType . '%')->withOutRelatedToOtherFields()->pluck('title', 'id')->toArray();
        echo json_encode($validationRulesArray);
    }

    public function filterRulesDependency(Request $request)
    {
        $questionType = $request->questionType;
        $validationRules = ValidationRule::where('rule_group', 'like', '%' . $questionType . '%')->withRelatedToOtherFields()->get();
        $responseStr = '<select name="dependency_rules[]" class="form-control dependencyRuleDdCls"><option value="">Select dependency rule</option>';
        foreach ($validationRules as $validationRule) {
            $responseStr .= '<option value="' . $validationRule->id . '">' . $validationRule->title . '</option>';
        }
        echo $responseStr . '</select>';
    }

    public function getQuestionValidationRules(Request $request)
    {
        $questionId = $request->questionId;
        $question = Question::find($questionId);
        $validationRules = ValidationRule::where('rule_group', 'like', '%' . $question->form_field_type->field_type . '%')->withOutRelatedToOtherFields()->get();
        $questionValidations = QuestionValidation::where('question_id', 'like', $questionId)->get();
        $retHtmlStr = '';
        $ruleStr = '';
        $selected = '';

        foreach ($questionValidations as $questionValidation) {
            foreach ($validationRules as $validationRule) {
                $selected = ($validationRule->id == $questionValidation->validation_rule_id) ? 'selected="selected"' : '';
                $ruleStr .= '<option value="' . $validationRule->id . '" ' . $selected . '>' . $validationRule->title . '</option>';
            }
            $retHtmlStr .= '<div class="values_row">
            <div class="form-group row" style="margin-top: 10px;">
                <div class="col-sm-1"> Rule:</div>
                <div class="col-sm-4">
                <select name="validation_rules[]" class="form-control validationRuleDdCls">' . $ruleStr . '</select>
                </div>
                <div class="form-group col-md-1" style="text-align: right;!important;">
                    <i class="btn btn-outline-danger fa fa-trash remove" style="margin-top: 3px;"></i>
                </div>
            </div>
        </div>';
        }
        echo $retHtmlStr;
    }
}
