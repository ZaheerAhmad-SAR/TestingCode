@php
$field_name = $question->formfields->variable_name;
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    @foreach ($options as $option_name => $option_value)
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" name="{{ $field_name }}"
                onchange="submitFormField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $field_name }}');" value="{{ $option_value }}"
                {{ $answer->answer == $option_value ? 'checked' : '' }} class="custom-control-input">
            <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
        </div>
    @endforeach
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
