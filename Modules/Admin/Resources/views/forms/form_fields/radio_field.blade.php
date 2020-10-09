@php
$option_names = [];
$option_values = [];
$optionGroup = $question->optionsGroup;
if(!empty($optionGroup->option_value)){
    $option_values = explode(',', $optionGroup->option_value);
    $option_names = explode(',', $optionGroup->option_name);
}else{
    $option_values = [1];
    $option_names = [$question->formFields->text_info];
}
$options = array_combine ( $option_names , $option_values );
@endphp
<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <div class="row">
        <div class="col-10">
            @foreach ($options as $option_name => $option_value)
                <div class="custom-control custom-radio {{ ($optionGroup->option_layout == 'horizontal')? 'custom-control-inline':''}}">
                    <input type="radio" name="{{ $field_name }}"
                        onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
                        value="{{ $option_value }}" {{ $answer->answer == $option_value ? 'checked' : '' }}
                        class="custom-control-input {{ $fieldId }}">
                    <label class="custom-control-label" for="customCheck1">{{ $option_name }}</label>
                </div>
            @endforeach
        </div>
        <div class="col-1">@include('admin::forms.form_fields.info_popup', ['question'=>$question->question_text, 'text_info'=>$question->formFields->text_info])</div><div class="col-1">@include('admin::forms.form_fields.query_popup')</div>
    </div>

</div>
