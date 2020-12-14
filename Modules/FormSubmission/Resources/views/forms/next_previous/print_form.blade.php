@if($isPreview === false && $formStatus == 'complete')
@if(canManageData(['index', 'create', 'store', 'edit', 'update']))
@php
$getGradersIdsArray = [
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
    'form_type_id' => $step->form_type_id,
];
$graderIdsArray = \Modules\FormSubmission\Entities\FormStatus::getAllGraderIds($getGradersIdsArray);
@endphp
@foreach ($graderIdsArray as $graderId)
@php
$grader = \App\User::find($graderId);
@endphp
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="btn btn-info" href="{{ url('/') }}/printForm/{{ $study->id }}/{{ $subject->id }}/{{ $phase->id }}/{{ $step->step_id }}/{{ $graderId }}" target="_blank">
    Print Form ({{ $grader->name }})
</a>
@endforeach
@endif
@endif
