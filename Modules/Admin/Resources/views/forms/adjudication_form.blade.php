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

$dataArray = [
    'studyId'=>$studyId,
    'studyClsStr'=>$studyClsStr,
    'subjectId'=>$subjectId,
    'phase'=>$phase,
    'step'=>$step,
    'sections'=> $sections,
    'phaseIdStr'=>$phaseIdStr,
    'form_filled_by_user_id' => $form_filled_by_user_id,
    'firstStep' => $firstStep,
    'stepClsStr' => $stepClsStr,
    'stepIdStr' => $stepIdStr,
];

@endphp
@if (canAdjudication())
    @if ($step->form_type_id == 2 && \Modules\Admin\Entities\FormStatus::isAllGradersGradedThatForm($step, $getGradingFormStatusArray))
        @include('admin::forms.adjudication_section_loop', $dataArray)
    @endif
@endif
