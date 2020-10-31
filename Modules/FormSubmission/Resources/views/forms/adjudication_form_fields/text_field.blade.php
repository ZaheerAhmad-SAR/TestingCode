<input type="text" name="{{ $field_name }}" id="{{ $fieldId }}"
    onclick="copyValueToField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}', '{{ $copyToFieldId }}');"
        value="{{ $answer->answer }}" class="form-control-ocap bg-transparent  make_disable_it" readonly>
