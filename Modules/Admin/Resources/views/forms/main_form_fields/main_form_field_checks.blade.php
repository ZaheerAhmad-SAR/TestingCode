@if ($fieldType == 'Radio')
    @include('admin::forms.main_form_fields.radio_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Checkbox')
    @include('admin::forms.main_form_fields.checkbox_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Dropdown')
    @include('admin::forms.main_form_fields.dropdown_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Text')
    @include('admin::forms.main_form_fields.text_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Textarea')
    @include('admin::forms.main_form_fields.textarea_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Number')
    @include('admin::forms.main_form_fields.number_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Date & Time')
    @include('admin::forms.main_form_fields.datetime_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@elseif($fieldType == 'Upload')
    @include('admin::forms.main_form_fields.upload_field', ['question'=> $question, 'field_name'=> $field_name,
    'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
@endif
