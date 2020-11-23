<textarea name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');"
class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}" {{ $is_required }}>{{ $answer->answer }}</textarea>
