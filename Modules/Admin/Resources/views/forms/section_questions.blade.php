@if (count($section->questions))
    <div class="form card mb-1">
        <form name="form_master_{{ $sectionIdStr }}" id="form_master_{{ $sectionIdStr }}">
            @csrf
            <input type="hidden" name="studyId" value="{{ $studyId }}" />
            <input type="hidden" name="subjectId" value="{{ $subjectId }}" />
            <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
            <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
            <input type="hidden" name="formTypeId" value="{{ $step->form_type_id }}" />
            <input type="hidden" name="sectionId" value="{{ $section->id }}" />
        </form>
        <form class="card-body" method="POST" name="form_{{ $sectionIdStr }}" id="form_{{ $sectionIdStr }}"
            onsubmit="return submitMe(event, '{{ $sectionIdStr }}', '{{ $sectionClsStr }}', '{{ $stepIdStr }}');">
            <fieldset id="fieldset_{{$stepIdStr}}" class="{{ $sectionClsStr }}">
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
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Checkbox')
                    @include('admin::forms.form_fields.checkbox_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Dropdown')
                    @include('admin::forms.form_fields.dropdown_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Text')
                    @include('admin::forms.form_fields.text_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Textarea')
                    @include('admin::forms.form_fields.textarea_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Number')
                    @include('admin::forms.form_fields.number_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Date & Time')
                    @include('admin::forms.form_fields.datetime_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @elseif($question->form_field_type->field_type === 'Upload')
                    @include('admin::forms.form_fields.upload_field', ['question'=> $question, 'answer'=> $answer,
                    'sectionClsStr'=>$sectionClsStr, 'sectionIdStr'=>$sectionIdStr])
                @endif
            @endforeach
        </fieldset>
            @if ((bool) $subjectId)
            <div class="row">
                <div class="col-md-12">
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="custom-control-input" name="{{ buildSafeStr($section->id, 'edit_form_check_') }}"
                            id="{{ buildSafeStr($section->id, 'edit_form_check_') }}" onclick="showReasonField('{{ buildSafeStr($section->id, 'edit_form_check_') }}', '{{ buildSafeStr($section->id, 'edit_form_div_') }}', '{{ $sectionIdStr }}', '{{ $sectionClsStr }}');">
                        <label class="custom-control-label checkbox-primary" for="primary">I want to edit that form.</label>
                    </div>
                </div>
                <div class="col-md-12" id="{{ buildSafeStr($section->id, 'edit_form_div_') }}" style="display: none;">
                    <input type="text" name="edit_reason_text" id="edit_reason_text" class="form-control-ocap bg-transparent" value="" placeholder="Please put reason to edit form here"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            @endif

        </form>
    </div>
    @push('script_last')
        <script>
        $( document ).ready(function() {
            @if( $formStatus == 'complete')
            disableFieldByClass('{{ $sectionClsStr }}');
            @elseif((bool)session('already_one_form_is_resumable'))
            disableFieldByClass('{{ $sectionClsStr }}');
            disableFieldByClass('{{ "next_".$sectionClsStr }}');
            @else
            disableFieldByClass('{{ "next_".$sectionClsStr }}');
            disableFieldByClass('{{ $stepClsStr }}');
            enableFieldByClass('{{ $sectionClsStr }}');
            @php
            session(['already_one_form_is_resumable' => 1]);
            @endphp
            @endif
        });

        </script>
    @endpush
@endif
