@if (canOtherForm(['index']))
@php
$getFormQCStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
];

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
        </a>
        <br>
    @endif
@endif
