@php
$dataArray = ['field_name'=> $grader_field_name, 'questionIdStr'=>
    $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer, 'is_required'=> $is_required];
@endphp
@if ($fieldType == 'Radio')
    @include('formsubmission::forms.adjudication_form_fields.radio_field', $dataArray)
@elseif($fieldType == 'Checkbox')
    @include('formsubmission::forms.adjudication_form_fields.checkbox_field', $dataArray)
@elseif($fieldType == 'Dropdown')
    @include('formsubmission::forms.adjudication_form_fields.dropdown_field', $dataArray)
@elseif($fieldType == 'Text')
    @include('formsubmission::forms.adjudication_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Textarea')
    @include('formsubmission::forms.adjudication_form_fields.textarea_field', $dataArray)
@elseif($fieldType == 'Number')
    @include('formsubmission::forms.adjudication_form_fields.number_field', $dataArray)
@elseif($fieldType == 'Calculated')
    @include('formsubmission::forms.adjudication_form_fields.text_field', $dataArray)
@elseif($fieldType == 'Description')
    {!! $question->formFields->text_info !!}
@endif
