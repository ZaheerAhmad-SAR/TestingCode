<textarea name="{{ $field_name }}" id="{{ $fieldId }}"
    onclick="copyValueToField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}', '{{ $copyToFieldId }}');"
        class="form-control-ocap bg-transparent make_disable_it"  readonly>{{ $answer->answer }}</textarea>
