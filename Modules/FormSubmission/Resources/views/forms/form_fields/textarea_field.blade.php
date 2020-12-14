<textarea name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $step->formType->form_type }}', '{{ $field_name }}', '{{ $fieldId }}');"
    class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}" {{ $is_required }}>{{ $answer->answer }}</textarea>
