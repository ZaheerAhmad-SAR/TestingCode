<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\ValidationRule;

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
}
