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
@endphp
@foreach ($options as $option_name => $option_value)
    @if(in_array($option_value, $answersArray))
    <br><button type="button" class="btn btn-success">{{ $option_name }}</button>
    @endif
@endforeach
