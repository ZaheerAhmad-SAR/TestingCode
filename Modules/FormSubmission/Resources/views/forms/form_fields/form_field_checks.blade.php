@php
$dataArray = ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'skipLogicQuestionIdStr'=> $skipLogicQuestionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required];
@endphp
@if ($fieldType == 'Radio')
    @include('formsubmission::forms.form_fields.radio_field', $dataArray)
@elseif($fieldType == 'Checkbox')
    @include('formsubmission::forms.form_fields.checkbox_field', $dataArray)
@elseif($fieldType == 'Dropdown')
    @include('formsubmission::forms.form_fields.dropdown_field', $dataArray)
@elseif($fieldType == 'Text')
    @include('formsubmission::forms.form_fields.text_field', $dataArray)
@elseif($fieldType == 'Textarea')
    @include('formsubmission::forms.form_fields.textarea_field', $dataArray)
@elseif($fieldType == 'Number')
    @include('formsubmission::forms.form_fields.number_field', $dataArray)
@elseif($fieldType == 'Date & Time')
    @include('formsubmission::forms.form_fields.datetime_field', $dataArray)
@elseif($fieldType == 'Upload')
    @include('formsubmission::forms.form_fields.upload_field', $dataArray)
@elseif($fieldType == 'Calculated')
    @include('formsubmission::forms.form_fields.calculated_field', $dataArray)
@elseif($fieldType == 'Certification')
    @include('formsubmission::forms.form_fields.certification_field', $dataArray)
@elseif($fieldType == 'Description')
    {!! html_entity_decode($question->formFields->text_info) !!}
@endif
@php
/*********************************** */
$questionValidationStr = Modules\Admin\Entities\PhaseSteps::generateJSFormValidationForQuestion($question, false);
$questionDependencyStr = Modules\Admin\Entities\Question::generateQuestionDependencyFunction($question, false);

$questionSkipLogicStr = Modules\Admin\Entities\Question::generateQuestionSkipLogicFunction($question, false);
$checkQuestionSkipLogicStr = Modules\Admin\Entities\Question::generateCheckQuestionSkipLogicFunction($question, false);
$checkQuestionSkipLogicStrPageLoad = Modules\Admin\Entities\Question::generateCheckQuestionSkipLogicFunctionForPageLoad($question, false);

$cohortSkipLogicStr = Modules\Admin\Entities\StudyStructure::generateCohortSkipLogicFunction($phase, false);
$checkCohortSkipLogicStr = Modules\Admin\Entities\StudyStructure::generateCheckCohortSkipLogicFunction($phase, false);
$checkCohortSkipLogicStrPageLoad = Modules\Admin\Entities\StudyStructure::generateCheckCohortSkipLogicFunctionForPageLoad($phase, false);

@endphp

@push('script')
<script>
{!! $questionValidationStr !!}
{!! $questionDependencyStr !!}
</script>
@endpush

@push('script_skip_logic')
<script>
{!! $questionSkipLogicStr !!}
{!! $checkQuestionSkipLogicStr !!}
{!! $checkQuestionSkipLogicStrPageLoad !!}

{!! $cohortSkipLogicStr !!}
{!! $checkCohortSkipLogicStr !!}
{!! $checkCohortSkipLogicStrPageLoad !!}
</script>
@endpush
