@php
$is_required = ($question->formFields->is_required == 'yes')? 'required':'';
@endphp
@if ($fieldType == 'Radio')
    @include('formsubmission::forms.main_form_fields.radio_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Checkbox')
    @include('formsubmission::forms.main_form_fields.checkbox_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Dropdown')
    @include('formsubmission::forms.main_form_fields.dropdown_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Text')
    @include('formsubmission::forms.main_form_fields.text_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Textarea')
    @include('formsubmission::forms.main_form_fields.textarea_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Number')
    @include('formsubmission::forms.main_form_fields.number_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Date & Time')
    @include('formsubmission::forms.main_form_fields.datetime_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@elseif($fieldType == 'Upload')
    @include('formsubmission::forms.main_form_fields.upload_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
@endif
