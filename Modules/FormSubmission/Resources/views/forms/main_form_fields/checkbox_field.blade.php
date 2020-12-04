@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionGroup;

if(!empty($optionGroup->option_value)){
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
}else{
$option_values = [1];
$option_names = [html_entity_decode($question->formFields->text_info)];
}
$answersArray = explode(',', $answer->answer);
$options = array_combine ( $option_names , $option_values );
$showFalseField = true;
@endphp
@foreach ($options as $option_name => $option_value)
@php
$checked = in_array($option_value, $answersArray) ? 'checked' : '';
if($checked == 'checked'){
    $showFalseField = false;
}
@endphp
<div
        class="custom-control custom-checkbox {{ $optionGroup->option_layout == 'horizontal' ? 'custom-control-inline' : '' }}">
        <input type="checkbox" name="{{ $field_name }}[]"
            onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');"
            value="{{ $option_value }}" {{ $checked }}
            class="custom-control-input {{ $skipLogicQuestionIdStr }} {{ 'skip_logic_' . $option_name . '_' . $option_value }}">
        <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
    </div>
@endforeach
@if($showFalseField == true)
<input type="checkbox" name="{{ $field_name }}[]" value="" checked style="display:none;">
@endif

