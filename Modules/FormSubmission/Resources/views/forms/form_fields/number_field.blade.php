<input type="number" name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $step->formType->form_type }}', '{{ $field_name }}', '{{ $fieldId }}');"
    value="{{ $answer->answer }}" class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}" {{ $is_required }}>
