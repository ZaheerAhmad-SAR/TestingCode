@if($step->is_active == 1 || $isPreview === true)
@if (count($section->questions))
<fieldset class="{{ $studyClsStr }} {{ $stepClsStr }} {{ $skipLogicStepIdStr }} {{ $skipLogicSectionIdStr }} {{ $sectionClsStr }}">
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
                $skipLogicQuestionIdStr = buildSafeStr($question->id, 'skip_logic_');

                $fieldId = $field_name . '_' . $questionIdStr;
                $fieldType = $question->form_field_type->field_type;
                $is_required = ($question->formFields->is_required == 'yes')? 'required':'';
                $is_required_star = ($question->formFields->is_required == 'yes')? '<span class="text text-danger">*</span>':'';
                $showOrHide = ($answer->answer == '-9999')? 'display:none;':'';
                @endphp
                 <div class="form-group" id="question_row_{{$questionIdStr}}" style="{{ $showOrHide }}">
                 <label class="">{{ $question->question_text }} {!! $is_required_star !!}</label>
                    <div class="row">
                        <div class="col-10">
                            @include('formsubmission::forms.form_fields.form_field_checks', ['fieldType'=>$fieldType, 'question'=> $question, 'field_name'=> $field_name,
                            'questionIdStr'=> $questionIdStr, 'skipLogicQuestionIdStr'=>$skipLogicQuestionIdStr, 'fieldId'=> $fieldId, 'answer'=> $answer, 'is_required'=> $is_required])
            </div>
            <div class="col-1">@include('formsubmission::forms.form_fields.info_popup', ['fieldType'=>$fieldType, 'question'=>$question])</div>
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
            @if($isPreview === false)
            <div class="col-1">@include('formsubmission::forms.form_fields.query_popup', ['fieldType'=>$fieldType, 'queryParams'=>$queryParams])</div>
            @endif
        </div>
    </div>
            @endforeach
    </div>
</fieldset>
@endif
@else
<div class="alert alert-danger" role="alert">Form is in draft mode</div>
@endif
