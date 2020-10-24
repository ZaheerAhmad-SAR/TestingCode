@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<select name="{{ $field_name }}" id="{{ $fieldId }}"
    class="form-control-ocap bg-transparent  make_disable_it"
    onchange="copyValueToField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}', '{{ $copyToFieldId }}');">
    @foreach ($options as $option_name => $option_value)
        <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
            {{ $option_name }}
        </option>
    @endforeach
</select>
