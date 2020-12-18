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
@endphp
@foreach ($options as $option_name => $option_value)
@php
$checked = ($answer->answer == $option_value) ? 'checked' : '';
@endphp
    <div
        class="custom-control custom-radio {{ $optionGroup->option_layout == 'horizontal' ? 'custom-control-inline' : '' }}">
        <input type="radio" name="{{ $field_name }}"
            onchange="validateAndSubmitAdjudicationFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $questionIdStr }}', '{{ $field_name }}', '{{ $fieldId }}');"
            value="{{ $option_value }}" {{ $checked }}
            class="custom-control-input {{ $skipLogicQuestionIdStr }} {{ 'skip_logic_' . $questionIdStr . '_' . $option_value }} {{ $fieldId }}">
        <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
    </div>
@endforeach
