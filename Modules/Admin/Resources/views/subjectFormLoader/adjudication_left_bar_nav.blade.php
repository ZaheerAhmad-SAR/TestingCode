@php
$getGradingFormStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
];

$getAdjudicationFormStatusArray = [
'form_adjudicated_by_id' => $form_filled_by_user_id,
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'modility_id' => $step->modility_id,
];
@endphp
@if (canAdjudication())
    @if ($step->form_type_id == 2 && \Modules\Admin\Entities\FormStatus::isAllGradersGradedThatForm($step, $getGradingFormStatusArray))
        <a class="badge p-1 badge-light m-1  {{ $stepClsStr }}" href="javascript:void(0);"
            onclick="showSections('step_adjudication_sections_{{ $stepIdStr }}');">
            Adjudication
            @php
            echo
            \Modules\Admin\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step,
            $getAdjudicationFormStatusArray, true);
            @endphp
        </a>
        <br>
    @endif
@endif
