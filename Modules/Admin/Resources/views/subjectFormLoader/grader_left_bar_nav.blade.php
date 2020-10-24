@php
$getGradingFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
];
@endphp
@if (canGrading())
    @if ($step->form_type_id == 2)
        <a class="badge p-1 badge-light m-1  {{ $stepClsStr }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}');">
            {{ $step->formType->form_type . ' ' . $step->step_name }}
            @php
            echo \Modules\Admin\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getGradingFormStatusArray);
            @endphp
        </a>
        <br>
    @endif
@endif
