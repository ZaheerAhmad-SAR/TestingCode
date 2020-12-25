@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = arrayFilter(explode(',', $optionGroup->option_value));
$option_names = arrayFilter(explode(',', $optionGroup->option_name));
$options = array_combine ( $option_names , $option_values );
@endphp
@foreach ($options as $option_name => $option_value)
    @if($answer->answer == $option_value)
    <br><button type="button" class="btn btn-success">{{ $option_name }}</button>
    @endif
@endforeach
