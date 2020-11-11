<input type="number" name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
    value="{{ str_replace(',', '', $answer->answer) }}" class="form-control-ocap bg-transparent" {{ $is_required }}>
