@if (canQualityControl(['index']))
@php
$getFormQCStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
];
@endphp
    @if ($step->form_type_id == 1)
        <a class="badge p-1 {{ $badgeCls }} m-1  {{ $stepClsStr }}  {{ $skipLogicStepIdStr }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase->id }}', '{{ $step->step_id }}', 'no');">
            {{ $step->formType->form_type . ' ' . $step->modility->modility_name }}
            @php
            echo
            \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step,
            $getFormQCStatusArray, true);
            @endphp
        </a>
        <br>
    @endif
@endif
