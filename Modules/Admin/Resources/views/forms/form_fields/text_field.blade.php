            <input type="text" name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
                value="{{ $answer->answer }}" class="form-control-ocap bg-transparent">
