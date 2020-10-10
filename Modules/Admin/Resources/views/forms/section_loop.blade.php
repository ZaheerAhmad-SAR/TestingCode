@php
$getFormStatusArray = [
    'form_filled_by_user_id' => $form_filled_by_user_id,
    'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
];
$formStatusObj = \Modules\Admin\Entities\FormStatus::getFormStatusObj($getFormStatusArray);
$formStatus = (null !== $formStatusObj)? $formStatusObj->form_status:'no_status';
@endphp
<div class="row">
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form name="form_master_{{ $stepIdStr }}" id="form_master_{{ $stepIdStr }}">
                    @csrf
                    <input type="hidden" name="studyId" value="{{ $studyId }}" />
                    <input type="hidden" name="subjectId" value="{{ $subjectId }}" />
                    <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
                    <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
                    <input type="hidden" name="formTypeId" value="{{ $step->form_type_id }}" />
                    <input type="hidden" class="form_hid_editing_status_{{ $stepIdStr }}" name="form_editing_status_{{ $stepIdStr }}"
                        id="form_editing_status_{{ $stepIdStr }}" value="{{ $formStatus == 'resumable' ? 'yes' : 'no' }}" />
                    <input type="hidden" class="form_hid_status_{{ $stepIdStr }}" name="form_status"
                        id="form_hid_status_{{ $stepIdStr }}" value="{{ $formStatus }}" />

                </form>
                <form class="" method="POST" name="form_{{ $stepIdStr }}" id="form_{{ $stepIdStr }}">
                    <fieldset id="fieldset_{{ $stepIdStr }}" class="{{ $studyClsStr }} {{ $stepClsStr }}">
                        <div class="wizard wizard-white mb-4">
                            <ul class="nav nav-tabs d-block d-sm-flex">
                                @php
                                $form_filled_by_user_id = ($form_filled_by_user_id ?? '');
                                $form_filled_by_user_role_id = ($form_filled_by_user_role_id ?? '');
                                $subjectId = ($subjectId ?? '');
                                $studyId = ($studyId ?? '');
                                $studyClsStr = ($studyClsStr ?? '');

                                $stepIdStr = buildSafeStr($step->step_id, '');
                                $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                $firstSection = true;
                                @endphp
                                @foreach ($sections as $section)
                                @php
                                $sectionClsStr = buildSafeStr($section->id, 'section_cls_');
                                @endphp
                                    <li class="nav-item mr-auto mb-4">
                                        <a class="nav-link p-0
                                    {{ $studyClsStr ?? '' }}
                                    {{ $stepClsStr }}
                                    {{ $sectionClsStr }}
                                    {{ $firstSection ? 'active' : '' }}
                                    {{ $firstSection ? 'first_navlink_' . $stepIdStr : '' }}" data-toggle="tab"
                                            href="#tab{{ $section->id }}">
                                            <div class="d-flex">
                                                <div class="mr-3 mb-0 h1">{{ $section->sort_number }}</div>
                                                <div class="media-body align-self-center">
                                                    <h6
                                                        class="mb-0 text-uppercase font-weight-bold">
                                                        {{ $section->name }}
                                                    </h6>
                                                    {{ $section->description }}
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @php
                                    $firstSection = false;
                                    @endphp
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @php
                                $firstSection = true;
                                $last = count($sections)-1;
                                @endphp
                                @foreach ($sections as $key => $section)
                                    @php
                                    $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                                    $sectionIdStr = buildSafeStr($section->id, '');
                                    $sharedData = [
                                    'studyId' => $studyId, 'studyClsStr' => $studyClsStr, 'subjectId' => $subjectId,
                                    'phase'
                                    => $phase,
                                    'step' => $step, 'section' => $section, 'formStatusObj' => $formStatusObj,
                                    'formStatus' => $formStatus, 'sectionIdStr' => $sectionIdStr,
                                    'sectionClsStr' => $sectionClsStr, 'stepClsStr'=> $stepClsStr,
                                    'key' => $key, 'first' => 0, 'last' => $last
                                    ];

                                    @endphp
                                    <div class="tab-pane tab-pane_{{ $stepIdStr }} fade {{ $firstSection ? 'first_tab_' . $stepIdStr : '' }} {{ $firstSection ? 'active show' : '' }}"
                                        id="tab{{ $section->id }}">
                                        @include('admin::forms.section_questions', $sharedData )
                                        @include('admin::forms.section_next_previous', $sharedData)
                                    </div>
                                    @php
                                    $firstSection = false;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@include('queries::queries.query_popup')
@push('script')
<script>
    function submitStepForms{{ $stepIdStr }}(stepIdStr, stepClsStr) {
        if (checkTermCond(stepIdStr)) {
                if (isFormInEditMode(stepIdStr)) {
                    if (checkReason(stepIdStr) === false) {
                        stopJsHere();
                    }
                }
                validateAndSubmitForm(stepIdStr);
            reloadPage();
            //hideReasonField(stepIdStr, stepClsStr);
        }
    }
</script>
@endpush

@push('script_last')
    <script>
        $(document).ready(function() {
            @php
            if ($formStatusObj->form_status != 'complete') {
                echo "globalDisableByClass('$studyClsStr', '$stepClsStr');";
            } else {
                echo "hideReasonField('$stepIdStr', '$stepClsStr');";
            }
            @endphp
        });

    </script>
@endpush
