<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <input type="date" name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');" value="{{ $answer->answer }}"
        class="form-control-ocap bg-transparent">
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
