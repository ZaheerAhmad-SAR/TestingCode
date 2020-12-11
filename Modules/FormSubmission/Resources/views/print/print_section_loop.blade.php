@php
/**************************************/
/**************************************/
/**************************************/
$transmissionNumber = \Modules\FormSubmission\Entities\SubjectsPhases::getTransmissionNumber($subjectId, $phase->id);
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
@if ($showForm == true)
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
        @php
        $getFormStatusArray = [
        'subject_id' => $subjectId,
        'study_id' => $studyId,
        'study_structures_id' => $phase->id,
        'phase_steps_id' => $step->step_id,
        'form_type_id' => $step->form_type_id,
        'modility_id' => $step->modility_id,
        ];

        $formStatusObjects = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObjArray($getFormStatusArray);
        $numberOfAlreadyGradedPersons = count($formStatusObjects);

        if($step->form_type_id == 2){
        $getFormStatusArray['form_filled_by_user_id'] = $current_user_id;
        }
        $formStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getFormStatusArray);
        $formStatus = 'no_status';
        $formFilledByUserId = 'no-user-id';
        $isFormDataLocked = 0;
        if(null !== $formStatusObj){
        $formStatus = $formStatusObj->form_status;
        $formFilledByUserId = $formStatusObj->form_filled_by_user_id;
        $isFormDataLocked = $formStatusObj->is_data_locked;
        }
        @endphp
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="wizard wizard-white mb-4">
                            @php
                            $current_user_id = ($current_user_id ?? '');
                            $subjectId = ($subjectId ?? '');
                            $studyId = ($studyId ?? '');
                            $studyClsStr = ($studyClsStr ?? '');
                            @endphp
                            @foreach ($sections as $key => $section)
                                @php
                                $sectionClsStr = buildSafeStr($section->id, 'section_cls_');
                                @endphp
                                <div class="">
                                    <div class="d-flex">
                                        <div class="mr-3 mb-0 h1">{{ $section->sort_number }}</div>
                                        <div class="media-body align-self-center">
                                            <h6 class="mb-0 text-uppercase font-weight-bold">
                                                {{ $section->name }}
                                            </h6>
                                            {{ $section->description }}
                                            @if (!empty($transmissionNumber))
                                                <br>
                                                <span class="text text-danger">Transmission Number :
                                                    {{ $transmissionNumber }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        @php
                                        $sectionClsStr = buildSafeStr($section->id, 'sec_cls_');
                                        $skipLogicSectionIdStr = buildSafeStr($section->id, 'skip_logic_');
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
                                        'skipLogicSectionIdStr' => $skipLogicSectionIdStr,
                                        'stepClsStr'=> $stepClsStr,
                                        'skipLogicStepIdStr'=> $skipLogicStepIdStr,
                                        'getFormStatusArray'=>$getFormStatusArray
                                        ];

                                        @endphp
                                        <div>
                                            @include('formsubmission::print.print_section_questions', $sharedData )
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
