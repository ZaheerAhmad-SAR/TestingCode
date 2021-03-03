@if (canGrading(['index']))
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
$getGraderLockFormStatusArray = [
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'modility_id' => $step->modility_id,
];
$graderLockFromStatus = 0;
$graderLockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getGraderLockFormStatusArray);
if(null !== $graderLockFormStatusObj) {
    $graderLockFromStatus = $graderLockFormStatusObj->is_data_locked == 1 ? $graderLockFormStatusObj->is_data_locked : 0;
}
/*************** Form Lock *****************/
@endphp
    @if ($step->formType->form_type == 'Grading')
        <a class="badge p-1 {{ $badgeCls }} m-1  {{ $stepClsStr }} {{ $skipLogicStepIdStr }} {{ $active_form }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase_id }}', '{{ $step->step_id }}', 'no');">
            {{ $step->formType->form_type . ' ' . $step->modility->modility_abbreviation }}
            @php
            echo \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getGradingFormStatusArray);
            @endphp

            @if($graderLockFromStatus == 1)
                <span class="" data-toggle="popover" data-trigger="hover" data-content="Form Data is Locked!">
                    <i class="fas fa-lock"> </i>
                </span>
            @endif
        </a>
        <br>
    @endif
@endif
