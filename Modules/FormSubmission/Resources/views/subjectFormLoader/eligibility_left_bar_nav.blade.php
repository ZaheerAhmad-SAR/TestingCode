@if (canEligibility(['index']))
@php
$getGradingFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
'modility_id' => $step->modility_id,
];
/*************** Form Lock *****************/
$getEligibilityLockFormStatusArray = [
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'modility_id' => $step->modility_id,
];
$eligibilityLockFromStatus = 0;
$eligibilityLockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getEligibilityLockFormStatusArray);
if(null !== $eligibilityLockFormStatusObj) {
    $eligibilityLockFromStatus = $eligibilityLockFormStatusObj->is_data_locked == 1 ? $eligibilityLockFormStatusObj->is_data_locked : 0;
}
/*************** Form Lock *****************/
@endphp
    @if ($step->formType->form_type == 'Eligibility')
        <a class="badge p-1 {{ $badgeCls }} m-1  {{ $stepClsStr }} {{ $skipLogicStepIdStr }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase_id }}', '{{ $step->step_id }}', 'no');">
            {{ $step->formType->form_type . ' ' . $step->modility->modility_abbreviation }}
            @php
            echo \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getGradingFormStatusArray);
            @endphp

            @if($eligibilityLockFromStatus == 1)
                <span class="" data-toggle="popover" data-trigger="hover" data-content="Form Data is Locked!">
                    <i class="fas fa-lock"> </i>
                </span>
            @endif
        </a>
        <br>
    @endif
@endif
