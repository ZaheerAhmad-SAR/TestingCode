@php
$field_name = 'field_' . $question->id;
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <textarea name="{{ $field_name }}" onchange="submitFormField{{ $formNameStr }}('{{ $field_name }}');"
        class="form-control-ocap bg-transparent">{{ $answer->answer }}</textarea>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
