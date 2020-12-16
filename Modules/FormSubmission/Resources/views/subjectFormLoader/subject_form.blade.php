@extends ('layouts.home')
@section('content')
    <input type="hidden" name="already_global_disabled" id="already_global_disabled" value="100000" />
    <input type="hidden" name="previous_alert_message" id="previous_alert_message" value="" />
    <input type="hidden" name="current_phase_id" id="current_phase_id" value="{{ request('phaseId', '-') }}" />
    <input type="hidden" name="current_step_id" id="current_step_id" value="{{ request('stepId', '-') }}" />
    <input type="hidden" name="current_section_id" id="current_section_id" value="{{ request('sectionId', '-') }}" />
    <input type="hidden" name="showAllQuestions" id="showAllQuestions" value="{{ request('showAllQuestions', 'no') }}" />
    <input type="hidden" name="isAdjudication" id="isAdjudication" value="{{ request('isAdjudication', 'no') }}" />
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Subject Phases</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('studies') }}">Studies</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('studies/' . $studyId) }}">Study Subjects</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void();">Form</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Study and Subject details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th scope="row">Subject ID</th>
                                            <td>{{ $subject->subject_id }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Study EYE</th>
                                            <td>{{ $subject->study_eye }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Study Site ID</th>
                                            <td>{{ $studySite->study_site_id }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th scope="row">Site Name</th>
                                            <td>{{ $site->site_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Site Code</th>
                                            <td>{{ $site->site_code }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cohort</th>
                                            <td>{{ \Modules\Admin\Entities\Subject::getDiseaseCohort($subject) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Grading legend</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="{{ url('images/no_status.png') }}" />&nbsp;&nbsp;Not Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{ url('images/incomplete.png') }}" />&nbsp;&nbsp;Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{ url('images/resumable.png') }}" />&nbsp;&nbsp;Editing
                            </div>
                            <div class="col-md-2">
                                <img src="{{ url('images/complete.png') }}" />&nbsp;&nbsp;Complete
                            </div>
                            <div class="col-md-2">
                                <img src="{{ url('images/not_required.png') }}" />&nbsp;&nbsp;Not Required
                            </div>
                            <div class="col-md-2">
                                <img src="{{ url('images/query.png') }}" />&nbsp;&nbsp;Query
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Activate Visits</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(
                                (\Modules\Admin\Entities\Preference::getPreference('VISIT_ACTIVATION') == 'Manual') &&
                                canQualityControl(['index', 'create', 'store', 'edit', 'update'])
                            )
                            <div class="col-2">
                                <button type="button" class="btn btn-success"
                                    onclick="openAssignPhasesToSubjectPopup('{{ $studyId }}', '{{ $subjectId }}');">Activate
                                    Visits</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12">
                <div class="row row-eq-height">
                    <div class="col-12 col-lg-2 mt-3 todo-menu-bar flip-menu pr-lg-0">
                        <a href="#" class="d-inline-block d-lg-none mt-1 flip-menu-close"><i class="icon-close"></i></a>
                        <div class="card border h-100 contact-menu-section">
                            <div id="accordion">
                                @php
                                $activePhase = true;
                                @endphp
                                @if (count($visitPhases))
                                    @foreach ($visitPhases as $phase)
                                        @php
                                        $phaseIdStr = buildSafeStr($phase->id, 'phase_cls_');
                                        $subjectPhaseDetail =
                                        \Modules\FormSubmission\Entities\SubjectsPhases::getSubjectPhase($subjectId, $phase->id);

                                        $showPhase = 'false';
                                        if(request('phaseId', '-') == $phase->id){
                                            $showPhase = 'true';
                                        }
                                        if($activePhase && request('phaseId', '-') == '-'){
                                            $showPhase = 'true';
                                        }
                                        @endphp
                                        <div class="card text-white bg-primary m-1">
                                            <div
                                                id="heading{{ $phase->id }}"
                                                class="card-header {{ $phaseIdStr }}"
                                                data-toggle="collapse" data-target="#collapse{{ $phase->id }}"
                                                aria-expanded="{{ $showPhase }}"
                                                aria-controls="collapse{{ $phase->id }}"
                                                onclick="updateCurrentPhaseId('{{ $phase->id }}');"
                                                >
                                                {{ $phase->name }}
                                                {{ $phase->count > 0 ? ' - Repeated: ' . $phase->count : '' }}<br>
                                                {{ $subjectPhaseDetail->visit_date->format('m-d-Y') }}

                                                @if(
                                                    (\Modules\Admin\Entities\Preference::getPreference('VISIT_ACTIVATION') == 'Manual') &&
                                                    canQualityControl(['index', 'create', 'store', 'edit', 'update'])
                                                )
                                                    <br><span style="cursor: pointer;" onclick="unAssignPhaseToSubject('{{ $subjectId }}', '{{ $phase->id }}');" class="text text-warning">Deactivate Visit</span>
                                                @endif
                                            </div>
                                            <div id="collapse{{ $phase->id }}"
                                                class="card-body collapse-body-bg collapse {{ ($activePhase || request('phaseId', '-') == $phase->id) ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $phase->id }}" data-parent="#accordion" style="">
                                                <p class="card-text">
                                                    @if (count($phase->phases))
                                                        @php
                                                        $activeStep = true;
                                                        $steps =
                                                        \Modules\Admin\Entities\PhaseSteps::phaseStepsbyPermissions($subjectId, $phase->id);
                                                        @endphp
                                                        @foreach ($steps as $step)
                                                            @php
                                                            if ($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') {
                                                                $getQcFormStatusArray = [
                                                                    'subject_id' => $subjectId,
                                                                    'study_id' => $studyId,
                                                                    'study_structures_id' => $phase->id,
                                                                    'form_type_id' => '1',
                                                                    'modility_id' => $step->modility_id,
                                                                ];
                                                                $qcFormStatus = \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getQcFormStatusArray);
                                                                if($qcFormStatus !== 'complete'){
                                                                    continue;
                                                                }
                                                            }
                                                            if ($step->formType->form_type == 'Grading') {
                                                                $eligibilityStep = \Modules\Admin\Entities\PhaseSteps::getEligibilityStep($phase->id, $step->modility_id);

                                                                $getEligibilityFormStatusArray = [
                                                                'subject_id' => $subjectId,
                                                                'study_id' => $studyId,
                                                                'study_structures_id' => $phase->id,
                                                                'form_type_id' => 3,
                                                                'modility_id' => $step->modility_id,
                                                                ];
                                                                if(
                                                                    null !== $eligibilityStep &&
                                                                    \Modules\FormSubmission\Entities\FormStatus::isAllGradersGradedThatForm($eligibilityStep, $getEligibilityFormStatusArray) === false){
                                                                        continue;
                                                                }
                                                            }



                                                            $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                                            $adjStepClsStr = buildSafeStr($step->step_id, 'adj_step_cls_');
                                                            $stepIdStr = buildSafeStr($step->step_id, '');

                                                            $badgeCls = 'badge-light';
                                                            if(request('stepId', '-') == $step->step_id){
                                                                $badgeCls = 'badge-light';
                                                            }
                                                            if($activeStep && request('stepId', '-') == '-'){
                                                                $badgeCls = 'badge-light';
                                                            }
                                                            $skipLogicStepIdStr = buildSafeStr($step->step_id, 'skip_logic_');

                                                            $stepData = [
                                                                'step' => $step,
                                                                'stepClsStr' => $stepClsStr,
                                                                'adjStepClsStr' => $adjStepClsStr,
                                                                'stepIdStr' => $stepIdStr,
                                                                'activeStep' => $activeStep,
                                                                'badgeCls' => $badgeCls,
                                                                'skipLogicStepIdStr' => $skipLogicStepIdStr,
                                                            ];
                                                            @endphp
                                                            @include('formsubmission::subjectFormLoader.qc_left_bar_nav', $stepData)
                                                            @include('formsubmission::subjectFormLoader.eligibility_left_bar_nav', $stepData)
                                                            @include('formsubmission::subjectFormLoader.grader_left_bar_nav', $stepData)
                                                            @include('formsubmission::subjectFormLoader.adjudication_left_bar_nav', $stepData)
                                                            @php
                                                            $activeStep = false;
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @php
                                        $activePhase = false;
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-10 mt-3 pl-lg-0">
                        <div class="card border h-100 contact-list-section">
                            <div class="card-body p-0">
                                <div class="contacts list">
                                    @if (count($visitPhases))
                                        @php
                                        $activeStep = true;
                                        $stepCounter = 0;
                                        @endphp
                                        @foreach ($visitPhases as $phase)
                                            @php
                                            $phaseIdStr = buildSafeStr($phase->id, 'phase_cls_');
                                            $steps =
                                            \Modules\Admin\Entities\PhaseSteps::phaseStepsbyPermissions($subjectId, $phase->id);
                                            @endphp
                                            @foreach ($steps as $step)
                                                @php
                                                $stepCounter++;
                                                if ($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') {
                                                    $getQcFormStatusArray = [
                                                        'subject_id' => $subjectId,
                                                        'study_id' => $studyId,
                                                        'study_structures_id' => $phase->id,
                                                        'form_type_id' => '1', //QC
                                                        'modility_id' => $step->modility_id,
                                                    ];
                                                    $qcFormStatus = \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getQcFormStatusArray);
                                                    if($qcFormStatus !== 'complete'){
                                                        continue;
                                                    }
                                                }

                                                if ($step->formType->form_type == 'Grading') {
                                                    $eligibilityStep = \Modules\Admin\Entities\PhaseSteps::getEligibilityStep($phase->id, $step->modility_id);

                                                    $getEligibilityFormStatusArray = [
                                                        'subject_id' => $subjectId,
                                                        'study_id' => $studyId,
                                                        'study_structures_id' => $phase->id,
                                                        'form_type_id' => 3,
                                                        'modility_id' => $step->modility_id,
                                                        ];
                                                    if(
                                                        null !== $eligibilityStep &&
                                                        \Modules\FormSubmission\Entities\FormStatus::isAllGradersGradedThatForm($eligibilityStep, $getEligibilityFormStatusArray) === false){
                                                        continue;
                                                    }
                                                }

                                                $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                                $adjStepClsStr = buildSafeStr($step->step_id, 'adj_step_cls_');
                                                $stepIdStr = buildSafeStr($step->step_id, '');
                                                $skipLogicStepIdStr = buildSafeStr($step->step_id, 'skip_logic_');


                                                $sections = $step->sections;
                                                if(count($sections)){
                                                $dataArray = [
                                                'studyId'=>$studyId,
                                                'studyClsStr'=>$studyClsStr,
                                                'subjectId'=>$subjectId,
                                                'phase'=>$phase,
                                                'step'=>$step,
                                                'sections'=> $sections,
                                                'phaseIdStr'=>$phaseIdStr,
                                                'current_user_id' => $current_user_id,
                                                'activeStep' => $activeStep,
                                                'stepClsStr' => $stepClsStr,
                                                'stepIdStr' => $stepIdStr,
                                                'skipLogicStepIdStr' => $skipLogicStepIdStr,
                                                'stepCounter' => $stepCounter,
                                                ];
                                                @endphp
                                                @include('formsubmission::forms.section_loop', $dataArray)
                                                @include('formsubmission::forms.adjudication_form', $dataArray)
                                                @php
                                                }
                                                $activeStep = false;
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Card DATA-->
        </div>

        @include('queries::queries.query_popup')
        @include('formsubmission::subjectFormLoader.include.subject_form_wait_popup')
        @include('formsubmission::subjectFormLoader.include.assignPhasesToSubjectPopup')
        @include('formsubmission::subjectFormLoader.include.subject_form_css_js')
        @include('formsubmission::subjectFormLoader.include.subject_adjudication_form_css_js')
        @include('formsubmission::subjectFormLoader.include.validation_rules_functions_js')
        @include('formsubmission::question_comments.addQuestionCommentPopup')
        @include('formsubmission::question_comments.questionCommentPopup')
        @include('formsubmission::qc_question_to_show.qcQuestionsToShowPopup')
    @stop
