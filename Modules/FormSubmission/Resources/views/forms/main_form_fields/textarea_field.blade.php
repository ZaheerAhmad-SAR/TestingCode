            <textarea name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', {{ $step->form_type_id }}, '{{ $field_name }}', '{{ $fieldId }}');"
                class="form-control-ocap bg-transparent">{{ $answer->answer }}</textarea>
