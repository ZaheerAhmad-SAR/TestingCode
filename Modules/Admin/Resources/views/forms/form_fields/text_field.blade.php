<div class="form-group">
    <label class="">{{ $question->question_text }}</label>
    <div class="row">
        <div class="col-11">
            <input type="text" name="{{ $field_name }}" id="{{ $fieldId }}"
                onchange="validateAndSubmitField('{{ $stepIdStr }}', '{{ $sectionIdStr }}', '{{ $question->id }}', '{{ $field_name }}', '{{ $fieldId }}');"
                value="{{ $answer->answer }}" class="form-control-ocap bg-transparent">
        </div>
        <div class="col-1">@include('admin::forms.form_fields.query_popup')</div>
    </div>
    <small class="form-text">{{ $question->formFields->text_info }}</small>
</div>
