@php
$field_name = $question->formfields->variable_name;
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <input type="number" name="{{ $field_name }}" onchange="submitFormField('{{ $sectionIdStr }}', '{{ $field_name }}');"
        value="{{ $answer->answer }}" class="form-control-ocap bg-transparent {{ $sectionClsStr }}">

    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
