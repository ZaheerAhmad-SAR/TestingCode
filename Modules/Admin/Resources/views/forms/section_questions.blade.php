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
                @endphp

                @if ($question->form_field_type->field_type === 'Radio')
                    @include('admin::forms.form_fields.radio_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Checkbox')
                    @include('admin::forms.form_fields.checkbox_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Dropdown')
                    @include('admin::forms.form_fields.dropdown_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Text')
                    @include('admin::forms.form_fields.text_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Textarea')
                    @include('admin::forms.form_fields.textarea_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Number')
                    @include('admin::forms.form_fields.number_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Date & Time')
                    @include('admin::forms.form_fields.datetime_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Upload')
                    @include('admin::forms.form_fields.upload_field', ['question'=> $question, 'field_name'=> $field_name, 'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @endif
            @endforeach
    </div>
</fieldset>
@endif
