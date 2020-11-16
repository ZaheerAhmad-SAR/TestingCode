@extends ('layouts.home')
@section('content')
    <input type="hidden" name="already_global_disabled" id="already_global_disabled" value="100000" />
    <input type="hidden" name="previous_alert_message" id="previous_alert_message" value="" />
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
                        <li class="breadcrumb-item active"><a href="{{ url('studies/' . $studyId) }}">Study Subjects</a>
                        </li>
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
            @if(
                (\Modules\Admin\Entities\Preference::getPreference('VISIT_ACTIVATION') == 'Manual') &&
                canQualityControl(['index', 'create', 'store', 'edit', 'update'])
            )
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Activate Visits</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-success"
                                    onclick="openAssignPhasesToSubjectPopup('{{ $studyId }}', '{{ $subjectId }}');">Activate
                                    Visits</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-12 col-sm-12">
                <div class="row row-eq-height">
                    <div class="col-12 col-lg-2 mt-3 todo-menu-bar flip-menu pr-lg-0">
                        <a href="#" class="d-inline-block d-lg-none mt-1 flip-menu-close"><i class="icon-close"></i></a>
                        <div class="card border h-100 contact-menu-section">
                            <div id="accordion">
                                @php
                                $firstPhase = true;
                                @endphp
                                @if (count($visitPhases))
                                    @foreach ($visitPhases as $phase)
                                        @php
                                        $phaseIdStr = buildSafeStr($phase->id, 'phase_cls_');
                                        $subjectPhaseDetail =
                                        \Modules\FormSubmission\Entities\SubjectsPhases::getSubjectPhase($subjectId, $phase->id);
                                        @endphp
                                        <div class="card text-white bg-primary m-1">
                                            <div id="heading{{ $phase->id }}" class="card-header {{ $phaseIdStr }}"
                                                data-toggle="collapse" data-target="#collapse{{ $phase->id }}"
                                                aria-expanded="{{ $firstPhase ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $phase->id }}">
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
                                                class="card-body collapse-body-bg collapse {{ $firstPhase ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $phase->id }}" data-parent="#accordion" style="">
                                                <p class="card-text">
                                                    @if (count($phase->phases))
                                                        @php
                                                        $firstStep = true;
                                                        $steps =
                                                        \Modules\Admin\Entities\PhaseSteps::phaseStepsbyPermissions($subjectId, $phase->id);
                                                        $previousStepId = '';
                                                        @endphp
                                                        @foreach ($steps as $step)
                                                            @php
                                                            if ($step->form_type_id == 2 && $previousStepId != '') {
                                                                $getQcFormStatusArray = [
                                                                    'subject_id' => $subjectId,
                                                                    'study_id' => $studyId,
                                                                    'study_structures_id' => $phase->id,
                                                                    'phase_steps_id' => $previousStepId,
                                                                    'form_type_id' => '1',
                                                                    'modility_id' => $step->modility_id,
                                                                ];
                                                                $qcFormStatus =
                                                                \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step,
                                                                $getQcFormStatusArray);
                                                                if($qcFormStatus !== 'complete'){
                                                                    continue;
                                                                }
                                                            }
                                                            $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                                            $adjStepClsStr = buildSafeStr($step->step_id, 'adj_step_cls_');
                                                            $stepIdStr = buildSafeStr($step->step_id, '');
                                                            @endphp

                                                            @include('formsubmission::subjectFormLoader.qc_left_bar_nav')
                                                            @include('formsubmission::subjectFormLoader.grader_left_bar_nav', ['step'=>$step])
                                                            @include('formsubmission::subjectFormLoader.adjudication_left_bar_nav')
                                                            @php
                                                            $firstStep = false;
                                                            $previousStepId = $step->step_id;
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @php
                                        $firstPhase = false;
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
                                        $firstStep = true;
                                        $stepCounter = 0;
                                        @endphp
                                        @foreach ($visitPhases as $phase)
                                            @php
                                            $phaseIdStr = buildSafeStr($phase->id, 'phase_cls_');
                                            $steps =
                                            \Modules\Admin\Entities\PhaseSteps::phaseStepsbyPermissions($subjectId, $phase->id);
                                            $previousStepId = '';
                                            @endphp
                                            @foreach ($steps as $step)
                                                @php
                                                $stepCounter++;
                                                if ($step->form_type_id == 2 && $previousStepId != '') {
                                                    $getQcFormStatusArray = [
                                                        'subject_id' => $subjectId,
                                                        'study_id' => $studyId,
                                                        'study_structures_id' => $phase->id,
                                                        'phase_steps_id' => $previousStepId,
                                                        'form_type_id' => '1',
                                                        'modility_id' => $step->modility_id,
                                                    ];
                                                    $qcFormStatus = \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step,
                                                    $getQcFormStatusArray);
                                                    if($qcFormStatus !== 'complete'){
                                                        continue;
                                                    }
                                                }
                                                $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                                $adjStepClsStr = buildSafeStr($step->step_id, 'adj_step_cls_');
                                                $stepIdStr = buildSafeStr($step->step_id, '');

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
                                                'form_filled_by_user_id' => $form_filled_by_user_id,
                                                'firstStep' => $firstStep,
                                                'stepClsStr' => $stepClsStr,
                                                'stepIdStr' => $stepIdStr,
                                                'stepCounter' => $stepCounter,
                                                ];
                                                @endphp
                                                @include('formsubmission::forms.section_loop', $dataArray)
                                                @include('formsubmission::forms.adjudication_form', $dataArray)
                                                @php
                                                }
                                                $firstStep = false;
                                                $previousStepId = $step->step_id;
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
    @stop
