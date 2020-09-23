@if (count($section->questions))
    @php
    $formNameStr = buildFormName($section->id);
    @endphp
    <div class="form card mb-1">
        <form name="form_master_{{ $formNameStr }}" id="form_master_{{ $formNameStr }}">
            @csrf
            <input type="hidden" name="studyId" value="{{ isset($studyId) ? $studyId : 0 }}" />
            <input type="hidden" name="subjectId" value="{{ isset($subjectId) ? $subjectId : 0 }}" />
            <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
            <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
            <input type="hidden" name="formTypeId" value="{{ $step->form_type }}" />
            <input type="hidden" name="sectionId" value="{{ $section->id }}" />
        </form>
        <form class="card-body" method="POST" name="form_{{ $formNameStr }}" id="form_{{ $formNameStr }}"
            onsubmit="return submitForm{{ $formNameStr }}(event);">
            @foreach ($section->questions as $question)
                @php
                $getAnswerArray = [
                'study_id'=>$studyId, 'subject_id'=>$subjectId,
                'study_structures_id'=>$phase->id, 'phase_steps_id'=>$step->step_id,
                'section_id'=>$section->id, 'question_id'=>$question->id, 'field_id'=>$question->formfields->id,
                ];
                $answer = $question->getAnswer($getAnswerArray);
                @endphp
                @if ($question->form_field_type->field_type === 'Radio')
                    @include('admin::forms.form_fields.radio_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Checkbox')
                    @include('admin::forms.form_fields.checkbox_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Dropdown')
                    @include('admin::forms.form_fields.dropdown_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Text')
                    @include('admin::forms.form_fields.text_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Textarea')
                    @include('admin::forms.form_fields.textarea_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Number')
                    @include('admin::forms.form_fields.number_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Date & Time')
                    @include('admin::forms.form_fields.datetime_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @elseif($question->form_field_type->field_type === 'Upload')
                    @include('admin::forms.form_fields.upload_field', ['question'=> $question, 'answer'=> $answer,
                    'formNameStr'=>$formNameStr])
                @endif
            @endforeach
            @if ((bool) $subjectId)
                <div class="row">
                    <div class="col-md-6 offset-md-4">
                        <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" name="terms_cond_{{$formNameStr}}" id="terms_cond_{{$formNameStr}}">
                            <label class="custom-control-label checkbox-primary" for="primary">I accept terms and conditions</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success float-right">Submit</button>
                    </div>
                </div>
            @endif
        </form>
    </div>
    @push('script')
        <script>
            function submitForm{{$formNameStr}}(event) {
                event.preventDefault();
                if($('#terms_cond_{{$formNameStr}}').prop('checked')){
                    var frmData = $("#form_master_{{ $formNameStr }}").serialize()+'&'+$("#form_{{ $formNameStr }}").serialize();
                    submitRequest(frmData);
                }else{alert('Please accept terms!')}
            }

            function submitFormField{{$formNameStr}}(field_name) {
                var frmData = $("#form_master_{{ $formNameStr }}").serialize();
                var field_val;
                if ($('input[name="' + field_name + '"]').attr('type') == 'radio'){
                    field_val = $('input[name="' + field_name + '"]:checked').val();
                }
                else{
                    field_val = $('input[name="' + field_name + '"]').val();
                }

                frmData = frmData + '&' + field_name + '=' + field_val;
                submitRequest(frmData);
            }
            function submitRequest(frmData){
                $.ajax({
                    url: "{{ route('submitStudyPhaseStepQuestionForm') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {

                    }
                });
            }

        </script>
    @endpush
@endif
