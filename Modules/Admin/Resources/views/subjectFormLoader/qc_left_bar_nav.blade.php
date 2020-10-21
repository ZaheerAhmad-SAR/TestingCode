@php
$getFormQCStatusArray = [
'subject_id' => $subjectId,
'study_id' => $studyId,
'study_structures_id' => $phase->id,
'phase_steps_id' => $step->step_id,
'form_type_id' => $step->form_type_id,
'form_filled_by_user_id' => $form_filled_by_user_id,
'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
];
@endphp
@if (canQualityControl())
    @if ($step->form_type_id == 1)
        <a class="badge p-1 badge-light m-1  {{ $stepClsStr }}" href="javascript:void(0);"
            onclick="showSections('step_sections_{{ $stepIdStr }}');">
            {{ $step->formType->form_type . ' ' . $step->step_name }}
            @php
            echo
            \Modules\Admin\Entities\FormStatus::getFormStatus($step,
            $getFormQCStatusArray, true);
            @endphp
        </a>
        <br>
    @endif
@endif
