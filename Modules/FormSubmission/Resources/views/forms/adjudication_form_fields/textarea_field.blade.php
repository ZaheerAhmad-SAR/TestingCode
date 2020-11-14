<textarea name="{{ $field_name }}" id="{{ $fieldId }}"
    onclick="copyValueToField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}', '{{ $copyToFieldId }}');"
        class="form-control-ocap bg-transparent make_disable_it"  readonly  {{ $is_required }}>{{ $answer->answer }}</textarea>
