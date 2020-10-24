@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionGroup;

if(!empty($optionGroup->option_value)){
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
}else{
$option_values = [1];
$option_names = [$question->formFields->text_info];
}
$answersArray = explode(',', $answer->answer);
$options = array_combine ( $option_names , $option_values );
@endphp
@foreach ($options as $option_name => $option_value)
    <div
        class="custom-control custom-checkbox {{ $optionGroup->option_layout == 'horizontal' ? 'custom-control-inline' : '' }}">
        <input type="checkbox" name="{{ $field_name }}[]"
            onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
            value="{{ $option_value }}" {{ in_array($option_value, $answersArray) ? 'checked' : '' }}
            class="custom-control-input">
        <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
    </div>
@endforeach
