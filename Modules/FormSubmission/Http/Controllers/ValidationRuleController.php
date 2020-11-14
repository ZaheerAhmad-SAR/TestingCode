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

    public function getNumParams(Request $request)
    {
        $ruleId = $request->ruleId;
        $validationRule = ValidationRule::find($ruleId);
        echo $validationRule->num_params;
    }

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

        $message_types = [
            'warning' => 'Warning',
            'error' => 'Error',
            'info' => 'Info',
        ];

        foreach ($questionValidations as $questionValidation) {
            $ruleStr = '';
            $selected = '';
            $questionValidationRule = ValidationRule::find($questionValidation->validation_rule_id);
            foreach ($validationRules as $validationRule) {
                $selected = ($validationRule->id == $questionValidation->validation_rule_id) ? 'selected="selected"' : '';
                $ruleStr .= '<option value="' . $validationRule->id . '" ' . $selected . '>' . $validationRule->title . '</option>';
            }

            $messageTypeStr = '';
            $messageTypeSelected = '';
            foreach ($message_types as $value => $message_type) {
                $messageTypeSelected = ($questionValidation->message_type == $value) ? 'selected="selected"' : '';
                $messageTypeStr .= '<option value="' . $value . '" ' . $messageTypeSelected . '>' . $message_type . '</option>';
            }
            $hideParam1 = 'style="display:block;"';
            $hideParam2 = 'style="display:block;"';

            if ($questionValidationRule->num_params == 0) {
                $hideParam1 = 'style="display:none;"';
                $hideParam2 = 'style="display:none;"';
            }
            if ($questionValidationRule->num_params == 1 || $questionValidationRule->num_params == 'unlimited') {
                $hideParam2 = 'style="display:none;"';
            }
            $retHtmlStr .= '<div class="values_row">
            <div class="row">
                <div class="col-sm-2"> Rule:</div>
                <div class="col-sm-2">
                <select name="validation_rules[]" class="form-control validationRuleDdCls" onchange="getNumParams(this);">' . $ruleStr . '</select>
                </div>
                <div class="col-sm-2" ' . $hideParam1 . '> Parameter-1:</div>
                <div class="col-sm-2" ' . $hideParam1 . '> <input type="number" name="parameter_1[]" value="' . $questionValidation->parameter_1 . '" class="form-control" required></div>
                <div class="col-sm-2" ' . $hideParam2 . '> Parameter-2:</div>
                <div class="col-sm-2" ' . $hideParam2 . '> <input type="number" name="parameter_2[]" value="' . $questionValidation->parameter_2 . '" class="form-control" required></div>
            </div>
            <div class="row"><div class="col-sm-12">&nbsp;</div></div>
            <div class="row">
                <div class="col-sm-2"> Message Type:</div>
                <div class="col-sm-2">
                    <select name="message_type[]" class="form-control" required>
                    ' . $messageTypeStr . '
                    </select>
                </div>
                <div class="col-sm-2"> Message:</div>
                <div class="col-sm-4"> <input type="text" name="message[]" value="' . $questionValidation->message . '" class="form-control" required></div>
                <div class="col-sm-1"> <input type="text" name="sort_order[]" value="' . $questionValidation->sort_order . '" class="form-control" required></div>
                <div class="form-group col-md-1" style="text-align: right;!important;">
                    <i class="btn btn-outline-danger fa fa-trash remove" style="margin-top: 3px;"></i>
                </div>
            </div>
            <div class="row"><div class="col-sm-12"><hr class="hr"></div></div>
        </div>';
        }
        echo $retHtmlStr;
    }
}
