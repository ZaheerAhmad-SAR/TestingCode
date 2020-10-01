@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    @foreach ($options as $option_name => $option_value)
        <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField{{ $questionIdStr }}('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');" value="{{ $option_value }}"
        {{ $answer->answer == $option_value ? 'checked' : '' }} class="custom-control-input">
            <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
        </div>
    @endforeach
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
@push('script')
<script>
function validateAndSubmitField{{ $questionIdStr }}(stepIdStr, sectionIdStr, field_name, fieldId){

    checkIsThisFieldDependent{{ $questionIdStr }}(field_name, fieldId);
    validateData{{ $questionIdStr }}(field_name, fieldId);
    validateDependentFields{{ $questionIdStr }}(field_name, fieldId);

    submitFormField(stepIdStr, sectionIdStr, field_name);
}
function validateData{{ $questionIdStr }}(field_name, fieldId){

    var inputField = document.getElementById(fieldId);
    if(inputField.checked == false ){
        alert(field_name + " is Required");
        inputField.focus();
        return false;
    }
}

function checkIsThisFieldDependent{{ $questionIdStr }}(field_name, fieldId){

}

function validateDependentFields{{ $questionIdStr }}(field_name, fieldId){

}
</script>
@endpush
