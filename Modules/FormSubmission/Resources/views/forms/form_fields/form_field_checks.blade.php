@php
$dataArray = ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required];
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
@elseif($fieldType == 'Description')
    {!! $question->formFields->text_info !!}
@endif

@php
/*********************************** */
$questionValidationStr = Modules\Admin\Entities\PhaseSteps::generateJSFormValidationForQuestion($question, false);
$questionDependencyStr = Modules\Admin\Entities\Question::generateQuestionDependencyFunction($question, false);
@endphp

@push('script')
<script>
{!! $questionValidationStr !!}
{!! $questionDependencyStr !!}
</script>
@endpush
