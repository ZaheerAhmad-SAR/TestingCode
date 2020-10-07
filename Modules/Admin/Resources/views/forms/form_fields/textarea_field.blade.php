<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <textarea name="{{ $field_name }}" id="{{ $fieldId }}" onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
        class="form-control-ocap bg-transparent">{{ $answer->answer }}</textarea>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
