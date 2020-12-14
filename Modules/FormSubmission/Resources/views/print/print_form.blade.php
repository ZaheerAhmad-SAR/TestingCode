@extends ('layouts.print')
@section('body')
    <div class="container-fluid site-width">
        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12">
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
                                        <tr>
                                            <th scope="row">Phase</th>
                                            <td>{{ $phase->name }}</td>
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
                                        <tr>
                                            <th scope="row">Step</th>
                                            <td>{{ $step->step_name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12">
                <div class="row row-eq-height">
                    <div class="col-12">
                        <div class="card border h-100 contact-list-section">
                            <div class="card-body p-0">
                                <div class="contacts list">
                                    @php
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
                                    ];
                                    @endphp
                                    @include('formsubmission::print.print_section_loop', $dataArray)
                                    @php
                                    }
                                    $activeStep = false;
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th scope="row">Form filled by:</th>
                                            <td>{{ $formFilledByUser->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th scope="row">Form filled on:</th>
                                            <td>
                                                {{ 'Created at : ' . $formStatusObj->created_at->format('M-d-Y:h:i:s') }}<br>
                                                {{ 'Updated at : ' . $formStatusObj->updated_at->format('M-d-Y:h:i:s') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('formsubmission::forms.form_css')
    @stop
