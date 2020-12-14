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
$options = array_combine ( $option_names , $option_values );
$showFalseField = true;
@endphp
@foreach ($options as $option_name => $option_value)
@php
$checked = ($answer->answer == $option_value) ? 'checked' : '';
if($checked == 'checked' && $answer->answer != '-9999'){
    $showFalseField = false;
}
@endphp
    <div
        class="custom-control custom-radio {{ $optionGroup->option_layout == 'horizontal' ? 'custom-control-inline' : '' }}">
        <input type="radio" name="{{ $field_name }}"
            onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $step->formType->form_type }}', '{{ $field_name }}', '{{ $fieldId }}');"
            value="{{ $option_value }}" {{ $checked }}
            class="custom-control-input {{ $skipLogicQuestionIdStr }} {{ 'skip_logic_' . $option_name . '_' . $option_value }} {{ $fieldId }}">
        <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
    </div>
@endforeach
@if($showFalseField == true)
<input type="radio" name="{{ $field_name }}" id="{{ $fieldId }}" value="{{ $answer->answer }}" checked style="display:none;">
@endif
