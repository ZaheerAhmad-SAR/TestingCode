<input type="number" name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
    value="{{ $answer->answer }}" class="form-control-ocap bg-transparent" {{ $is_required }}>
