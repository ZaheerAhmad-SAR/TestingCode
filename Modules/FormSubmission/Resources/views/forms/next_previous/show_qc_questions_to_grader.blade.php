@if($isPreview === false && $step->formType->form_type !== 'QC')
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="btn btn-success" onclick="openShowQuestionsToGraderPopUp('{{ $study->id }}', '{{ $subject->id }}', '{{ $phase->id }}', '{{ $step->step_id }}');">
    Show QC Form Questions</button>
@endif
