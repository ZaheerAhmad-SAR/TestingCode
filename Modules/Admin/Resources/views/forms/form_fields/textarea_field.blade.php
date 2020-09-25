@php
$field_name = 'field_' . $question->id;
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <textarea name="{{ $field_name }}" onchange="submitFormField{{ $sectionIdStr }}('{{ $field_name }}');"
        class="form-control-ocap bg-transparent {{ $sectionClsStr }}">{{ $answer->answer }}</textarea>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
