@php
/**************************************/
/**************************************/
/**************************************/
$transmissionNumber = \Modules\FormSubmission\Entities\SubjectsPhases::getTransmissionNumber($subjectId, $phase->id);
/**************************************/
/**************************************/
/**************************************/
$current_user_id = auth()->user()->id;

$showForm = false;
if ($step->formType->form_type == 'QC' && canQualityControl(['index'])){
    $showForm = true;
}
if ($step->formType->form_type == 'Grading' && canGrading(['index'])){
    $showForm = true;
}
if ($step->formType->form_type == 'Eligibility' && canEligibility(['index'])){
    $showForm = true;
}
if ($isPreview ===true){
    $stepIdStr = buildSafeStr(Request::segment(4), '');
}
@endphp
@if($showForm == true)
@php
$showStep = 'none';
if(
    (request('stepId', '-') == $step->step_id) &&
    (request('isAdjudication', 'no') == 'no')
    ){
    $showStep = 'block';
}
if(
    ($activeStep && request('stepId', '-') == '-') &&
    (request('isAdjudication', 'no') == 'no')
){
    $showStep = 'block';
}
@endphp

<div class="all_step_sections step_sections_{{ $stepIdStr }}" style="display: {{ $showStep }};">
@if($step->is_active == 1 || $isPreview === true)
@php
$getFormStatusArray = [
    'subject_id' => $subjectId,
    'study_id' => $studyId,
    'study_structures_id' => $phase->id,
    'phase_steps_id' => $step->step_id,
    'form_type_id' => $step->form_type_id,
    'modility_id' => $step->modility_id,
    'form_status' => 'complete',
];

$formStatusObjects = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObjArray($getFormStatusArray);
$numberOfAlreadyGradedPersons = count($formStatusObjects);

if($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility'){
    $getFormStatusArray['form_filled_by_user_id'] = $current_user_id;
}

$formStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getFormStatusArray);

$formStatus = 'no_status';
$formFilledByUserId = 'no-user-id';
$isFormDataLocked = 0;

if(null !== $formStatusObj) {
    $formStatus = $formStatusObj->form_status;
    $formFilledByUserId = $formStatusObj->form_filled_by_user_id;
    //$isFormDataLocked = $formStatusObj->is_data_locked;
}

