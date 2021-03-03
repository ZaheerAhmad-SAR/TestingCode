@php
$getGradingFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
'form_status' => 'complete',
];

$getAdjudicationFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'modility_id' => $step->modility_id,
'adjudication_status' => 'complete',
];

/*************** Form Lock *****************/
$getAdjudicationLockFormStatusArray = [
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'modility_id' => $step->modility_id,
];
$adjudicationLockFromStatus = 0;
$adjudicationLockFormStatusObj = \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatusObj($getAdjudicationLockFormStatusArray);
if(null !== $adjudicationLockFormStatusObj) {
    $adjudicationLockFromStatus = $adjudicationLockFormStatusObj->is_data_locked == 1 ? $adjudicationLockFormStatusObj->is_data_locked : 0;
}
/*************** Form Lock *****************/
@endphp
@if (canAdjudication(['index']))
    @if (($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') && \Modules\FormSubmission\Entities\FormStatus::isAllGradersGradedThatForm($step, $getGradingFormStatusArray))
        <a class="badge p-1 {{ $badgeCls }} m-1" href="javascript:void(0);"
            onclick="showSections('step_adjudication_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase_id }}', '{{ $step->step_id }}', 'yes');">
            Adj. {{ $step->modility->modility_abbreviation }}
            @php
            echo
            \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step,
            $getAdjudicationFormStatusArray, true);
            @endphp

            @if($adjudicationLockFromStatus == 1)
                <span class="" data-toggle="popover" data-trigger="hover" data-content="Form Data is Locked!">
                    <i class="fas fa-lock"> </i>
                </span>
            @endif
        </a>
        <br>
    @endif
@endif
