<textarea name="{{ $field_name }}" id="{{ $fieldId }}"
    onclick="copyValueToField('{{ $field_name }}', '{{ $fieldId }}', '{{ $copyToFieldId }}');"
        class="form-control-ocap bg-transparent">{{ $answer->answer }}</textarea>
