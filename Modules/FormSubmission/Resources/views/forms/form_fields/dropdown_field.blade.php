@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = array_filter(explode(',', $optionGroup->option_value);
$option_names = array_filter(explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<select name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $step->formType->form_type }}', '{{ $field_name }}', '{{ $fieldId }}');"
    class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}"  {{ $is_required }}>
    @foreach ($options as $option_name => $option_value)
        <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
            {{ $option_name }}
        </option>
    @endforeach
</select>
