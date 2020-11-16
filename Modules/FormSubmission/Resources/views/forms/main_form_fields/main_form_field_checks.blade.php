@php
$dataArray = ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required];
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
@endif


@php
$adjudicationQuestionValidationStr = Modules\Admin\Entities\PhaseSteps::generateJSFormValidationForQuestion($question, true);
$questionDependencyStr = Modules\Admin\Entities\Question::generateQuestionDependencyFunction($question, true);
@endphp

@push('script')
<script>
{!! $adjudicationQuestionValidationStr !!}
{!! $questionDependencyStr !!}
</script>
@endpush
