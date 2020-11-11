@if($step->is_active == 1)
@php
$getAdjudicationFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'modility_id' => $step->modility_id,
];
$adjudicationFormStatus = \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step,
$getAdjudicationFormStatusArray);
@endphp
@if ($key == 0 && $first == 0 && $last == 0)
    @include('formsubmission::forms.next_previous.edit_form')
    @include('formsubmission::forms.next_previous.submit_form')
@elseif ($key == $first)
    <div class="d-flex">
        <button type="button"
            class="btn btn-primary nexttab ml-auto">Next</button>
    </div>
    @include('formsubmission::forms.next_previous.edit_form')
@elseif($key == $last)
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
    </div>
    @include('formsubmission::forms.next_previous.submit_form')
@else
    <div class="d-flex">
        <button type="button" class="btn btn-primary prevtab">Previous</button>
        <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
    </div>
@endif
@endif
