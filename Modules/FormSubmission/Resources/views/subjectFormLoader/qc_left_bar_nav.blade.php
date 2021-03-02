@if (canQualityControl(['index']))
@php
$getFormQCStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
];

/*************** Form Lock *****************/
$getQCLockFormStatusArray = [
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'modility_id' => $step->modility_id,
];
$qcLockFromStatus = 0;
$qcLockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getQCLockFormStatusArray);
if(null !== $qcLockFormStatusObj) {
    $qcLockFromStatus = $qcLockFormStatusObj->is_data_locked == 1 ? $qcLockFormStatusObj->is_data_locked : 0;
}
/*************** Form Lock *****************/
@endphp
    @if ($step->formType->form_type == 'QC')
        <a class="badge p-1 {{ $badgeCls }} m-1  {{ $stepClsStr }}  {{ $skipLogicStepIdStr }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase_id }}', '{{ $step->step_id }}', 'no');">
            {{ $step->formType->form_type . ' ' . $step->modility->modility_abbreviation }}
            @php
            echo
            \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step,
            $getFormQCStatusArray, true);
            @endphp
            
            @if($qcLockFromStatus == 1)
                <span class="" data-toggle="popover" data-trigger="hover" data-content="Form Data is Locked!">
                    <i class="fas fa-lock"> </i>
                </span>
            @endif
        </a>
        <br>
    @endif
@endif
