@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = arrayFilter(explode(',', $optionGroup->option_value));
$option_names = arrayFilter(explode(',', $optionGroup->option_name));
$options = array_combine ( $option_names , $option_values );
@endphp
<select name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');"
    class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}" {{ $is_required }}>
    @foreach ($options as $option_name => $option_value)
        <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
            {{ $option_name }}
        </option>
    @endforeach
</select>
