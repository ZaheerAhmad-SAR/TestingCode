@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<select name="{{ $field_name }}" id="{{ $fieldId }}"
    onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
    class="form-control-ocap bg-transparent">
    @foreach ($options as $option_name => $option_value)
        <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
            {{ $option_name }}
        </option>
    @endforeach
</select>