/********* check form lock status ******************/
$getFormStatuslockArray= [
    'study_id' => $studyId,
    'subject_id' => $subjectId,
    'study_structures_id' => $phase->id,
    'modility_id' => $step->modility_id,
];
$formLockStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getFormStatuslockArray);
if(null !== $formLockStatusObj) {
    $isFormDataLocked = $formLockStatusObj->is_data_locked;
}
/********* check form lock status ******************/
@endphp
<div class="row">
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-body">
                {{--  --}}
                @php 
                    $check_if_form_graded_by_logged_user = [
                        'subject_id' => $subjectId,
                        'study_id' => $studyId,
                        'study_structures_id' => $phase->id,
                        'phase_steps_id' => $step->step_id,
                        'form_type_id' => $step->form_type_id,
                        'modility_id' => $step->modility_id,
                        'form_status' => 'complete',
                        'form_filled_by_user_id' => $current_user_id
                    ];
                @endphp
                @if(\Modules\FormSubmission\Entities\FormStatus::getFormStatusObjArray($check_if_form_graded_by_logged_user)->isEmpty() && $numberOfAlreadyGradedPersons >= $step->graders_number && $isPreview === false)
                    <div class="alert alert-danger" role="alert">
                        The current form has already been graded by required number of graders
                    </div>
                    {{-- just defined id to controll page from loading on same modality  --}}
                        <div id="form_master_{{ $stepIdStr }}"></div>
                        <div id="form_{{ $stepIdStr }}"></div>
                    {{-- just defined id to controll page from loading on same modality  --}}
                @else
                <form name="form_master_{{ $stepIdStr }}" id="form_master_{{ $stepIdStr }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="studyId" value="{{ $studyId }}" />
                    <input type="hidden" name="subjectId" value="{{ $subjectId }}" />
                    <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
                    <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
                    <input type="hidden" name="formTypeId" value="{{ $step->form_type_id }}" />
                    <input type="hidden" name="formType" value="{{ $step->formType->form_type }}" />
                    <input type="hidden" name="numberOfGraders" value="{{ $step->graders_number }}" />
                    <input type="hidden" name="numberOfAlreadyGradedPersons" value="{{ $numberOfAlreadyGradedPersons }}" />
                    <input type="hidden" name="modilityId" value="{{ $step->modility_id }}" />
                    <input type="hidden" name="formFilledByUserId" value="{{ $formFilledByUserId }}" />
                    <input type="hidden" name="isFormDataLocked" value="{{ $isFormDataLocked }}" />
                    <input type="hidden" name="formStatus" value="{{ $formStatus }}" />
                    <input type="hidden" class="form_hid_editing_status_{{ $stepIdStr }}" name="form_editing_status_{{ $stepIdStr }}"
                        id="form_editing_status_{{ $stepIdStr }}" value="{{ $formStatus == 'resumable' ? 'yes' : 'no' }}" />
                </form>
                <form class="" method="POST" name="form_{{ $stepIdStr }}" id="form_{{ $stepIdStr }}" enctype="multipart/form-data">
                        <div class="wizard wizard-white mb-4">
                            <ul class="nav nav-tabs d-block d-sm-flex">
                                @php
                                $current_user_id = ($current_user_id ?? '');
                                $subjectId = ($subjectId ?? '');
                                $studyId = ($studyId ?? '');
                                $activeSection = true;
                                $liBackground = '';
                                $number = 0;
                                @endphp
                                @foreach ($sections as $section)
                                @php
                                $sectionClsStr = buildSafeStr($section->id, 'section_cls_');
                                $showSection = '';
                                if(request('sectionId', '-') == $section->id){
                                    $showSection = 'active';
                                }
                                if($activeSection && request('sectionId', '-') == '-'){
                                    $showSection = 'active';
                                }
                                if(substr($section->name, -2) =='OD'){
                                    $liBackground = 'li-even';
                                }else if(substr($section->name, -2) =='OS'){
                                    $liBackground = 'li-odd';
                                }else{
                                    $liBackground = 'no-class';
                                }
                                @endphp
                                    <li class="nav-item mr-auto mb-1 {{ $liBackground }}">
                                        <a class="nav-link p-0 {{ $stepClsStr }} {{ $sectionClsStr }} {{ $showSection }} {{ $activeSection ? 'first_navlink_' . $stepIdStr : '' }}"
                                        data-toggle="tab"
                                        href="#tab{{ $section->id }}"
                                        onclick="updateCurrentSectionId('{{ $section->step->phase_id }}', '{{ $section->step->step_id }}', '{{ $section->id }}');">
                                        <span class="mb-0 text-uppercase " data-toggle="tooltip" data-placement="bottom" title="{{ $section->description }}" > {{ $section->sort_number }}. {{ $section->name }}
                                        </span>
                                        @if(!empty($transmissionNumber))
                                          <br>
                                          <span class="text text-danger">Transmission Number : {{ $transmissionNumber }}</span>
                                        @endif
                                        </a>
                                    </li>&nbsp;
                                    @php
                                    $activeSection = false;
                                    $number++;
                                    @endphp
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @php
                                $activeSection = true;
                                $last = count($sections)-1;
                                @endphp
                                @foreach ($sections as $key => $section)
                                    @php
                                    $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                                    $skipLogicSectionIdStr = buildSafeStr($section->id, 'skip_logic_');
                                    $sectionIdStr = buildSafeStr($section->id, '');
                                    $sharedData = [
                                    'studyId' => $studyId,
                                    'subjectId' => $subjectId,
                                    'phase' => $phase,
                                    'step' => $step,
                                    'section' => $section,
                                    'formStatusObj' => $formStatusObj,
                                    'formStatus' => $formStatus,
                                    'sectionIdStr' => $sectionIdStr,
                                    'sectionClsStr' => $sectionClsStr,
                                    'skipLogicSectionIdStr' => $skipLogicSectionIdStr,
                                    'stepClsStr'=> $stepClsStr,
                                    'skipLogicStepIdStr'=> $skipLogicStepIdStr,
                                    'key' => $key,
                                    'first' => 0,
                                    'last' => $last,
                                    'getFormStatusArray'=>$getFormStatusArray
                                    ];

                                    $showSection = '';
                                    if(request('sectionId', '-') == $section->id){
                                        $showSection = 'active show';
                                    }
                                    if($activeSection){
                                        $showSection = 'active show';
                                    }
                                    @endphp
                                    <div class="tab-pane tab-pane_{{ $stepIdStr }} fade {{ $activeSection ? 'first_tab_' . $stepIdStr : '' }} {{ $showSection }}"
                                        id="tab{{ $section->id }}">
                                        @include('formsubmission::forms.section_questions', $sharedData )
                                        @include('formsubmission::forms.section_next_previous', $sharedData)
                                    </div>
                                    @php
                                    $activeSection = false;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-danger" role="alert">Form is in draft mode</div>
@endif
</div>


@if($step->is_active == 1 || $isPreview === true)
@push('script')
<script>
    function submitStepForm{{ $stepIdStr }}(stepIdStr, stepClsStr) {
        if (checkTermCond(stepIdStr)) {
            if (isFormInEditMode(stepIdStr)) {
                if (checkReason(stepIdStr) == false) {
                    stopJsHere();
                }
            }
            validateAndSubmitForm(stepIdStr, '{{ $step->formType->form_type }}', '{{ buildGradingStatusIdClsStr($formStatusObj->id) }}');
        }
    }
</script>
@endpush
@push('script_last')
    <script>
        $(document).ready(function() {
            @php
            if ($formStatusObj->form_status != 'complete') {
                if(empty(session('stepToActivateStr'))){
                    session(['stepToActivateStr' => $stepClsStr]);
                    echo "enableByClass('$stepClsStr');";
                }
            } else {
                echo "hideReasonField('".$stepIdStr."', '".$stepClsStr."', '".$step->formType->form_type."', '".buildGradingStatusIdClsStr($formStatusObj->id)."', 2);";
            }
            @endphp
        });

    </script>
@endpush

@php
$stepValidationStr = Modules\Admin\Entities\PhaseSteps::generateJSFormValidationForStep($step, false);
$stepCalculatedFunctionsStr = Modules\Admin\Entities\PhaseSteps::generateCalculatedFieldsJSFunctions($step);
$runStepCalculatedFunctionsStr = Modules\Admin\Entities\PhaseSteps::runCalculatedFieldsJSFunctions($step);
@endphp
@push('script')
    <script>
        function validateStep{{$stepIdStr}}() {
            var isFormValid = true;
            {!! $stepValidationStr !!}
            return isFormValid;
        }

        {!! $stepCalculatedFunctionsStr !!}

        function runCalculatedFieldsFunctions{{$stepIdStr}}(triggeringQuestionIdStr) {
            if(isPreview === false){
                {!! $runStepCalculatedFunctionsStr !!}
            }
        }
    </script>
@endpush
@endif
@endif
