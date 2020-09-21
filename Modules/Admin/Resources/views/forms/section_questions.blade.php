@if(count($section->questions))
@php
$formNameStr = str_replace('-', '_', $section->id);
@endphp
<div class="form card mb-1">
    <form class="card-body" method="POST" name="form_{{ $formNameStr }}" id="form_{{ $formNameStr }}"
        onsubmit="return submitForm{{ $formNameStr }}(event);">
        @csrf
        <input type="hidden" name="study_id" value="{{ isset($studyId) ? $studyId:0 }}" />
        <input type="hidden" name="phase_id" value="{{ $phase->id }}" />
        <input type="hidden" name="step_id" value="{{ $step->step_id }}" />
        <input type="hidden" name="section_id" value="{{ $section->id }}" />
        @foreach ($section->questions as $question)
        @if($question->form_field_type->field_type === 'Radio')
        @include('admin::forms.radio_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Checkbox')
        @include('admin::forms.checkbox_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Dropdown')
        @include('admin::forms.dropdown_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Text')
        @include('admin::forms.text_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Textarea')
        @include('admin::forms.textarea_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Number')
        @include('admin::forms.number_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Date & Time')
        @include('admin::forms.datetime_field', ['question'=> $question])
        @elseif($question->form_field_type->field_type === 'Upload')
        @include('admin::forms.upload_field', ['question'=> $question])
        @endif
        @endforeach
        <div class="row">
            <div class="col-md-4 offset-md-8">
                <button type="submit" class="btn btn-success float-right">Submit</button>
            </div>
        </div>
    </form>
</div>
@section('script')
<script>
function submitForm{{$formNameStr}} (event) {
    event.preventDefault();
    //alert($("#form_{{$formNameStr}}").serialize());    
    $.ajax({
        url: "{{route('submitStudyPhaseStepQuestionForm')}}",
        type: 'POST',
        data: $("#form_{{$formNameStr}}").serialize(),
        success: function(response) {
            alert("Form Submit success");
            //$('#assignRolesToPhaseStepMainDiv').empty();
            //$("#assignRolesToPhaseStepMainDiv").html(response);
        }
    });
}
</script>
@endsection
@endif