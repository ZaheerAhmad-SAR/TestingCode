@extends ('layouts.home')
@section('content')
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
                                        session(['already_one_form_is_resumable' => 0]);
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
                                                @endphp
                                                <div class="all_step_sections step_sections_{{ $step->step_id }}"
                                                    style="display: {{ $firstStep ? 'block' : 'none' }};">
                                                    @include('admin::forms.section_loop', ['studyId'=>$studyId,
                                                    'subjectId'=>$subjectId, 'phase'=>$phase, 'step'=>$step,
                                                    'sections'=> $sections, 'phaseIdStr'=>$phaseIdStr])
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

            function disableFieldByClass(cls) {
                $("." + cls).prop('disabled', true);
            }

            function enableFieldByClass(cls) {
                $("." + cls).prop('disabled', false);
            }

            function submitMe(event, sectionIdStr, sectionClsStr, stepIdStr) {
                event.preventDefault();
                submitForm(sectionIdStr, sectionClsStr, stepIdStr);
            }

            function submitForm(sectionIdStr, sectionClsStr, stepIdStr) {
                var term_cond = $('#term_cond_' + stepIdStr + ':checked').val();
                var frmData = $("#form_master_" + sectionIdStr).serialize() + '&' + $("#form_" + sectionIdStr).serialize() + '&term_cond_'+stepIdStr+'='+term_cond;
                submitRequest(frmData);
                disableFieldByClass(sectionClsStr);
                setTimeout(function() {
                   location.reload();
                }, 1000);

            }

            function submitFormField(sectionIdStr, field_name) {
                if (
                    (
                        ($('#form_' + sectionIdStr + ' #edit_reason_text').prop('required') === true) &&
                        ($('#form_' + sectionIdStr + ' #edit_reason_text').val() != '')
                    ) ||
                    ($('#form_' + sectionIdStr + ' #edit_reason_text').prop('required') === false)
                ) {
                    var frmData = $("#form_master_" + sectionIdStr).serialize();
                    var field_val;
                    if ($('#form_' + sectionIdStr + ' input[name="' + field_name + '"]').attr('type') == 'radio') {
                        field_val = $('#form_' + sectionIdStr + ' input[name="' + field_name + '"]:checked').val();
                    } else {
                        field_val = $('#form_' + sectionIdStr + ' input[name="' + field_name + '"]').val();
                    }
                    var reason = $('#form_' + sectionIdStr + ' #edit_reason_text').val();

                    frmData = frmData + '&' + field_name + '=' + field_val + '&' + 'edit_reason_text=' + reason;
                    submitRequest(frmData);
                } else {
                    alert('Please tell the reason to edit');
                }
            }

            function submitRequest(frmData) {
                $.ajax({
                    url: "{{ route('submitStudyPhaseStepQuestionForm') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {

                    }
                });
            }

            function showReasonField(checkBoxId, divId, sectionIdStr, sectionClsStr) {
                if ($('#'+checkBoxId).prop('checked') === true) {
                    var reason_to_edit = prompt("Please enter reason to edit form");
                    if (reason_to_edit !== null && reason_to_edit != "") {
                        $("#" + divId).show(500);
                        $('#form_' + sectionIdStr + ' #edit_reason_text').prop('required', true);
                        $('#form_' + sectionIdStr + ' #edit_reason_text').val(reason_to_edit);
                        enableFieldByClass(sectionClsStr);
                    }
                }else {
                    $("#" + divId).hide(500);
                    $('#form_' + sectionIdStr + ' #edit_reason_text').prop('required', false);
                    $('#form_' + sectionIdStr + ' #edit_reason_text').val('');
                    disableFieldByClass(sectionClsStr);
                }

            }

        </script>
    @endpush
