@if (canAdjudication(['index']))
@php
$showStep = 'none';
if(
    (request('stepId', '-') == $step->step_id) &&
    (request('isAdjudication', 'no') == 'yes')
    ){
    $showStep = 'block';
}
if(
    ($activeStep && request('stepId', '-') == '-') &&
    (request('isAdjudication', 'no') == 'yes')
){
    $showStep = 'block';
}
@endphp
<div class="all_step_sections step_adjudication_sections_{{ $stepIdStr }}"
        style="display: {{ $showStep }};">
        @php
        $current_user_id = ($current_user_id ?? '');
        $subjectId = ($subjectId ?? '');
        $studyId = ($studyId ?? '');

        $getAdjudicationFormStatusArray = [
        //'form_adjudicated_by_id' => $current_user_id,
        'subject_id' => $subjectId,
        'study_id' => $studyId,
        'study_structures_id' => $phase->id,
        'phase_steps_id' => $step->step_id,
        'modility_id' => $step->modility_id,
        ];
        $adjudicationFormStatusObj =
        \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatusObj($getAdjudicationFormStatusArray);
        $adjudicationFormStatus = (null !== $adjudicationFormStatusObj)?
        $adjudicationFormStatusObj->adjudication_status:'no_status';

        /********* check form lock status ******************/
        $getFormStatuslockArray= [
            'study_id' => $studyId,
            'study_structures_id' => $phase->id,
            'modility_id' => $step->modility_id,
        ];
        $isFormDataLocked = 0;
        $formLockStatusObj = \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatusObj($getFormStatuslockArray);
        if(null !== $formLockStatusObj) {
            $isFormDataLocked = $formLockStatusObj->is_data_locked;
        }
        /********* check form lock status ******************/

        @endphp
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        @php 
                            $check_if_form_graded_by_logged_user = [
                                'subject_id' => $subjectId,
                                'study_id' => $studyId,
                                'study_structures_id' => $phase->id,
                                'phase_steps_id' => $step->step_id,
                                'form_type_id' => $step->form_type_id,
                                'modility_id' => $step->modility_id,
                                'adjudication_status' => 'complete',
                                'form_adjudicated_by_id' => $current_user_id
                            ];
                            $check_form_graded_already = [
                                'subject_id' => $subjectId,
                                'study_id' => $studyId,
                                'study_structures_id' => $phase->id,
                                'phase_steps_id' => $step->step_id,
                                'form_type_id' => $step->form_type_id,
                                'modility_id' => $step->modility_id,
                                'adjudication_status' => 'complete',
                            ];
                            $count_of_form_graded_already = count(\Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatusObjArray($check_form_graded_already));
                        @endphp
                        
                        @if(\Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatusObjArray($check_if_form_graded_by_logged_user)->isEmpty() && $count_of_form_graded_already >=1)
                            <div class="alert alert-danger" role="alert">
                                The current form has already been graded by required number of graders
                            </div>
                            {{-- just defined id to controll page from loading on same modality  --}}
                                <div id="adjudication_form_master_{{ $stepIdStr }}"></div>
                                <div id="adjudication_form_{{ $stepIdStr }}"></div>
                            {{-- just defined id to controll page from loading on same modality  --}}
                        @else
                        <form name="adjudication_form_master_{{ $stepIdStr }}"
                            id="adjudication_form_master_{{ $stepIdStr }}">
                            @csrf
                            <input type="hidden" name="studyId" value="{{ $studyId }}" />
                            <input type="hidden" name="subjectId" value="{{ $subjectId }}" />
                            <input type="hidden" name="phaseId" value="{{ $phase->id }}" />
                            <input type="hidden" name="stepId" value="{{ $step->step_id }}" />
                            <input type="hidden" name="formTypeId" value="{{ $step->form_type_id }}" />
                            <input type="hidden" name="formType" value="{{ $step->formType->form_type }}" />
                            <input type="hidden" name="modilityId" value="{{ $step->modility_id }}" />
                            <input type="hidden" name="isFormDataLocked" value="{{ $isFormDataLocked }}" />
                            <input type="hidden" name="form_adjudicated_by_id" value="{{ $adjudicationFormStatusObj->form_adjudicated_by_id }}" />
                            <input type="hidden" name="adjudication_status" value="{{ $adjudicationFormStatusObj->adjudication_status }}" />
                            <input type="hidden" class="adjudication_form_hid_editing_status_{{ $stepIdStr }}"
                                name="adjudication_form_editing_status_{{ $stepIdStr }}"
                                id="adjudication_form_editing_status_{{ $stepIdStr }}"
                                value="{{ $adjudicationFormStatus == 'resumable' ? 'yes' : 'no' }}" />
                        </form>
                        <form class="" method="POST" name="adjudication_form_{{ $stepIdStr }}"
                            id="adjudication_form_{{ $stepIdStr }}">
                            <div class="wizard wizard-white mb-4">
                                <ul class="nav nav-tabs d-block d-sm-flex">
                                    @php
                                    $stepIdStr = buildSafeStr($step->step_id, '');
                                    $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
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
                                            <a class="nav-link p-0 {{ $showSection }} {{ $activeSection ? 'first_navlink_' . $stepIdStr : '' }}" data-toggle="tab"
                                                href="#adjudication_tab_{{ $section->id }}">
                                                <span class="mb-0 text-uppercase " data-toggle="tooltip" data-placement="bottom" title="{{ $section->description }}" > {{ $section->sort_number }}. {{ $section->name }}
                                                </span>
                                            </a>
                                        </li>
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
                                        $sectionIdStr = buildSafeStr($section->id, '');
                                        $skipLogicSectionIdStr = buildSafeStr($section->id, 'skip_logic_');
                                        $sharedData = [
                                        'studyId' => $studyId,
                                        'subjectId' => $subjectId,
                                        'phase' => $phase,
                                        'step' => $step,
                                        'section' => $section,
                                        'adjudicationFormStatusObj' => $adjudicationFormStatusObj,
                                        'adjudicationFormStatus' => $adjudicationFormStatus,
                                        'sectionIdStr' => $sectionIdStr,
                                        'sectionClsStr' => $sectionClsStr,
                                        'stepClsStr'=> $stepClsStr,
                                        'skipLogicSectionIdStr'=> $skipLogicSectionIdStr,
                                        'skipLogicStepIdStr'=> $skipLogicStepIdStr,
                                        'key' => $key,
                                        'first' => 0,
                                        'last' => $last,
                                        'getAdjudicationFormStatusArray'=>$getAdjudicationFormStatusArray
                                        ];

                                        $showSection = '';
                                        if(request('sectionId', '-') == $section->id){
                                            $showSection = 'active show';
                                        }
                                        if($activeSection && request('sectionId', '-') == '-'){
                                            $showSection = 'active show';
                                        }
                                        @endphp
                                        <div class="tab-pane fade {{ $activeSection ? 'first_tab_' . $stepIdStr : '' }} {{ $showSection }}"
                                            id="adjudication_tab_{{ $section->id }}">
                                            @include('formsubmission::forms.adjudication_section_questions', $sharedData )
                                            @include('formsubmission::forms.adjudication_section_next_previous', $sharedData)
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
    </div>
    @push('script')
        <script>
            function submitStepAdjudicationForm{{ $stepIdStr }}(stepIdStr, stepClsStr) {
                if (isFormDataLocked(stepIdStr) == false) {
                    if (checkAdjudicationFormTermCond(stepIdStr)) {
                        if (isAdjudicationFormInEditMode(stepIdStr)) {
                            if (checkAdjudicationFormReason(stepIdStr) == false) {
                                stopJsHere();
                            }
                        }
                        validateAndSubmitAdjudicationForm(stepIdStr, '{{ buildAdjudicationStatusIdClsStr($adjudicationFormStatusObj->id) }}');
                    }
                } else {
                    showDataLockError();
                }
            }

        </script>
    @endpush
    @push('script_last')
        <script>
            $(document).ready(function() {
                @php
                if ($adjudicationFormStatusObj->adjudication_status != 'complete') {
                    if(empty(session('stepToActivateStr'))){
                        session(['stepToActivateStr' => $adjStepClsStr]);
                        echo "enableByClass('$adjStepClsStr');";
                    }
                }
                @endphp
            });

        </script>
    @endpush

    @php
    $adjudicationStepValidationStr = Modules\Admin\Entities\PhaseSteps::generateJSFormValidationForStep($step, true);
    @endphp
    @push('script')
        <script>
            function validateAdjudicationStep{{$stepIdStr}}() {
                var isFormValid = true;
                {!! $adjudicationStepValidationStr !!}
                return isFormValid;
            }
        </script>
    @endpush
    @endif
