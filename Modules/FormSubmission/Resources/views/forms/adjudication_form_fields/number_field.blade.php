<input type="number" name="{{ $field_name }}[]" id="{{ $fieldId }}"
    onclick="copyValueToField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}', '{{ $copyToFieldId }}');"
value="{{ $answer->answer }}" class="form-control-ocap bg-transparent  make_disable_it {{ $questionIdStr }}" readonly  {{ $is_required }}>
