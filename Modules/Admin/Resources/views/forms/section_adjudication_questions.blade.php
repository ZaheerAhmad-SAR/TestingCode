@if (count($section->questions))
@php
$getGradersIdsArray = [
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
    'form_type_id' => $step->form_type_id,
];
$graderIdsArray = \Modules\Admin\Entities\FormStatus::getAllGraderIds($getGradersIdsArray);
@endphp
<fieldset id="fieldset_adjudication_{{ $stepIdStr }}" class="">
    <div class="card p-2 mb-1">
        <input type="hidden" name="sectionId[]" value="{{ $section->id }}" />
            @foreach ($section->questions as $question)
            @php
            $fieldType = $question->form_field_type->field_type;
            if (
                $fieldType === 'Radio' ||
                $fieldType === 'Checkbox' ||
                $fieldType === 'Dropdown' ||
                $fieldType === 'Upload' ||
                $fieldType === 'Date & Time'
            ){
                continue;
            }
            @endphp
            <div class="form-group">
                <label class="">{{ $question->question_text }}</label>
                <div class="row">
                    <div class="col-10">
            @foreach ($graderIdsArray as $graderId)
                @php
                $getAnswerArray = [
                    'form_filled_by_user_id'=>$graderId,
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
                $questionIdStr = buildSafeStr($question->id, '');
                $graderIdStr = buildSafeStr($graderId, '');

                $copyToFieldName = buildFormFieldName($question->formFields->variable_name);
                $copyToFieldId = $copyToFieldName . '_' . $questionIdStr;

                $grader_field_name = $copyToFieldName . '_' . $graderIdStr;
                $grader_field_id = $grader_field_name . '_' . $questionIdStr;

                @endphp

                @if($fieldType === 'Text')
                    @include('admin::forms.adjudication_form_fields.text_field', ['field_name'=> $grader_field_name, 'questionIdStr'=> $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer])
                @elseif($fieldType === 'Textarea')
                    @include('admin::forms.adjudication_form_fields.textarea_field', ['field_name'=> $grader_field_name, 'questionIdStr'=> $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer])
                @elseif($fieldType === 'Number')
                    @include('admin::forms.adjudication_form_fields.number_field', ['field_name'=> $grader_field_name, 'questionIdStr'=> $questionIdStr, 'copyToFieldId'=> $copyToFieldId, 'fieldId'=> $grader_field_id, 'answer'=> $answer])
                @endif

                @endforeach
            </div>
            <div class="col-1">@include('admin::forms.adjudication_form_fields.info_popup', ['question'=>$question])</div><div class="col-1">@include('admin::forms.adjudication_form_fields.query_popup')</div>
        </div>
    </div>
            @endforeach
    </div>
</fieldset>
@endif
