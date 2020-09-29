@extends ('layouts.home')
@section('content')
    @php
    $studyId = isset($studyId) ? $studyId : 0;
    $studyClsStr = buildSafeStr($studyId, 'study_cls_');
    $study = \Modules\Admin\Entities\Study::find($studyId);

    $subjectId = isset($subjectId) ? $subjectId : 0;
    $subject = \Modules\Admin\Entities\Subject::find($subjectId);

    $form_filled_by_user_id = auth()->user()->id;
    $form_filled_by_user_role_id = auth()->user()->id;
    @endphp
    <input type="hidden" name="already_global_disabled" id="already_global_disabled" value="no" />
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Subject Phases</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item">Forms</li>
                        <li class="breadcrumb-item active"><a href="#">Form Type Here</a></li>
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
                        <table>
                            <tr><td>Study ID :</td><td>{{ $studyId }}</td></tr>
                            <tr><td>Study Title :</td><td>{{ $study->study_title }}</td></tr>

                            <tr><td>Subject ID :</td><td>{{ $subject->subject_id }}</td></tr>
                            <tr><td>Study EYE :</td><td>{{ $subject->study_eye }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Grading legend</h4>
                    </div>
                    <div class="card-body">
                        <span class="badge p-2 badge-light mb-1">Not Graded</span>&nbsp;&nbsp;
                        <span class="badge p-2 badge-warning mb-1">Graded by 1st grader</span>&nbsp;&nbsp;
                        <span class="badge p-2 badge-success mb-1">Graded by 2nd grader</span>&nbsp;&nbsp;
                        <span class="badge p-2 badge-danger mb-1">Required Adjudication</span>&nbsp;&nbsp;
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
                                        @endphp
                                        <div class="card text-white bg-primary m-1">
                                            <div id="heading{{ $phase->id }}" class="card-header {{ $phaseIdStr }}"
                                                data-toggle="collapse" data-target="#collapse{{ $phase->id }}"
                                                aria-expanded="{{ $firstPhase ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $phase->id }}">
                                                {{ $phase->name }}</div>
                                            <div id="collapse{{ $phase->id }}"
                                                class="card-body collapse-body-bg collapse {{ $firstPhase ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $phase->id }}" data-parent="#accordion" style="">
                                                <p class="card-text">
                                                    @if (count($phase->phases))
                                                        @php
                                                        $firstStep = true;
                                                        $steps =
                                                        \Modules\Admin\Entities\PhaseSteps::phaseStepsbyRoles($phase->id,
                                                        $userRoleIds);
                                                        @endphp
                                                        @foreach ($steps as $step)
                                                            @php
                                                            $stepIdStr = buildSafeStr($step->step_id, 'step_cls_');
                                                            @endphp
                                                            <a class="badge p-1 badge-light m-1  {{ $stepIdStr }}"
                                                                href="javascript:void(0);"
                                                                onclick="showSections('step_sections_{{ $step->step_id }}');">
                                                                {{ $step->step_name }}
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
                                            $steps = \Modules\Admin\Entities\PhaseSteps::phaseStepsbyRoles($phase->id,
                                            $userRoleIds);
                                            @endphp
                                            @foreach ($steps as $step)
                                                @php
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
                                                <div class="all_step_sections step_sections_{{ $step->step_id }}"
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
    @stop

    @push('styles')
        @include('admin::forms.form_css')
    @endpush

    @push('script')
        <script>
            function showSections(step_id_class) {
                $('.all_step_sections').hide(500);
                $('.' + step_id_class).show(500);
            }

            function disableAllFormFields(formId) {
                $("#" + formId + " input").prop('disabled', true);
            }

            function enableAllFormFields(formId) {
                $("#" + formId + " input").prop('disabled', false);
            }

            function disableField(fieldId) {
                $("#" + fieldId).prop('disabled', true);
            }

            function enableField(fieldId) {
                $("#" + fieldId).prop('disabled', false);
            }

            function disableByClass(cls) {
                $("." + cls).prop('disabled', true);
            }

            function globalDisableByClass(studyClsStr, sectionClsStr) {
                if ($('#already_global_disabled').val() == 'no') {
                    $("." + studyClsStr).prop('disabled', true);
                    $('#already_global_disabled').val('yes')
                    enableByClass(sectionClsStr);
                }
            }

            function enableByClass(cls) {
                $("." + cls).prop('disabled', false);
            }

            function submitForm(sectionIdStr, sectionClsStr, stepIdStr) {
                var submitFormFlag = true;
                if (isFormInEditMode(sectionIdStr)) {
                    if (checkReason(stepIdStr) === false) {
                        submitFormFlag = false;
                    }
                }
                if(submitFormFlag){
                    var term_cond = $('#terms_cond_' + stepIdStr).val();
                        var reason = $('#edit_reason_text_' + stepIdStr).val();
                        var frmData = $("#form_master_" + sectionIdStr).serialize() + '&' + $("#form_" + sectionIdStr)
                            .serialize() +
                            '&terms_cond_' + stepIdStr + '=' + term_cond + '&' + 'edit_reason_text=' + reason;
                        submitRequest(frmData);
                }
            }

            function reloadPage(stepClsStr) {
                setTimeout(function() {
                    //disableByClass(stepClsStr);
                    location.reload();
                }, 1000);
            }

            function checkTermCond(stepIdStr) {
                if ($('#terms_cond_' + stepIdStr).prop('checked')) {
                    return true;
                } else {
                    alert(
                        'Please acknowledge the truthfulness and correctness of information being submitting in this form!'
                    );
                    return false;
                }
            }

            function isFormInEditMode(sectionIdStr) {
                var formStatus = $('#form_master_' + sectionIdStr + ' #form_status').val();
                var formEditStatus = $('#form_master_' + sectionIdStr + ' #form_editing_status').val();
                var returnVal = false;
                if (formEditStatus == 'yes') {
                    returnVal = true;
                }

                return returnVal;
            }


            function checkReason(stepIdStr) {
                var returnVal = false;
                if (($('#edit_reason_text_' + stepIdStr).val() == '')) {
                    alert('Please tell the reason to edit');
                } else {
                    returnVal = true;
                }
                return returnVal;
            }

            function submitFormField(stepIdStr, sectionIdStr, field_name) {
                var submitFormFlag = true;
                if (isFormInEditMode(sectionIdStr)) {
                    if (checkReason(stepIdStr) === false) {
                        submitFormFlag = false;
                    }
                }
                if(submitFormFlag){
                    var frmData = $("#form_master_" + sectionIdStr).serialize();
                        var field_val;
                        if ($('#form_' + sectionIdStr + ' input[name="' + field_name + '"]').attr('type') == 'radio') {
                            field_val = $('#form_' + sectionIdStr + ' input[name="' + field_name + '"]:checked').val();
                        } else {
                            field_val = $('#form_' + sectionIdStr + ' input[name="' + field_name + '"]').val();
                        }
                        var reason = $('#edit_reason_text_' + stepIdStr).val();

                        frmData = frmData + '&' + field_name + '=' + field_val + '&' + 'edit_reason_text=' + reason;
                        submitRequest(frmData);
                }


            }

            function openFormForEditing(stepIdStr, stepClsStr, sectionIdStr) {
                var frmData = $("#form_master_" + sectionIdStr).serialize();
                frmData = frmData + '&' + 'open_form_to_edit=1';
                $.ajax({
                    url: "{{ route('openSubjectFormToEdit') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {
                        showReasonField(stepIdStr, stepClsStr, sectionIdStr);
                    }
                });
            }

            function submitRequest(frmData) {
                $.ajax({
                    url: "{{ route('submitStudyPhaseStepQuestionForm') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {
                        //
                    }
                });
            }

            function showReasonField(stepIdStr, stepClsStr, sectionIdStr) {
                $("#edit_form_div_" + stepIdStr).show(500);
                $('#edit_reason_text_' + stepIdStr).prop('required', true);
                enableByClass(stepClsStr);
                $('.form_hid_editing_status_' + stepIdStr).val('yes');
                $('.form_hid_status_' + stepIdStr).val('resumable');
            }

        </script>
    @endpush
