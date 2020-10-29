@php
/**************************************/
/**************************************/
/**************************************/
$stepIdStr = (isset($stepIdStr) && !empty($stepIdStr))? $stepIdStr:'';
$subjectId = (isset($subjectId) && !empty($subjectId))? $subjectId:'';
$studyId = (isset($studyId) && !empty($studyId))? $studyId:'';
$stepCounter = (isset($stepCounter) && !empty($stepCounter))? $stepCounter:0;
$form_filled_by_user_id = (isset($form_filled_by_user_id) && !empty($form_filled_by_user_id))? $form_filled_by_user_id:0;
/**************************************/
/**************************************/
/**************************************/

$showForm = false;
if ($step->form_type_id == 1 && canQualityControl(['index'])){
    $showForm = true;
}
if ($step->form_type_id == 2 && canGrading(['index'])){
    $showForm = true;
}
@endphp
@if($showForm == true)
<div class="all_step_sections step_sections_{{ $stepIdStr }}" style="display: {{ $firstStep ? 'block' : 'none' }};">
@php
$getFormStatusArray = [
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
    'form_type_id' => $step->form_type_id,
    'modility_id' => $step->modility_id,
];
if($step->form_type_id == 2){
    $getFormStatusArray['form_filled_by_user_id'] = $form_filled_by_user_id;
}
$formStatusObj = \Modules\Admin\Entities\FormStatus::getFormStatusObj($getFormStatusArray);
$formStatus = 'no_status';
$formFilledByUserId = 'no-user-id';
if(null !== $formStatusObj){
    $formStatus = $formStatusObj->form_status;
    $formFilledByUserId = $formStatusObj->form_filled_by_user_id;
}
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
                    <input type="hidden" name="modilityId" value="{{ $step->modility_id }}" />
                    <input type="hidden" name="formFilledByUserId" value="{{ $formFilledByUserId }}" />
                    <input type="hidden" name="formStatus" value="{{ $formStatus }}" />
                    <input type="hidden" class="form_hid_editing_status_{{ $stepIdStr }}" name="form_editing_status_{{ $stepIdStr }}"
                        id="form_editing_status_{{ $stepIdStr }}" value="{{ $formStatus == 'resumable' ? 'yes' : 'no' }}" />
                </form>
                <form class="" method="POST" name="form_{{ $stepIdStr }}" id="form_{{ $stepIdStr }}">
                        <div class="wizard wizard-white mb-4">
                            <ul class="nav nav-tabs d-block d-sm-flex">
                                @php
                                $form_filled_by_user_id = ($form_filled_by_user_id ?? '');
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
                                    'studyId' => $studyId,
                                    'studyClsStr' => $studyClsStr,
                                    'subjectId' => $subjectId,
                                    'phase' => $phase,
                                    'step' => $step,
                                    'section' => $section,
                                    'formStatusObj' => $formStatusObj,
                                    'formStatus' => $formStatus,
                                    'sectionIdStr' => $sectionIdStr,
                                    'sectionClsStr' => $sectionClsStr,
                                    'stepClsStr'=> $stepClsStr,
                                    'key' => $key,
                                    'first' => 0,
                                    'last' => $last,
                                    'getFormStatusArray'=>$getFormStatusArray
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
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@include('queries::queries.query_popup')
@push('script')
<script>
    function submitStepForm{{ $stepIdStr }}(stepIdStr, stepClsStr) {
        if (checkTermCond(stepIdStr)) {
            if (isFormInEditMode(stepIdStr)) {
                if (checkReason(stepIdStr) == false) {
                    stopJsHere();
                }
            }
            validateAndSubmitForm(stepIdStr, '{{ $formStatusObj->form_type_id }}', '{{ buildGradingStatusIdClsStr($formStatusObj->id) }}');
            reloadPage(2);
        }
    }
</script>
@endpush
@push('script_last')
    <script>
        $(document).ready(function() {
            @php
            if ($formStatusObj->form_status != 'complete') {
                echo "globalDisableByClass($stepCounter, '$studyClsStr', '$stepClsStr');";
            } else {
                echo "hideReasonField('$stepIdStr', '$stepClsStr', '$formStatusObj->form_type_id', '".buildGradingStatusIdClsStr($formStatusObj->id)."');";
            }
            @endphp
        });

    </script>
@endpush
@endif
