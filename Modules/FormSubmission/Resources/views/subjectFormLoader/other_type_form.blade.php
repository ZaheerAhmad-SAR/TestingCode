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
'subject_id' => $subjectId,
'study_structures_id' => $phase->id,
'modility_id' => $step->modility_id,
];
$qcLockFromStatus = '';
$qcLockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getQCLockFormStatusArray);
if(null !== $qcLockFormStatusObj) {
    $qcLockFromStatus = $qcLockFormStatusObj->is_data_locked == 1 ? '<span class="" data-toggle="popover" data-trigger="hover" data-content="'.$qcLockFormStatusObj->is_data_locked_reason.'">
                                        <i class="fas fa-lock"></i>
                                    </span>' : '';
}
/*************** Form Lock *****************/
@endphp
    @if ($step->formType->form_type == 'Others')
        <a class="badge p-1 {{ $badgeCls }} m-1 applyCss_{{ $stepClsStr }} {{ $stepClsStr }}  {{ $skipLogicStepIdStr }} {{ $active_form }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase_id }}', '{{ $step->step_id }}', 'no');">
            {{ $step->formType->form_type . ' ' . $step->modility->modility_abbreviation }}
            @php
            echo
            \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step,
            $getFormQCStatusArray, true);
            @endphp
            
            {!! $qcLockFromStatus !!}
        </a>
        <br>
    @endif
@endif
