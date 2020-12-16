@php
$dataArray = ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer];
@endphp
@if ($fieldType == 'Radio')
    @include('formsubmission::print.print_form_fields.radio_field', $dataArray)
@elseif($fieldType == 'Checkbox')
    @include('formsubmission::print.print_form_fields.checkbox_field', $dataArray)
@elseif($fieldType == 'Dropdown')
    @include('formsubmission::print.print_form_fields.dropdown_field', $dataArray)
@elseif($fieldType == 'Text')
    @include('formsubmission::print.print_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Textarea')
    @include('formsubmission::print.print_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Number')
    @include('formsubmission::print.print_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Date & Time')
    @include('formsubmission::print.print_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Upload')
    @include('formsubmission::print.print_form_fields.upload_field', $dataArray)
@elseif($fieldType == 'Calculated')
    @include('formsubmission::print.print_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Certification')
    @include('formsubmission::print.print_form_fields.certification_field', $dataArray)
@elseif($fieldType == 'Description')
    {!! html_entity_decode($question->formFields->text_info) !!}
@endif
