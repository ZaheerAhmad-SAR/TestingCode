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
                ];
                if($step->form_type_id == 2){
                    $getAnswerArray['form_filled_by_user_id'] = auth()->user()->id;
                }
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
                            @include('formsubmission::forms.form_fields.form_field_checks', ['fieldType'=>$fieldType, 'question'=> $question, 'field_name'=> $field_name,
                            'questionIdStr'=> $questionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer])
            </div>
            <div class="col-1">@include('formsubmission::forms.form_fields.info_popup', ['question'=>$question])</div>
            @php
            $queryParams = [
                    'study_id'=>$studyId,
                    'subject_id'=>$subjectId,
                    'study_structures_id'=>$phase->id,
                    'phase_steps_id'=>$step->step_id,
                    'section_id'=>$section->id,
                    'question_id'=>$question->id,
                    'field_id'=>$question->formfields->id,
                    'form_type_id'=>$step->form_type_id,
                    'modility_id'=>$step->modility_id,
                    'module'=>$step->formType->form_type.' Form',
            ];
            @endphp
            <div class="col-1">@include('formsubmission::forms.form_fields.query_popup', ['queryParams'=>$queryParams])</div>
        </div>
    </div>
            @endforeach
    </div>
</fieldset>
@endif
