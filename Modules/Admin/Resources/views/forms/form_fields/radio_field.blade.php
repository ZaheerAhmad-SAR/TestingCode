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
        <div class="col-11">
            @foreach ($options as $option_name => $option_value)
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" name="{{ $field_name }}"
                        onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
                        value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'checked' : '' }}
                        class="custom-control-input {{ $fieldId }}">
                    <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
                </div>
            @endforeach
        </div>
        <div class="col-1">@include('admin::forms.form_fields.query_popup')</div>
    </div>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
