@php
$dataArray = ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'skipLogicQuestionIdStr'=> $skipLogicQuestionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required];
@endphp
@if ($fieldType == 'Radio')
    @include('formsubmission::forms.main_form_fields.radio_field', $dataArray)
@elseif($fieldType == 'Checkbox')
    @include('formsubmission::forms.main_form_fields.checkbox_field', $dataArray)
@elseif($fieldType == 'Dropdown')
    @include('formsubmission::forms.main_form_fields.dropdown_field', $dataArray)
@elseif($fieldType == 'Text')
    @include('formsubmission::forms.main_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Textarea')
    @include('formsubmission::forms.main_form_fields.textarea_field', $dataArray)
@elseif($fieldType == 'Number')
    @include('formsubmission::forms.main_form_fields.number_field', $dataArray)
@elseif($fieldType == 'Date & Time')
    @include('formsubmission::forms.main_form_fields.datetime_field', $dataArray)
@elseif($fieldType == 'Upload')
    @include('formsubmission::forms.main_form_fields.upload_field', $dataArray)
@elseif($fieldType == 'Calculated')
    @include('formsubmission::forms.main_form_fields.number_field', $dataArray)
@elseif($fieldType == 'Description')
    {!! html_entity_decode($question->formFields->text_info) !!}
@endif


@php



$questionSkipLogicStr = Modules\Admin\Entities\Question::generateQuestionSkipLogicFunction($question, true);
$checkQuestionSkipLogicStr = Modules\Admin\Entities\Question::generateCheckQuestionSkipLogicFunction($question, true);
$checkQuestionSkipLogicStrPageLoad = Modules\Admin\Entities\Question::generateCheckQuestionSkipLogicFunctionForPageLoad($question, true);

$cohortSkipLogicStr = Modules\Admin\Entities\StudyStructure::generateCohortSkipLogicFunction($phase, true);
$checkCohortSkipLogicStr = Modules\Admin\Entities\StudyStructure::generateCheckCohortSkipLogicFunction($phase, true);
$checkCohortSkipLogicStrPageLoad = Modules\Admin\Entities\StudyStructure::generateCheckCohortSkipLogicFunctionForPageLoad($phase, true);

$questionDependencyStr = Modules\Admin\Entities\Question::generateQuestionDependencyFunction($question, true);
$adjudicationQuestionValidationStr = Modules\Admin\Entities\PhaseSteps::generateJSFormValidationForQuestion($question, true);
@endphp



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

@push('script')
<script>
{!! $adjudicationQuestionValidationStr !!}
{!! $questionDependencyStr !!}
</script>
@endpush
