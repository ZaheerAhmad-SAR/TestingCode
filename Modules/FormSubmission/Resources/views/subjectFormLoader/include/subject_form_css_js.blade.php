    @push('styles')
        @include('formsubmission::forms.form_css')
    @endpush

    @push('script')
        <script>
            var isPreview = {{ $isPreview === true ? 'true' : 'false' }};
            var upload_max_filesize = {{ return_bytes(ini_get('upload_max_filesize')) }};
            var post_max_size = {{ return_bytes(ini_get('post_max_size')) }};

            function showAlert(swalTitle, message, messageType) {
                swal.fire({
                    title: swalTitle,
                    text: message,
                    icon: messageType,
                    //dangerMode: true,
                });
            }

            function showSections(step_id_class) {
                $('.all_step_sections').hide(500);
                $('.' + step_id_class).show(500);
            }

            function showAdjudication(step_id_class) {
                $('.all_step_sections').hide(500);
                $('.' + step_id_class).show(500);
            }

            function disableAllFormFields(id) {
                //console.log('disableAllFormFields : ' + id);
                $("#" + id + " :input").attr('disabled', true);
                $("#" + id + " textarea").attr('disabled', true);
                $("#" + id + " select").attr('disabled', true);
            }

            function makeReadOnly(cls) {
                $("." + cls + " :input").prop('readonly', true);
            }

            function removeReadOnly(cls) {
                $("." + cls + " :input").prop('readonly', false);
            }

            function enableAllFormFields(id) {
                //console.log('enableAllFormFields : ' + id);
                $("#" + id + " :input").attr('disabled', false);
                $("#" + id + " textarea").attr('disabled', false);
                $("#" + id + " select").attr('disabled', false);
            }

            function disableField(fieldId) {
                $("#" + fieldId).prop('disabled', true);
            }

            function enableField(fieldId) {
                $("#" + fieldId).prop('disabled', false);
            }

            function disableByClass(cls) {
                //console.log('disableByClass : ' + cls);
                $("." + cls).prop('disabled', true);
                $("." + cls + " :input").prop('disabled', true);
                $("." + cls + " textarea").prop('disabled', true);
                $("." + cls + " select").prop('disabled', true);
            }

            function disableLinkByClass(cls) {
                $("." + cls).addClass('disable_link');
            }

            function globalDisableByClass(stepCounter, studyClsStr, stepClsStr) {
                $("." + studyClsStr).prop('disabled', true);
            }

            function enableByClass(cls) {
                //console.log('enableByClass : ' + cls);
                $("." + cls).prop('disabled', false);
                $("." + cls + " :input").prop('disabled', false);
                $("." + cls + " select").prop('disabled', false);
                $("." + cls + " textarea").prop("disabled", false);
            }

            function enableLinkByClass(cls) {
                $("." + cls).removeClass('disable_link');
            }

            function submitForm(stepIdStr, formType, formStatusIdStr) {
                var submitFormFlag = true;
                if (isFormInEditMode(stepIdStr)) {
                    if (checkReason(stepIdStr) == false) {
                        submitFormFlag = false;
                    }
                }
                if (submitFormFlag) {
                    var term_cond = $('#terms_cond_' + stepIdStr).val();
                    var reason = $('#edit_reason_text_' + stepIdStr).val();
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr).serialize() +
                        '&terms_cond_' + stepIdStr + '=' + term_cond + '&' + 'edit_reason_text=' + reason;
                    submitRequest(frmData, stepIdStr, formType, formStatusIdStr);
                    reloadPage(2);
                }
            }

            function putResponseImage(stepIdStr, responseImage, formType, formStatusIdStr) {
                if (formType == 'Grading' || formType == 'Eligibility') {
                    if ($('.' + formStatusIdStr).length != 0) {
                        $('.' + formStatusIdStr).html('<img src="{{ url('/') . '/images/' }}' + responseImage + '.png"/>');
                    } else {
                        putImageOnStepLevel(stepIdStr, responseImage);
                    }
                } else {
                    putImageOnStepLevel(stepIdStr, responseImage);
                }
            }

            function putImageOnStepLevel(stepIdStr, responseImage) {
                $('.img_step_status_' + stepIdStr + ':first').html('<img src="{{ url('/') . '/images/' }}' + responseImage + '.png"/>');
            }

            function putNotRequiredImage(skipLogicCls) {
                $('.img_step_status_' + skipLogicCls).html('<img src="{{ url('/') . '/images/' }}not_required.png"/>');
            }

            function putRequiredImage(skipLogicCls) {
                $('.img_step_status_' + skipLogicCls).html('<img src="{{ url('/') . '/images/' }}no_status.png"/>');
            }

            function submitRequest(frmData, stepIdStr, formType, formStatusIdStr) {
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestionForm') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        putResponseImage(stepIdStr, response.formStatus, response.formType, response.formStatusIdStr);
                    }
                });
            }

            function submitFieldRequest(frmData, stepIdStr) {
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestion') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(responseData) {
                        response = responseData.status;
                        answer = responseData.answer;
                        putResponseImage(stepIdStr, response.formStatus, response.formType, response.formStatusIdStr);
                    }
                });
            }

            function validateFormField(stepIdStr, questionId, field_name, fieldId) {
                var field_val;
                field_val = getFormFieldValue(stepIdStr, field_name, fieldId);
                var frmData = $("#form_master_" + stepIdStr).serialize() + '&questionId=' + questionId + '&' + field_name + '=' + field_val;
                return validateSingleQuestion(frmData);

            }

            function validateForm(stepIdStr) {
                return new Promise(function(resolve, reject) {
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr)
                        .serialize();
                    $.ajax({
                        url: "{{ route('subjectFormSubmission.validateSectionQuestionsForm') }}",
                        type: 'POST',
                        data: frmData,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.success == 'no') {
                                reject(response.error);
                            } else {
                                resolve(response.success);
                            }
                        }
                    });
                })
            }

            function validateSingleQuestion(frmData) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('subjectFormSubmission.validateSingleQuestion') }}",
                        type: 'POST',
                        data: frmData,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.success == 'no') {
                                reject(response.error);
                            } else {
                                resolve(response.success);
                            }
                        }
                    });
                })
            }

            function validateAndSubmitForm(stepIdStr, formType, formStatusIdStr) {
                if (isPreview === false) {
                    if (isFormDataLocked(stepIdStr) == false) {
                        if (canSubmitForm(formType, stepIdStr)) {
                            if (needToPutFormInEditMode(stepIdStr) == false) {
                                if (window['validateStep' + stepIdStr]()) {
                                    submitForm(stepIdStr, formType, formStatusIdStr);
                                }
                            } else {
                                showPutFormInEditModeError();
                            }
                        } else {
                            showPermissionError();
                        }
                    } else {
                        showDataLockError();
                    }
                }
            }

            function validateAndSubmitField(stepIdStr, sectionIdStr, questionId, questionIdStr, formType, field_name,
                fieldId,dependencyIdStr) {

                if (isPreview === false) {
                    if (isFormDataLocked(stepIdStr) == false) {
                      
                        if (canSubmitForm(formType, stepIdStr)) {

                            if (needToPutFormInEditMode(stepIdStr) == false) {

                                if (window['validateQuestion' + questionIdStr](true, stepIdStr)) {

                                    if (eval("typeof " + window['showHideQuestion' + questionIdStr+ '_' + dependencyIdStr]) != 'undefined') {

                                        window['showHideQuestion' + questionIdStr+ '_' + dependencyIdStr](stepIdStr);
                                    }
                                    if (eval("typeof " + window['runCalculatedFieldsFunctions' + stepIdStr]) !=
                                        'undefined') {
                                        window['runCalculatedFieldsFunctions' + stepIdStr](questionIdStr);
                                    }
                                    if (eval("typeof " + window['checkQuestionSkipLogic' + questionIdStr]) != 'undefined') {
                                        window['checkQuestionSkipLogic' + questionIdStr]();
                                    }
                                    submitFormField(stepIdStr, questionId, field_name, fieldId);
                                }
                            } else {
                                showPutFormInEditModeError();
                            }
                        } else {
                            showPermissionError();
                        }
                    } else {
                        showDataLockError();
                    }
                }
            }

            function handleValidationErrors(error) {
                showAlert('Alert', error, 'error');
            }

            function checkTermCond(stepIdStr) {
                if ($('#terms_cond_' + stepIdStr).prop('checked')) {
                    return true;
                } else {
                    showAlert('Alert',
                        'Please acknowledge the truthfulness and correctness of information being submitting in this form!',
                        'error'
                    );
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
                    showAlert('Alert', 'Please tell the reason to edit', 'error');
                } else {
                    returnVal = true;
                }
                return returnVal;
            }

            function submitFormField(stepIdStr, questionId, field_name, fieldId) {
                var frmData = $("#form_master_" + stepIdStr).serialize();
                var field_val;
                field_val = getFormFieldValue(stepIdStr, field_name, fieldId);
                frmData = frmData + '&' + field_name + '=' + field_val + '&' + 'questionId=' + questionId;
                submitFieldRequest(frmData, stepIdStr);

            }

            function getFormFieldValue(stepIdStr, field_name, fieldId) {
                var field_val = '';
                var checkedCheckBoxes = [];

                if ($('#' + fieldId + '_' + stepIdStr).prop('type') == "file") {
                    var file = document.getElementById(fieldId + '_' + stepIdStr);
                    var divHtml = document.getElementById('file_upload_files_div_' + fieldId).innerHTML;
                    if(file.files.length == 0 && divHtml == '' ){
                        field_val = '';
                    }else{
                        field_val = 'hasFiles';
                    }
                }else if ($('#form_' + stepIdStr + ' #' + fieldId).is("textarea")) {
                    field_val = $('#form_' + stepIdStr + ' #' + fieldId).val();
                } else if ($('#form_' + stepIdStr + ' #' + fieldId).is("select")) {
                    field_val = $('#form_' + stepIdStr + ' #' + fieldId).find(":selected").val();
                } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').prop('type') == 'radio') {
                    field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]').prop('type') == 'checkbox') {

                    $('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]:checked').each(function() {
                        checkedCheckBoxes.push($(this).val());
                    });
                    field_val = checkedCheckBoxes.join(",");

                } else {
                    field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                }
                return field_val;
            }

            function getFormFieldValueForRequired(stepIdStr, field_name, fieldId) {
                var field_val = '';
                var field_val_for_disabled = 'disabledField';
                var checkedCheckBoxes = [];

                if ($('#' + fieldId + '_' + stepIdStr).prop('type') == "file") {
                    if ($('#' + fieldId + '_' + stepIdStr).prop('disabled') == false) {
                        var file = document.getElementById(fieldId + '_' + stepIdStr);
                        var divHtml = document.getElementById('file_upload_files_div_' + fieldId).innerHTML;
                        if(file.files.length == 0 && divHtml == '' ){
                            field_val = '';
                        }
                    }else{
                        field_val = field_val_for_disabled;
                    }
                }else if ($('#form_' + stepIdStr + ' #' + fieldId).is("textarea")) {
                    if ($('#form_' + stepIdStr + ' #' + fieldId).prop('disabled') == false) {
                        field_val = $('#form_' + stepIdStr + ' #' + fieldId).val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else if ($('#form_' + stepIdStr + ' #' + fieldId).is("select")) {
                    if ($('#form_' + stepIdStr + ' #' + fieldId).prop('disabled') == false) {
                        field_val = $('#form_' + stepIdStr + ' #' + fieldId).find(":selected").val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').prop('type') == 'radio') {
                    if ($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').prop('disabled') == false) {
                        field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]').prop('type') == 'checkbox') {
                    if ($('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]').prop('disabled') == false) {
                        $('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]:checked').each(function() {
                            checkedCheckBoxes.push($(this).val());
                        });
                        field_val = checkedCheckBoxes.join(",");
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else {
                    if($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').prop('disabled') == false){
                        field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                }
                return field_val;
            }

            function openFormForEditing(stepIdStr, stepClsStr, formType, formStatusIdStr) {
                if (isFormDataLocked(stepIdStr) == false) {
                    if (canSubmitForm(formType, stepIdStr)) {
                        var frmData = $("#form_master_" + stepIdStr).serialize();
                        frmData = frmData + '&' + 'open_form_to_edit=1';
                        $.ajax({
                            url: "{{ route('SubjectFormSubmission.openSubjectFormToEdit') }}",
                            type: 'POST',
                            data: frmData,
                            success: function(response) {
                                showReasonField(stepIdStr, stepClsStr, formType, formStatusIdStr);
                                reloadPage(0);
                            }
                        });
                    } else {
                        showPermissionError();
                    }
                } else {
                    showDataLockError();
                }
            }

            function lockFormData(stepIdStr) {
                if (canLockFormData() == true) {
                    var frmData = $("#form_master_" + stepIdStr).serialize();
                    $.ajax({
                        url: "{{ route('SubjectFormSubmission.lockFormData') }}",
                        type: 'POST',
                        data: frmData,
                        success: function(response) {
                            $('#form_master_' + stepIdStr + ' input[name="isFormDataLocked"]').val(response);
                            $('#lock_data_button_' + stepIdStr).hide('slow');
                            $('#unlock_data_button_' + stepIdStr).show('slow');
                            showAlert('Data lock status', 'Data locked successfully!', 'info');
                        }
                    });
                } else {
                    showAlert('Data lock status', 'You can not lock data!', 'error');
                }
            }

            function unlockFormData(stepIdStr) {
                if (canLockFormData() == true) {
                    var frmData = $("#form_master_" + stepIdStr).serialize();
                    $.ajax({
                        url: "{{ route('SubjectFormSubmission.unlockFormData') }}",
                        type: 'POST',
                        data: frmData,
                        success: function(response) {
                            $('#form_master_' + stepIdStr + ' input[name="isFormDataLocked"]').val(response);
                            $('#unlock_data_button_' + stepIdStr).hide('slow');
                            $('#lock_data_button_' + stepIdStr).show('slow');
                            showAlert('Data lock status', 'Data unlocked successfully!', 'info');
                        }
                    });
                } else {
                    showAlert('Data lock status', 'You can not unlock data!', 'error');
                }
            }

            function showReasonField(stepIdStr, stepClsStr, formType, formStatusIdStr) {
                $("#edit_form_div_" + stepIdStr).show(500);
                $("#edit_form_button_" + stepIdStr).hide(500);
                $('#edit_reason_text_' + stepIdStr).prop('required', true);
                enableByClass(stepClsStr);
                $('.form_hid_editing_status_' + stepIdStr).val('yes');
                putResponseImage(stepIdStr, 'resumable', formType, formStatusIdStr);
            }

            function hideReasonField(stepIdStr, stepClsStr, formType, formStatusIdStr, waitSeconds) {
                //console.log('hideReasonField : ' + stepIdStr + ' - ' + stepClsStr + ' - ' + formType + ' - ' + formStatusIdStr + ' - ' + waitSeconds);
                startWait();
                var seconds = waitSeconds * 1000;
                //console.log('wait : ' + seconds);
                setTimeout(function() {
                    $("#edit_form_div_" + stepIdStr).hide(500);
                    $('#edit_reason_text_' + stepIdStr).prop('required', false);
                    $('#edit_reason_text_' + stepIdStr).val('');
                    disableByClass(stepClsStr);
                    $('.form_hid_editing_status_' + stepIdStr).val('no');
                    $('.nav-link').removeClass('active');
                    $('.first_navlink_' + stepIdStr).addClass('active');
                    $('.tab-pane_' + stepIdStr).removeClass('active show');
                    $('.first_tab_' + stepIdStr).addClass('active show');
                    putResponseImage(stepIdStr, 'complete', formType, formStatusIdStr);
                    endWait();
                }, seconds);
            }

            function startWait() {
                $("#waitModal").modal('show');
            }

            function endWait() {
                $("#waitModal").modal('hide');
            }

            function openAssignPhasesToSubjectPopup(studyId, subjectId) {
                $("#assignPhasesToSubjectPopup").modal('show');
                loadAssignPhaseToSubjectForm(studyId, subjectId)
            }

            function loadAssignPhaseToSubjectForm(studyId, subjectId) {
                $.ajax({
                    url: "{{ route('assignPhaseToSubject.loadAssignPhaseToSubjectForm') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'studyId': studyId
                    },
                    success: function(response) {
                        $('#assignPhaseToSubjectMainDiv').empty();
                        $("#assignPhaseToSubjectMainDiv").html(response);
                    }
                });
            }

            function submitAssignPhaseToSubjectForm(e) {
                e.preventDefault();
                $("#assignPhasesToSubjectPopup").modal('hide');
                startWait();
                $.ajax({
                    url: "{{ route('assignPhaseToSubject.submitAssignPhaseToSubjectForm') }}",
                    type: 'POST',
                    data: $("#assignPhaseToSubjectForm").serialize(),
                    success: function(response) {
                        $('#assignPhaseToSubjectMainDiv').empty();
                        reloadPage(0);
                    }
                });

            }

            function unAssignPhaseToSubject(subjectId, phaseId) {
                $.confirm({
                    columnClass: 'col-md-6',
                    title: 'Confirm to deactivate visit!',
                    content: 'Are you sure to deactivate visit',
                    buttons: {
                        yeDeactivateVisit: {
                            text: 'Yes deactivate vist',
                            btnClass: 'btn-green',
                            keys: ['enter', 'shift'],
                            action: function() {
                                confirmation = 'deactivate';
                                unAssignPhaseToSubjectAjax(subjectId, phaseId);
                            }
                        },
                        cancelDeactivation: {
                            text: 'Cancel',
                            btnClass: 'btn-red',
                            keys: ['enter', 'shift'],
                            action: function() {
                                confirmation = 'cancel';
                            }
                        }
                    }
                });

            }

            function unAssignPhaseToSubjectAjax(subjectId, phaseId) {
                startWait();
                $.ajax({
                    url: "{{ route('assignPhaseToSubject.unAssignPhaseToSubject') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'phaseId': phaseId
                    },
                    success: function(response) {
                        changeUrl_after_deactivate_visit()
                        reloadPage(1);
                    }
                });
            }

            function isFormDataLocked(stepIdStr) {
                var isFormDataLocked = $('#form_master_' + stepIdStr + ' input[name="isFormDataLocked"]').val();
                if (isFormDataLocked == 1) {
                    return true;
                } else {
                    return false;
                }
            }

            function canLockFormData() {
                var canManageData = {{ canManageData(['create', 'store', 'edit', 'update']) ? 'true' : 'false' }};
                userCanLockFormData = false;
                if (canManageData == true) {
                    userCanLockFormData = true;
                }
                return userCanLockFormData;
            }

            function canSubmitForm(formType, stepIdStr) {

                var numberOfGraders = $('#form_master_' + stepIdStr + ' input[name="numberOfGraders"]').val();
                var numberOfAlreadyGradedPersons = $('#form_master_' + stepIdStr + ' input[name="numberOfAlreadyGradedPersons"]').val();

                var canQualityControl = {{ canQualityControl(['create', 'store', 'edit', 'update']) ? 'true' : 'false' }};

                var canGrading = {{ canGrading(['create', 'store', 'edit', 'update']) ? 'true' : 'false' }};
                var canEligibility = {{ canEligibility(['create', 'store', 'edit', 'update']) ? 'true' : 'false' }};
                var canAdjudication = {{ canAdjudication(['create', 'store', 'edit', 'update']) ? 'true' : 'false' }};
                var canSubmit = false;
                var formStatus = $('#form_master_' + stepIdStr + ' input[name="formStatus"]').val();
                var formFilledByUserId = $('#form_master_' + stepIdStr + ' input[name="formFilledByUserId"]').val();
                var current_user_id = '{{ auth()->user()->id }}';

                console.log('formType : ' + formType);
                console.log('canQualityControl : ' + canQualityControl);
                console.log('formStatus : ' + formStatus);
                console.log('formFilledByUserId : ' + formFilledByUserId);
                console.log('current_user_id : ' + current_user_id);
                console.log('canGrading : ' + canGrading);
                console.log('canEligibility : ' + canEligibility);
                console.log('numberOfGraders : ' + numberOfGraders);
                console.log('numberOfAlreadyGradedPersons : ' + numberOfAlreadyGradedPersons);

                if (
                    (formType == 'QC') &&
                    (canQualityControl == true)
                ) {

                    if ((formStatus == 'no_status') && (formFilledByUserId == 'no-user-id')) {
                        canSubmit = true;
                    }
                    if ((formStatus != 'no_status') && (formFilledByUserId == current_user_id)) {
                        canSubmit = true;
                    }
                }

                if(
                    ((formType == 'Grading') && (canGrading == true)) ||
                    ((formType == 'Eligibility') && (canEligibility == true))
                ){
                    if ((formStatus == 'no_status') && (formFilledByUserId == 'no-user-id')) {
                        canSubmit = true;
                    }
                    if ((formStatus != 'no_status') && (formFilledByUserId == current_user_id)) {
                        canSubmit = true;
                    }
                }

                // ((formType == 'Grading') && (canGrading == true)) &&
                if (((formType == 'Grading') && (canGrading == true)) && (numberOfGraders == numberOfAlreadyGradedPersons) &&
                    (formFilledByUserId != current_user_id)
                ) {

                    canSubmit = false;
                }

                return canSubmit;
            }

            function needToPutFormInEditMode(stepIdStr) {
                var putFormInEditMode = false;
                var formStatus = $('#form_master_' + stepIdStr + ' input[name="formStatus"]').val();
                var isFormInEditMode = $('#form_editing_status_' + stepIdStr).val();

                if ((formStatus == 'complete') && (isFormInEditMode == 'no')) {
                    putFormInEditMode = true;
                }
                return putFormInEditMode;
            }

            function showPermissionError() {
                showAlert('Alert', 'You do not have permission to submit form', 'error');
            }

            function showPutFormInEditModeError() {
                showAlert('Alert', 'Please put form in edit mode first', 'error');
            }

            function showDataLockError() {
                showAlert('Data lock status', 'Form data is locked, you can not change data!', 'error');
            }

            function calculateField(firstFieldId, secondFieldId, operator, make_decision, customVal, stepIdStr,
                sectionIdStr, questionId, questionIdStr, formType, field_name, fieldId) {
                if (isPreview === false) {
                    var firstVal = 0;
                    var secondVal = 0;
                    customVal = Number(customVal);

                    var firstFieldName = $("#" + firstFieldId).attr("name");
                    var firstFieldVal = getFormFieldValue(stepIdStr, firstFieldName, firstFieldId);
                    firstVal = Number(firstFieldVal);

                    if (make_decision == 'question') {
                        var secondFieldName = $("#" + secondFieldId).attr("name");
                        var secondFieldVal = getFormFieldValue(stepIdStr, secondFieldName, secondFieldId);
                        secondVal = Number(secondFieldVal);
                    }

                    if (make_decision == 'custom') {
                        secondVal = customVal;
                    }

                    var answer = 0;
                    if (operator == '+') {
                        answer = firstVal + secondVal;
                    } else if (operator == '-') {
                        answer = firstVal - secondVal;
                    } else if (operator == '*') {
                        answer = firstVal * secondVal;
                    } else if (operator == '/') {
                        if (firstVal > 0 && secondVal > 0) {
                            answer = firstVal / secondVal;
                        } else {
                            $('#' + fieldId).val(0);
                        }
                    }

                    $('#form_' + stepIdStr + ' #' + fieldId).val(answer);
                    validateAndSubmitField(stepIdStr, sectionIdStr, questionId, questionIdStr, formType, field_name,
                        fieldId);
                }
            }

            function updateCurrentPhaseId(phaseId) {
                $('#current_phase_id').val(phaseId);
                $('#current_step_id').val('-');
                $('#current_section_id').val('-');
                changeUrl();
            }

            function updateCurrentStepId(phaseId, stepId, isAdjudication) {
                var stepIdStr =   stepId.replace(/-/g, "_");
                $('a').removeClass('selected_form');
                $('a').removeClass('selected_form_adj');
                if(isAdjudication =='yes'){
                    $('.adj_step_cls_'+stepIdStr).addClass('selected_form');
                }else{
                    $('.step_cls_'+stepIdStr).addClass('selected_form');
                }
                $('#current_phase_id').val(phaseId);
                $('#current_step_id').val(stepId);
                $('#current_section_id').val('-');
                $('#isAdjudication').val(isAdjudication);
                changeUrl();
                if($("#form_master_" + stepIdStr).length == 0 && $("#form_" + stepIdStr).length == 0) {
                    $('.loader').css('display','block');
                    reloadPage(0);
                }
            }

            function updateCurrentSectionId(phaseId, stepId, sectionId) {
                $('#current_phase_id').val(phaseId);
                $('#current_step_id').val(stepId);
                $('#current_section_id').val(sectionId);
                changeUrl();
            }

            function changeUrl() {
                if (isPreview === false) {
                    var phaseId = $('#current_phase_id').val();
                    var stepId = $('#current_step_id').val();
                    var sectionId = $('#current_section_id').val();
                    var showAllQuestions = $('#showAllQuestions').val();
                    var isAdjudication = $('#isAdjudication').val();
                    var title = 'new title';
                    var url = "{{ url('/') }}/subjectFormLoader/{{ $studyId }}/{{ $subjectId }}/" + phaseId + '/' + stepId +
                        '/' + sectionId + '/' + isAdjudication + '/' + showAllQuestions;
                    if (typeof(history.pushState) != "undefined") {
                        var obj = {
                            Title: title,
                            Url: url
                        };
                        history.pushState(obj, obj.Title, obj.Url);
                    } else {
                        alert("Browser does not support HTML5.");
                    }
                }
            }
            function changeUrl_after_deactivate_visit() {
                    var title = 'new title';
                    var url = "{{ url('/') }}/subjectFormLoader/{{ $studyId }}/{{ $subjectId }}";
                    if (typeof(history.pushState) != "undefined") {
                        var obj = {
                            Title: title,
                            Url: url
                        };
                        history.pushState(obj, obj.Title, obj.Url);
                    } else {
                        alert("Browser does not support HTML5.");
                    }
            }
            function validateAndUploadFiles(stepIdStr, sectionIdStr, questionId, questionIdStr, formType, field_name,
                fieldId) {
                if (isPreview === false) {
                    if (isFormDataLocked(stepIdStr) == false) {
                        if (canSubmitForm(formType, stepIdStr)) {
                            if (needToPutFormInEditMode(stepIdStr) == false) {
                                if (window['validateQuestion' + questionIdStr](true, stepIdStr)) {
                                    submitFormFileField(stepIdStr, questionId, field_name, fieldId);
                                }
                            } else {
                                showPutFormInEditModeError();
                            }
                        } else {
                            showPermissionError();
                        }
                    } else {
                        showDataLockError();
                    }
                }
            }

            function submitFormFileField(stepIdStr, questionId, field_name, fieldId) {
                var frmData = new FormData(document.getElementById("form_master_" + stepIdStr));
                var field_val;
                frmData.append('questionId', questionId);
                var fileField = document.getElementById(fieldId + '_' + stepIdStr);
                let TotalFiles = fileField.files.length;
                for (let i = 0; i < TotalFiles; i++) {
                    var fileSize = fileField.files[i].size;
                    if(fileSize < upload_max_filesize && fileSize < post_max_size){
                        frmData.append(field_name + i, fileField.files[i]);
                    }else{

                        console.log(fileSize);
                    }
                }
                frmData.append('TotalFiles', TotalFiles);
                submitFileFieldRequest(frmData, stepIdStr, fieldId);
            }

            function submitFileFieldRequest(frmData, stepIdStr, fieldId){
                $('#file_upload_files_div_' + fieldId).html('Uploading files ....');
                var url = "{{ url('/') }}/";
                $.ajax({
                    type:'POST',
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestion') }}",
                    data: frmData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (responseData) => {
                        response = responseData.status;
                        answer = responseData.answer;
                        answerId = responseData.answerId;
                        putResponseImage(stepIdStr, response.formStatus, response.formType, response.formStatusIdStr);
                        var answerArray = answer.split('<<|!|>>');
                        var linkStr = '';
                        for (i = 0; i < answerArray.length; i++) {
                            if(answerArray[i] != ''){
                                linkStr += '<div id="'+answerArray[i]+'"><a href="' + url + '/form_files/' + answerArray[i] + '" target="_blank">' + answerArray[i] + '</a>&nbsp;&nbsp;<img onclick="deleteFormUploadFile(\''+answerId+'\', \''+answerArray[i]+'\');" src="' + url + '/images/remove.png"></div>';
                            }
                        }
                        $('#file_upload_files_div_' + fieldId).html(linkStr);
                    },
                    error: function(response){
                        //console.log(response);
                    }
                });
            }

            function deleteFormUploadFile(answerId, fileName){
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.deleteFormUploadFile') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'answerId': answerId,
                        'fileName': fileName
                    },
                    success: function(response) {
                        var fileDiv = document.getElementById(fileName);
                        fileDiv.remove();
                    }
                });
            }

            function loadQuestionCommentPopup(studyId, subjectId, phaseId, stepId, sectionId, questionId) {
                $("#questionCommentPopup").modal('show');
                $.ajax({
                    url: "{{ route('questionComment.loadQuestionCommentPopup') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'studyId': studyId,
                        'phaseId': phaseId,
                        'stepId': stepId,
                        'sectionId': sectionId,
                        'questionId': questionId,
                    },
                    success: function(response) {
                        $('#questionCommentDiv').empty();
                        $("#questionCommentDiv").html(response);
                    }
                });
            }

            function loadAddQuestionCommentForm(studyId, subjectId, phaseId, stepId, sectionId, questionId) {
                $("#questionCommentPopup").modal('hide');
                $("#addQuestionCommentPopup").modal('show');
                $.ajax({
                    url: "{{ route('questionComment.loadAddQuestionCommentForm') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'studyId': studyId,
                        'phaseId': phaseId,
                        'stepId': stepId,
                        'sectionId': sectionId,
                        'questionId': questionId,
                    },
                    success: function(response) {
                        $('#addQuestionCommentDiv').empty();
                        $("#addQuestionCommentDiv").html(response);
                    }
                });
            }

            function submitAddQuestionCommentForm(e) {
                e.preventDefault();
                var studyId = $('#addQuestionCommentForm #studyId').val();
                var subjectId = $('#addQuestionCommentForm #subjectId').val();
                var phaseId = $('#addQuestionCommentForm #phaseId').val();
                var stepId = $('#addQuestionCommentForm #stepId').val();
                var sectionId = $('#addQuestionCommentForm #sectionId').val();
                var questionId = $('#addQuestionCommentForm #questionId').val();

                $("#assignPhasesToSubjectPopup").modal('hide');
                $.ajax({
                    url: "{{ route('questionComment.submitAddQuestionCommentForm') }}",
                    type: 'POST',
                    data: $("#addQuestionCommentForm").serialize(),
                    success: function(response) {
                        $('#addQuestionCommentDiv').empty();
                        $("#addQuestionCommentPopup").modal('hide');
                        loadQuestionCommentPopup(studyId, subjectId, phaseId, stepId, sectionId, questionId)
                    }
                });

            }

            function openShowQuestionsToGraderPopUp(studyId, subjectId, phaseId, stepId) {
                $("#qcQuestionsToShowPopup").modal('show');
                $.ajax({
                    url: "{{ route('qcQuestionToShow.openShowQuestionsToGraderPopUp') }}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'studyId': studyId,
                        'phaseId': phaseId,
                        'stepId': stepId
                    },
                    success: function(response) {
                        $('#qcQuestionsToShowDiv').empty();
                        $("#qcQuestionsToShowDiv").html(response);
                    }
                });
            }

            function reloadPage(waitSeconds) {
                var seconds = waitSeconds * 1000;
                //console.log('wait : ' + seconds);
                setTimeout(function() {
                    location.reload();
                }, seconds);
            }
        </script>
    @endpush
