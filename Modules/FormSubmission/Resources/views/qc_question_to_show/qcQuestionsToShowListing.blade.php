@foreach ($qcStep->sections as $section)
<div class="tab-content">
    {{ $section->sort_number }} {{ $section->name }}
        @php
        $questions = Modules\Admin\Entities\Question::where('section_id', 'like', $section->id)->where('is_show_to_grader','like', 'yes')->get();
        @endphp
        @foreach ($questions as $question)
            @php
            $getAnswerArray = [
            'study_id'=>$studyId,
            'subject_id'=>$subjectId,
            'study_structures_id'=>$phaseId,
            'phase_steps_id'=>$qcStep->step_id,
            'section_id'=>$section->id,
            'question_id'=>$question->id,
            'field_id'=>$question->formfields->id,
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
                    <div class="col-12">
                        @include('formsubmission::print.print_form_fields.print_form_field_checks',
                        ['fieldType'=>$fieldType, 'question'=> $question, 'field_name'=> $field_name,
                        'questionIdStr'=> $questionIdStr, 'fieldId'=>
                        $fieldId, 'answer'=> $answer])
                    </div>

                </div>
            </div>
        @endforeach
        <hr>
@endforeach
</div>
<div class="modal-footer">
    <button class="btn btn-outline-danger" data-dismiss="modal" id="addQuestionCommentFormClose">
        <i class="fa fa-window-close" aria-hidden="true"></i> Close
    </button>
</div>
