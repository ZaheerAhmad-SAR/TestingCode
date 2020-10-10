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
                        <!--
                        <span class="badge p-2 badge-light mb-1">Not Graded</span>&nbsp;&nbsp;
                        <span class="badge p-2 badge-warning mb-1">Graded by 1st grader</span>&nbsp;&nbsp;
                        <span class="badge p-2 badge-success mb-1">Graded by 2nd grader</span>&nbsp;&nbsp;
                        <span class="badge p-2 badge-danger mb-1">Required Adjudication</span>&nbsp;&nbsp;
                        <div class="row">&nbsp;</div>
                        -->
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
                                                            $stepClsStr = buildSafeStr($step->step_id, 'step_cls_');
                                                            $stepIdStr = buildSafeStr($step->step_id, '');
                                                            @endphp
                                                            <a class="badge p-1 badge-light m-1  {{ $stepClsStr }}"
                                                                href="javascript:void(0);"
                                                                onclick="showSections('step_sections_{{ $stepIdStr }}');">
                                                                {{ $step->formType->form_type . ' ' . $step->step_name }}
                                                                @php
                                                                $getFormStatusArray = [
                                                                    'form_filled_by_user_id' => $form_filled_by_user_id,
                                                                    'form_filled_by_user_role_id' => $form_filled_by_user_role_id,
                                                                    'subject_id' => $subjectId,
                                                                    'study_id' => $studyId,
                                                                    'study_structures_id' => $phase->id,
                                                                    'phase_steps_id' => $step->step_id,
                                                                ];
                                                                echo \Modules\Admin\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true);
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
                                            $steps = \Modules\Admin\Entities\PhaseSteps::phaseStepsbyRoles($phase->id,
                                            $userRoleIds);
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
    @stop

    @push('styles')
        @include('admin::forms.form_css')
    @endpush

    @push('script')
        <script>
            function showAlert(message){
                alert(message);
                /*
                var field = $("#previous_alert_message");
                var previous_alert_message = field.val();
                if(previous_alert_message != message){
                    alert(message);
                    field.val(message);
                }else{
                    field.val('');
                }
                */
            }
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

            function submitForm(stepIdStr) {
                var submitFormFlag = true;
                if (isFormInEditMode(sectionIdStr)) {
                    if (checkReason(stepIdStr) === false) {
                        submitFormFlag = false;
                    }
                }
                if (submitFormFlag) {
                    var term_cond = $('#terms_cond_' + stepIdStr).val();
                    var reason = $('#edit_reason_text_' + stepIdStr).val();
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr)
                        .serialize() +
                        '&terms_cond_' + stepIdStr + '=' + term_cond + '&' + 'edit_reason_text=' + reason;
                        submitRequest(frmData, stepIdStr);
                }
            }

            function submitRequest(frmData, stepIdStr) {
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestionForm') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {
                        $('#form_hid_status_' + stepIdStr).val(response);
                        $('.img_step_status_' + stepIdStr).html('<img src="{{url('/')}}/images/'+response+'.png"/>');
                    }
                });
            }

            function submitFieldRequest(frmData, stepIdStr) {
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestion') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {
                        console.log(response);
                        $('#form_hid_status_' + stepIdStr).val(response);
                        $('.img_step_status_' + stepIdStr).html('<img src="{{url('/')}}/images/'+response+'.png"/>');
                    }
                });
            }

            function validateFormField(stepIdStr, questionId, field_name, fieldId) {
                    var field_val;
                    field_val = getFormFieldValue(stepIdStr, field_name, fieldId);
                    var frmData = $("#form_master_" + stepIdStr).serialize()  + '&questionId=' + questionId + '&' + field_name + '=' + field_val;
                    return validateSingleQuestion(frmData);

            }

            function validateForm(stepIdStr) {
                return new Promise(function (resolve, reject) {
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr).serialize();
                    $.ajax({
                        url: "{{ route('subjectFormSubmission.validateSectionQuestionsForm') }}",
                        type: 'POST',
                        data: frmData,
                        dataType: 'JSON',
                        success: function(response) {
                            if(response.success == 'no'){
                                reject(response.error);
                            }else{
                                resolve(response.success);
                            }
                        }
                    });
                })
            }

            function validateSingleQuestion(frmData) {
                return new Promise(function (resolve, reject) {
                $.ajax({
                    url: "{{ route('subjectFormSubmission.validateSingleQuestion') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        if(response.success == 'no'){
                            reject(response.error);
                        }else{
                            resolve(response.success);
                        }
                    }
                });
                })
            }
            function validateAndSubmitForm(stepIdStr){
                const promise = validateForm(stepIdStr);
                promise
                .then((data) => {
                    console.log(data);
                    submitForm(stepIdStr);
                })
                .catch((error) => {
                    console.log(error);
                    handleValidationErrors(error);
                });
            }
            function validateAndSubmitField(stepIdStr, sectionIdStr, questionId, field_name, fieldId){
                checkIsThisFieldDependent(sectionIdStr, questionId, field_name, fieldId);
                const validationPromise = validateFormField(stepIdStr, questionId, field_name, fieldId);
                validationPromise
                .then((data) => {
                    console.log(data)
                    submitFormField(stepIdStr, questionId, field_name, fieldId);
                })
                .then((data) => {
                    console.log(data)
                    validateDependentFields(sectionIdStr, questionId, field_name, fieldId);
                })
                .catch((error) => {
                    console.log(error)
                    handleValidationErrors(error);
                });
            }
            function checkIsThisFieldDependent(sectionIdStr, questionId, field_name, fieldId){}
            function validateDependentFields(sectionIdStr, questionId, field_name, fieldId){}
            function handleValidationErrors(error) { alert(error); }
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
                    showAlert('Please acknowledge the truthfulness and correctness of information being submitting in this form!');
                    return false;
                }
            }

            function isFormInEditMode(stepIdStr) {
                var formEditStatus = $('#form_editing_status_' + stepIdStr).val();
                var returnVal = false;
                if (formEditStatus == 'yes') {
                    returnVal = true;
                }
                return returnVal;
            }


            function checkReason(stepIdStr) {
                var returnVal = false;
                if (($('#edit_reason_text_' + stepIdStr).val() == '')) {
                    showAlert('Please tell the reason to edit');
                } else {
                    returnVal = true;
                }
                return returnVal;
            }

            function submitFormField(stepIdStr, questionId, field_name, fieldId) {
                var submitFormFlag = true;
                if (submitFormFlag) {
                    var frmData = $("#form_master_" + stepIdStr).serialize();
                    var field_val;
                    field_val = getFormFieldValue(stepIdStr, field_name, fieldId);
                    frmData = frmData + '&' + field_name + '=' + field_val + '&' + 'questionId=' + questionId;
                    submitFieldRequest(frmData, stepIdStr);
                }
            }

            function getFormFieldValue(stepIdStr, field_name, fieldId){
                var field_val;
                var checkedCheckBoxes = [];
                if ($('#' + fieldId).is("textarea")) {
                        field_val = $('#' + fieldId).val();
                    } else if ($('#' + fieldId).is("select")) {
                        field_val = $('#' + fieldId).find(":selected").val();
                    } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').attr('type') == 'radio') {
                        field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                    } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').attr('type') == 'checkbox') {

                        $('#form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').each(function(){
                            checkedCheckBoxes.push($(this).val());
                        });
                        field_val = checkedCheckBoxes.join(",");

                    } else {
                        field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                    }
                    return field_val;
            }

            function openFormForEditing(stepIdStr, stepClsStr) {
                var frmData = $("#form_master_" + stepIdStr).serialize();
                frmData = frmData + '&' + 'open_form_to_edit=1';
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.openSubjectFormToEdit') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {
                        showReasonField(stepIdStr, stepClsStr);
                    }
                });
            }

            function showReasonField(stepIdStr, stepClsStr) {
                $("#edit_form_div_" + stepIdStr).show(500);
                $("#edit_form_button_" + stepIdStr).hide(500);
                $('#edit_reason_text_' + stepIdStr).prop('required', true);
                enableByClass(stepClsStr);
                $('.form_hid_editing_status_' + stepIdStr).val('yes');
                $('.form_hid_status_' + stepIdStr).val('resumable');
                $('.img_step_status_' + stepIdStr).html('<img src="{{url('images/resumable.png')}}"/>');
            }

            function hideReasonField(stepIdStr, stepClsStr) {
                $("#edit_form_div_" + stepIdStr).hide(500);
                $('#edit_reason_text_' + stepIdStr).prop('required', false);
                $('#edit_reason_text_' + stepIdStr).val('');
                disableByClass(stepClsStr);
                $('.form_hid_editing_status_' + stepIdStr).val('no');
                $('.form_hid_status_' + stepIdStr).val('complete');
                $('.img_step_status_' + stepIdStr).html('<img src="{{url('images/complete.png')}}"/>');
                $('.nav-link').removeClass('active');
                $('.first_navlink_' + stepIdStr).addClass('active');
                $('.tab-pane_' + stepIdStr).removeClass('active show');
                $('.first_tab_' + stepIdStr).addClass('active show');
            }

        </script>
    @endpush
