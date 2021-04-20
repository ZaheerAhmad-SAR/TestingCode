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
'subject_id' => $subjectId,
'study_structures_id' => $phase->id,
'modility_id' => $step->modility_id,
];
$eligibilityLockFromStatus = '';
$eligibilityLockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getEligibilityLockFormStatusArray);
if(null !== $eligibilityLockFormStatusObj) {
    $eligibilityLockFromStatus = $eligibilityLockFormStatusObj->is_data_locked == 1 ? '<span class="" data-toggle="popover" data-trigger="hover" data-content="'.$eligibilityLockFormStatusObj->is_data_locked_reason.'">
                                        <i class="fas fa-lock"></i>
                                    </span>' : '';
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

            {!! $eligibilityLockFromStatus !!}
        </a>
        <br>
    @endif
@endif
