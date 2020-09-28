@php
$field_name = $question->formfields->variable_name;
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <textarea name="{{ $field_name }}" onchange="submitFormField('{{ $sectionIdStr }}', '{{ $field_name }}');"
        class="form-control-ocap bg-transparent {{ $sectionClsStr }}">{{ $answer->answer }}</textarea>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
