@php
$is_required = ($question->formFields->is_required == 'yes')? 'required':'';
@endphp
@if ($fieldType == 'Radio')
    @include('formsubmission::forms.adjudication_form_fields.radio_field', ['field_name'=> $grader_field_name, 'questionIdStr'=>
    $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Checkbox')
    @include('formsubmission::forms.adjudication_form_fields.checkbox_field', ['field_name'=> $grader_field_name,
    'questionIdStr'=> $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=>
    $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Dropdown')
    @include('formsubmission::forms.adjudication_form_fields.dropdown_field', ['field_name'=> $grader_field_name,
    'questionIdStr'=> $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=>
    $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Text')
    @include('formsubmission::forms.adjudication_form_fields.text_field', ['field_name'=> $grader_field_name, 'questionIdStr'=>
    $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Textarea')
    @include('formsubmission::forms.adjudication_form_fields.textarea_field', ['field_name'=> $grader_field_name,
    'questionIdStr'=> $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=>
    $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Number')
    @include('formsubmission::forms.adjudication_form_fields.number_field', ['field_name'=> $grader_field_name, 'questionIdStr'=>
    $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer, 'is_required'=> $is_required])
@endif
