    @push('styles')
        @include('formsubmission::forms.form_css')
    @endpush

    @push('script')
        <script>
            function showAlert(swalTitle, message, messageType) {
                swal.fire({
                    title: swalTitle,
                    text: message,
                    icon: messageType,
                    dangerMode: true,
                });
                /*
                var field = $("#previous_alert_message");
                var previous_alert_message = field.val();
                if(previous_alert_message != message){
                    swal("Alert", message, "error");
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

            function showAdjudication(step_id_class) {
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

            function globalDisableByClass(stepCounter, studyClsStr, stepClsStr) {
                //if (stepCounter < $('#already_global_disabled').val()) {
                    $("." + studyClsStr).prop('disabled', true);
                   // $('#already_global_disabled').val(stepCounter);
                    //enableByClass(stepClsStr);
                //}
            }

            function enableByClass(cls) {
                $("." + cls).prop('disabled', false);
            }

            function submitForm(stepIdStr, formTypeId, formStatusIdStr) {
                var submitFormFlag = true;
                if (isFormInEditMode(stepIdStr)) {
                    if (checkReason(stepIdStr) == false) {
                        submitFormFlag = false;
                    }
                }
                if (submitFormFlag) {
                    var term_cond = $('#terms_cond_' + stepIdStr).val();
                    var reason = $('#edit_reason_text_' + stepIdStr).val();
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr)
                        .serialize() +
                        '&terms_cond_' + stepIdStr + '=' + term_cond + '&' + 'edit_reason_text=' + reason;
                    submitRequest(frmData, stepIdStr, formTypeId, formStatusIdStr);
                }
            }

            function putResponseImage(stepIdStr, responseImage, formTypeId, formStatusIdStr){
                if(formTypeId == 2){
                    if($('.' + formStatusIdStr).length != 0){
                        $('.' + formStatusIdStr).html('<img src="{{ url('/').'/images/' }}' + responseImage + '.png"/>');
                    }else{
                        putImageOnStepLevel(stepIdStr, responseImage);
                    }
                }else{
                    putImageOnStepLevel(stepIdStr, responseImage);
                }
            }

            function putImageOnStepLevel(stepIdStr, responseImage){
                $('.img_step_status_' + stepIdStr+':first').html('<img src="{{ url('/').'/images/' }}' + responseImage + '.png"/>');
            }

            function submitRequest(frmData, stepIdStr, formTypeId, formStatusIdStr) {
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestionForm') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        putResponseImage(stepIdStr, response.formStatus, response.formTypeId, response.formStatusIdStr);
                    }
                });
            }

            function submitFieldRequest(frmData, stepIdStr) {
                $.ajax({
                    url: "{{ route('SubjectFormSubmission.submitStudyPhaseStepQuestion') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        putResponseImage(stepIdStr, response.formStatus, response.formTypeId, response.formStatusIdStr);
                    }
                });
            }

            function validateFormField(stepIdStr, questionId, field_name, fieldId) {
                var field_val;
                field_val = getFormFieldValue(stepIdStr, field_name, fieldId);
                var frmData = $("#form_master_" + stepIdStr).serialize() + '&questionId=' + questionId + '&' + field_name +
                    '=' + field_val;
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

            function validateAndSubmitForm(stepIdStr, formTypeId, formStatusIdStr) {
                if(canSubmitForm(formTypeId,stepIdStr)){
                    const promise = validateForm(stepIdStr);
                    promise
                        .then((data) => {
                            console.log(data);
                            submitForm(stepIdStr, formTypeId, formStatusIdStr);
                        })
                        .catch((error) => {
                            console.log(error);
                            handleValidationErrors(error);
                        });
                }else{
                    showPermissionError();
                }

            }

            function validateAndSubmitField(stepIdStr, sectionIdStr, questionId, formTypeId, field_name, fieldId) {
                if(canSubmitForm(formTypeId,stepIdStr)){
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
                }else{
                    showPermissionError();
                }
            }

            function checkIsThisFieldDependent(sectionIdStr, questionId, field_name, fieldId) {}

            function validateDependentFields(sectionIdStr, questionId, field_name, fieldId) {}

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
                var submitFormFlag = true;
                if (submitFormFlag) {
                    var frmData = $("#form_master_" + stepIdStr).serialize();
                    var field_val;
                    field_val = getFormFieldValue(stepIdStr, field_name, fieldId);
                    frmData = frmData + '&' + field_name + '=' + field_val + '&' + 'questionId=' + questionId;
                    submitFieldRequest(frmData, stepIdStr, '', '');
                }
            }

            function getFormFieldValue(stepIdStr, field_name, fieldId) {
                var field_val;
                var checkedCheckBoxes = [];
                if ($('#' + fieldId).is("textarea")) {
                    field_val = $('#' + fieldId).val();
                } else if ($('#' + fieldId).is("select")) {
                    field_val = $('#' + fieldId).find(":selected").val();
                } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '"]').attr('type') == 'radio') {
                    field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                } else if ($('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]').attr('type') == 'checkbox') {

                    $('#form_' + stepIdStr + ' input[name="' + field_name + '[]"]:checked').each(function() {
                        checkedCheckBoxes.push($(this).val());
                    });
                    field_val = checkedCheckBoxes.join(",");

                } else {
                    field_val = $('#form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                }
                return field_val;
            }

            function openFormForEditing(stepIdStr, stepClsStr, formTypeId, formStatusIdStr) {
                if(canSubmitForm(formTypeId,stepIdStr)){
                    var frmData = $("#form_master_" + stepIdStr).serialize();
                    frmData = frmData + '&' + 'open_form_to_edit=1';
                    $.ajax({
                        url: "{{ route('SubjectFormSubmission.openSubjectFormToEdit') }}",
                        type: 'POST',
                        data: frmData,
                        success: function(response) {
                            showReasonField(stepIdStr, stepClsStr, formTypeId, formStatusIdStr);
                        }
                    });
                }else{
                    showPermissionError();
                }
            }

            function showReasonField(stepIdStr, stepClsStr, formTypeId, formStatusIdStr) {
                $("#edit_form_div_" + stepIdStr).show(500);
                $("#edit_form_button_" + stepIdStr).hide(500);
                $('#edit_reason_text_' + stepIdStr).prop('required', true);
                enableByClass(stepClsStr);
                $('.form_hid_editing_status_' + stepIdStr).val('yes');
                putResponseImage(stepIdStr, 'resumable', formTypeId, formStatusIdStr);
            }

            function hideReasonField(stepIdStr, stepClsStr, formTypeId, formStatusIdStr) {
                $("#edit_form_div_" + stepIdStr).hide(500);
                $('#edit_reason_text_' + stepIdStr).prop('required', false);
                $('#edit_reason_text_' + stepIdStr).val('');
                disableByClass(stepClsStr);
                $('.form_hid_editing_status_' + stepIdStr).val('no');
                $('.nav-link').removeClass('active');
                $('.first_navlink_' + stepIdStr).addClass('active');
                $('.tab-pane_' + stepIdStr).removeClass('active show');
                $('.first_tab_' + stepIdStr).addClass('active show');
                putResponseImage(stepIdStr, 'complete', formTypeId, formStatusIdStr);
            }

            function startWait(){
                $("#waitModal").modal();
            }

            function wait(){
                while(start_wait == 'yes'){
                    console.log('wait');
                }
            }

            function endWait(){
                start_wait = 'no';
                console.log('endWait');
            }

            function openAssignPhasesToSubjectPopup(studyId, subjectId){
                $("#assignPhasesToSubjectPopup").modal('show');
                loadAssignPhaseToSubjectForm(studyId, subjectId)
            }
            function loadAssignPhaseToSubjectForm(studyId, subjectId){
                $.ajax({
                    url: "{{route('assignPhaseToSubject.loadAssignPhaseToSubjectForm')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'studyId': studyId
                    },
                    success: function(response){
                        $('#assignPhaseToSubjectMainDiv').empty();
                        $("#assignPhaseToSubjectMainDiv").html(response);
                    }
                });
            }
            function submitAssignPhaseToSubjectForm(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('assignPhaseToSubject.submitAssignPhaseToSubjectForm')}}",
                    type: 'POST',
                    data: $( "#assignPhaseToSubjectForm" ).serialize(),
                    success: function(response){
                        $("#assignPhasesToSubjectPopup").modal('hide');
                        $('#assignPhaseToSubjectMainDiv').empty();
                        reloadPage(0);
                    }
                });

            }

            function canSubmitForm(formTypeId, stepIdStr){

                var canQualityControl = {{ (canQualityControl(['create', 'store', 'edit', 'update']))? 'true':'false' }};
                var canGrading = {{ (canGrading(['create', 'store', 'edit', 'update']))? 'true':'false' }};
                var canAdjudication = {{ (canAdjudication(['create', 'store', 'edit', 'update']))? 'true':'false' }};
                var canSubmit = false;
                var formStatus = $('#form_master_' + stepIdStr + ' input[name="formStatus"]').val();
                var formFilledByUserId = $('#form_master_' + stepIdStr + ' input[name="formFilledByUserId"]').val();
                var current_user_id = '{{ auth()->user()->id }}';

                if(
                    (formTypeId == 1) &&
                    (canQualityControl == true) &&
                    (
                    ((formStatus == 'no_status') && (formFilledByUserId == 'no-user-id')) ||
                    ((formStatus != 'no_status') && (formFilledByUserId == current_user_id))
                    )

                ){
                    canSubmit = true;
                }
                if(
                    (formTypeId == 2) && (canGrading == true) &&
                    (
                    ((formStatus == 'no_status') && (formFilledByUserId == 'no-user-id')) ||
                    ((formStatus != 'no_status') && (formFilledByUserId == current_user_id))
                    )
                ){
                    canSubmit = true;
                }
                return canSubmit;
            }

            function showPermissionError(){
                showAlert('Alert', 'You do not have permission to submit form', 'error');
            }

            function reloadPage(waitSeconds) {
                startWait();
                var seconds = waitSeconds * 1000;
                setTimeout(function() {
                   location.reload();
               }, seconds);
            }

        </script>
    @endpush
