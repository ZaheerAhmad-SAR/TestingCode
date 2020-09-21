@if(count($section->questions))
@php
$formNameStr = str_replace('-', '_', $section->id);
@endphp
<div class="form card mb-1">
    <form class="card-body" method="POST" name="form_{{ $formNameStr }}" id="form_{{ $formNameStr }}"
        onsubmit="return submitForm{{ $formNameStr }}(event);">
        @csrf
        <input type="hidden" name="studyId" value="{{ isset($studyId) ? $studyId:0 }}" />
        <input type="hidden" name="subjectId" value="{{ isset($subjectId) ? $subjectId:0 }}" />
        <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
        <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
        <input type="hidden" name="sectionId" value="{{ $section->id }}" />
        @foreach ($section->questions as $question)
        @php
            $getAnswerArray = [
                'study_id'=>$studyId, 'subject_id'=>$subjectId,
                'study_structures_id'=>$phase->id, 'phase_steps_id'=>$step->step_id,
                'section_id'=>$section->id, 'question_id'=>$question->id, 'field_id'=>$question->formfields->id,
            ];
         $answer = $question->getAnswer($getAnswerArray);
        @endphp
        @if($question->form_field_type->field_type === 'Radio')
        @include('admin::forms.radio_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Checkbox')
        @include('admin::forms.checkbox_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Dropdown')
        @include('admin::forms.dropdown_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Text')
        @include('admin::forms.text_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Textarea')
        @include('admin::forms.textarea_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Number')
        @include('admin::forms.number_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Date & Time')
        @include('admin::forms.datetime_field', ['question'=> $question, 'answer'=> $answer])
        @elseif($question->form_field_type->field_type === 'Upload')
        @include('admin::forms.upload_field', ['question'=> $question, 'answer'=> $answer])
        @endif
        @endforeach
        @if((bool)$subjectId)
        <div class="row">
            <div class="col-md-4 offset-md-8">
                <button type="submit" class="btn btn-success float-right">Submit</button>
            </div>
        </div>
        @endif
    </form>
</div>
@push('script')
<script>
function submitForm{{$formNameStr}} (event) {
    event.preventDefault();
    $.ajax({
        url: "{{route('submitStudyPhaseStepQuestionForm')}}",
        type: 'POST',
        data: $("#form_{{$formNameStr}}").serialize(),
        success: function(response) {
            alert('Form submitted successfully!');
        }
    });
}
</script>
@endpush
@endif
