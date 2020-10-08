@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
$option_values = explode(',', $optionGroup->option_value);
$option_names = explode(',', $optionGroup->option_name);
$options = array_combine ( $option_names , $option_values );
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <div class="row">
        <div class="col-10">
            <select name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
                class="form-control-ocap bg-transparent">
                @foreach ($options as $option_name => $option_value)
                    <option value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'selected' : '' }}>
                        {{ $option_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-1">@include('admin::forms.form_fields.info_popup', ['question'=>$question->question_text, 'text_info'=>$question->formFields->text_info])</div><div class="col-1">@include('admin::forms.form_fields.query_popup')</div>
    </div>

</div>
