@extends ('layouts.home')
@section('content')
    <input type="hidden" name="already_global_disabled" id="already_global_disabled" value="no" />
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
                        <li class="breadcrumb-item active"><a href="{{ url('studies/'.$studyId) }}">Study Subjects</a></li>
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
                                <img src="{{url('images/no_status.png')}}"/>&nbsp;&nbsp;Not Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/incomplete.png')}}"/>&nbsp;&nbsp;Initiated
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/resumable.png')}}"/>&nbsp;&nbsp;Editing
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/complete.png')}}"/>&nbsp;&nbsp;Complete
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/not_required.png')}}"/>&nbsp;&nbsp;Not Required
                            </div>
                            <div class="col-md-2">
                                <img src="{{url('images/query.png')}}"/>&nbsp;&nbsp;Query
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
                            <div class="col-12">
                                <button type="button" class="btn btn-success" onclick="openAssignPhasesToSubjectPopup('{{ $studyId }}', '{{ $subjectId }}');" >Activate Visits</button>
                            </div>
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
                                $firstPhase = true;
                                @endphp
                                @if (count($visitPhases))
                                    @foreach ($visitPhases as $phase)
                                        @php
                                        $phaseIdStr = buildSafeStr($phase->id, 'phase_cls_');
                                        $subjectPhaseDetail = \Modules\Admin\Entities\SubjectsPhases::getSubjectPhase($subjectId, $phase->id);
                                        @endphp
                                        <div class="card text-white bg-primary m-1">
                                            <div id="heading{{ $phase->id }}" class="card-header {{ $phaseIdStr }}"
                                                data-toggle="collapse" data-target="#collapse{{ $phase->id }}"
                                                aria-expanded="{{ $firstPhase ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $phase->id }}">
                                                {{ $phase->name }}
                                                {{ ($phase->count > 0)? ' - Repeated: '.$phase->count:'' }}<br>
                                                {{$subjectPhaseDetail->visit_date->format('m-d-Y')}}</div>
                                            <div id="collapse{{ $phase->id }}"
                                                class="card-body collapse-body-bg collapse {{ $firstPhase ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $phase->id }}" data-parent="#accordion" style="">
                                                <p class="card-text">
                                                    @if (count($phase->phases))
                                                        @php
                                                        $firstStep = true;
                                                        $steps = \Modules\Admin\Entities\PhaseSteps::phaseStepsbyPermissions($phase->id);
                                                        @endphp
                                                        @foreach ($steps as $step)
                                                            @php
                                                            if ($step->form_type_id == 2) {
                                                                $getQcFormStatusArray = [
                                                                  'subject_id' => $subjectId,
                                                                    'study_id' => $studyId,
                                                                    'study_structures_id' => $phase->id,
                                                                    'phase_steps_id' => $step->step_id,
                                                                    'form_type_id' => '1',
                                                                    'modility_id' => $step->modility_id,
                                                                    'form_filled_by_user_id' => $form_filled_by_user_id,
                                                                    'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
                                                                ];
                                                                if(\Modules\Admin\Entities\FormStatus::getFormStatus($step, $getQcFormStatusArray) !== 'complete'){
                                                                    continue;
                                                                }
                                                            }

                                                            $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                                            $stepIdStr = buildSafeStr($step->step_id, '');
                                                            @endphp
                                                            <a class="badge p-1 badge-light m-1  {{ $stepClsStr }}"
                                                                href="javascript:void(0);"
                                                                onclick="showSections('step_sections_{{ $stepIdStr }}');">
                                                                {{ $step->formType->form_type . ' ' . $step->step_name }}
                                                                @php
                                                                $getFormStatusArray = [
                                                                  'subject_id' => $subjectId,
                                                                    'study_id' => $studyId,
                                                                    'study_structures_id' => $phase->id,
                                                                    'phase_steps_id' => $step->step_id,
                                                                    'form_type_id' => $step->form_type_id,
                                                                ];
                                                                if ($step->form_type_id == 1) {
                                                                    $getFormStatusArray2 = [
                                                                    'form_filled_by_user_id' => $form_filled_by_user_id,
                                                                    'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
                                                                ];
                                                                echo \Modules\Admin\Entities\FormStatus::getFormStatus($step, $getFormStatusArray+$getFormStatusArray2, true);
                                                                }
                                                                if ($step->form_type_id == 2) {
                                                                    echo \Modules\Admin\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray);
                                                                }
                                                                @endphp
                                                            </a>
                                                            <br>
                                                            @php
                                                            $firstStep = false;
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
                                        @endphp
                                        @foreach ($visitPhases as $phase)
                                            @php
                                            $phaseIdStr = buildSafeStr($phase->id, 'phase_cls_');
                                            $steps = \Modules\Admin\Entities\PhaseSteps::phaseStepsbyPermissions($phase->id);
                                            @endphp
                                            @foreach ($steps as $step)
                                                @php
                                                $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
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
                                                'form_filled_by_user_role_id' => $form_filled_by_user_role_id
                                                ];
                                                @endphp
                                                <div class="all_step_sections step_sections_{{ $stepIdStr }}"
                                                    style="display: {{ $firstStep ? 'block' : 'none' }};">
                                                    @include('admin::forms.section_loop', $dataArray)
                                                </div>
                                                @php
                                                }
                                                $firstStep = false;
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
        @include('admin::subjectFormLoader.subject_form_wait_popup')
        @include('admin::subjectFormLoader.assignPhasesToSubjectPopup')
        @stop
  @include('admin::subjectFormLoader.subject_form_css_js')
