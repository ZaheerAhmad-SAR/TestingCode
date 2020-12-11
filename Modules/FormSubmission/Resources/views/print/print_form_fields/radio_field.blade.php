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
<div id="{{ $fieldId }}" class="form-control-ocap bg-transparent {{ $skipLogicQuestionIdStr }}">
@foreach ($options as $option_name => $option_value)
    @if($answer->answer == $option_value)
    {{ $option_name }}<br>
    @endif
@endforeach
</div>
