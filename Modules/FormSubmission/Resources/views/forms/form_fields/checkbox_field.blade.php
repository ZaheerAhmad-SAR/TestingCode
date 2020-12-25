@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionGroup;

if(!empty($optionGroup->option_value)){
$option_values = array_filter(explode(',', $optionGroup->option_value));
$option_names = array_filter(explode(',', $optionGroup->option_name));
}else{
$option_values = [1];
$option_names = [html_entity_decode($question->formFields->text_info)];
}
$answersArray = array_filter(explode(',', $answer->answer));
$options = array_combine ( $option_names , $option_values );
$showFalseField = false;
@endphp
@foreach ($options as $option_name => $option_value)
    @php
    $checked = in_array($option_value, $answersArray) ? 'checked' : '';
    if($answer->answer == '-9999'){
    $showFalseField = true;
    }
    @endphp
    <div
        class="custom-control custom-checkbox {{ $optionGroup->option_layout == 'horizontal' ? 'custom-control-inline' : '' }}">
        <input type="checkbox" name="{{ $field_name }}[]"
            onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $step->formType->form_type }}', '{{ $field_name }}', '{{ $fieldId }}');"
            value="{{ $option_value }}" {{ $checked }} class="custom-control-input {{ $skipLogicQuestionIdStr }} {{ buildSafeStr($question->id, 'skip_logic_' . $option_value) }}">
        <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
    </div>
@endforeach
@if ($showFalseField == true)
    <input type="checkbox" name="{{ $field_name }}[]"  id="{{ $fieldId }}" value="{{ $answer->answer }}" checked style="display:none;">
@endif
