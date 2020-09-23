@php
$field_name = 'field_' . $question->id;
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <input type="text" name="{{ $field_name }}" onchange="submitFormField{{ $formNameStr }}('{{ $field_name }}');"
        value="{{ $answer->answer }}" class="form-control-ocap bg-transparent">
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
