@if (count($section->questions))
<fieldset id="fieldset_{{ $stepIdStr }}" class="{{ $studyClsStr }} {{ $stepClsStr }} {{ $sectionClsStr }}">
    <div class="card p-2 mb-1">
        <input type="hidden" name="sectionId[]" value="{{ $section->id }}" />
            @foreach ($section->questions as $question)
                @php
                $getAnswerArray = [
                'study_id'=>$studyId,
                'subject_id'=>$subjectId,
                'study_structures_id'=>$phase->id,
                'phase_steps_id'=>$step->step_id,
                'section_id'=>$section->id,
                'question_id'=>$question->id,
                'field_id'=>$question->formfields->id,
                'form_filled_by_user_id'=>auth()->user()->id,
                ];
                $answer = $question->getAnswer($getAnswerArray);

                $field_name = buildFormFieldName($question->formFields->variable_name);
                $questionIdStr = buildSafeStr($question->id, '');
                $fieldId = $field_name . '_' . $questionIdStr;
                $fieldType = $question->form_field_type->field_type;
                @endphp
                 <div class="form-group">
                    <label class="">{{ $question->question_text }}</label>
                    <div class="row">
                        <div class="col-10">

                @if ($fieldType === 'Radio')
                    @include('admin::forms.form_fields.radio_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Checkbox')
                    @include('admin::forms.form_fields.checkbox_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Dropdown')
                    @include('admin::forms.form_fields.dropdown_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Text')
                    @include('admin::forms.form_fields.text_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Textarea')
                    @include('admin::forms.form_fields.textarea_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Number')
                    @include('admin::forms.form_fields.number_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Date & Time')
                    @include('admin::forms.form_fields.datetime_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @elseif($fieldType === 'Upload')
                    @include('admin::forms.form_fields.upload_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
                @endif

            </div>
            <div class="col-1">@include('admin::forms.form_fields.info_popup', ['question'=>$question])</div><div class="col-1">@include('admin::forms.form_fields.query_popup')</div>
        </div>
    </div>
            @endforeach
    </div>
</fieldset>
@endif
