@php
$getGradingFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
];

$getAdjudicationFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'modility_id' => $step->modility_id,
];
@endphp
@if (canAdjudication(['index']))
    @if (($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') && \Modules\FormSubmission\Entities\FormStatus::isAllGradersGradedThatForm($step, $getGradingFormStatusArray))
        <a class="badge p-1 {{ $badgeCls }} m-1" href="javascript:void(0);"
            onclick="showSections('step_adjudication_sections_{{ $stepIdStr }}'); updateCurrentStepId('{{ $step->phase->id }}', '{{ $step->step_id }}', 'yes');">
            Adj. {{ $step->formType->form_type }} {{ $step->modility->modility_name }}
            @php
            echo
            \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step,
            $getAdjudicationFormStatusArray, true);
            @endphp
        </a>
        <br>
    @endif
@endif
