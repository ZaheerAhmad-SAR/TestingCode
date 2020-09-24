@php
$field_name = 'field_' . $question->id;
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <select name="{{ $field_name }}" onchange="submitFormField{{ $sectionIdStr }}('{{ $field_name }}');"
        class="form-control-ocap bg-transparent {{ $sectionClsStr }}">
        @foreach ($options as $option_name => $option_value)
            <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
                {{ $option_name }}
            <option>
        @endforeach
    </select>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
