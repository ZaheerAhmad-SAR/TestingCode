<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <div class="row">
        <div class="col-10">
            <textarea name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
                class="form-control-ocap bg-transparent">{{ $answer->answer }}</textarea>
        </div>
        <div class="col-1">@include('admin::forms.form_fields.info_popup', ['question'=>$question->question_text, 'text_info'=>$question->formFields->text_info])</div><div class="col-1">@include('admin::forms.form_fields.query_popup')</div>
    </div>

</div>
