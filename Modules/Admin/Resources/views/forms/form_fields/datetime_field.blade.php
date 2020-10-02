<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <input type="date" name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField{{ $questionIdStr }}('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');" value="{{ $answer->answer }}"
        class="form-control-ocap bg-transparent">
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
    if(inputField.value == ''){
        showAlert(field_name + " is Required");
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
