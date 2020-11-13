<input type="number" name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');"
    value="{{ str_replace(',', '', $answer->answer) }}" class="form-control-ocap bg-transparent" {{ $is_required }}>
