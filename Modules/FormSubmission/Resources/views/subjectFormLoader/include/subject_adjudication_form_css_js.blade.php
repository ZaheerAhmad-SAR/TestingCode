    @push('script')
        <script>
            function putAdjudicationImage(responseImage, adjudicationFormStatusIdStr){
                $('.' + adjudicationFormStatusIdStr).html('<img src="{{ url('/').'/images/' }}' + responseImage + '.png"/>');
            }

            function submitAdjudicationForm(stepIdStr, formStatusIdStr) {
                disableByClass('make_disable_it');
                var submitAdjudicationFormFlag = true;
                if (isAdjudicationFormInEditMode(stepIdStr)) {
                    if (checkAdjudicationFormReason(stepIdStr) == false) {
                        submitAdjudicationFormFlag = false;
                    }
                }
                if (submitAdjudicationFormFlag) {
                    var term_cond = $('#adjudication_form_terms_cond_' + stepIdStr).val();
                    var reason = $('#adjudication_form_edit_reason_text_' + stepIdStr).val();
                    var frmData = $("#adjudication_form_master_" + stepIdStr).serialize() + '&' + $("#adjudication_form_" +
                            stepIdStr)
                        .serialize() +
                        '&adjudication_form_terms_cond_' + stepIdStr + '=' + term_cond + '&' + 'adjudication_form_edit_reason_text=' +
                        reason;
                    submitAdjudicationFormRequest(frmData, stepIdStr, formStatusIdStr);
                    reloadPage(2);
                }
            }

            function submitAdjudicationFormRequest(frmData, stepIdStr, formStatusIdStr) {
                $.ajax({
                    url: "{{ route('SubjectAdjudicationFormSubmission.submitStudyPhaseStepQuestionAdjudicationForm') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        putAdjudicationImage(response.adjudicationFormStatus, response.adjudicationFormStatusIdStr);
                    }
                });
            }

            function submitAdjudicationFormFieldRequest(frmData, stepIdStr) {
                $.ajax({
                    url: "{{ route('SubjectAdjudicationFormSubmission.submitAdjudicationFormStudyPhaseStepQuestion') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        putAdjudicationImage(response.adjudicationFormStatus, response.adjudicationFormStatusIdStr);
                    }
                });
            }

            function validateAdjudicationFormField(stepIdStr, questionId, field_name, fieldId) {
                var field_val;
                field_val = getAdjudicationFormFieldValue(stepIdStr, field_name, fieldId);
                var frmData = $("#adjudication_form_master_" + stepIdStr).serialize() + '&questionId=' + questionId + '&' +
                    field_name +
                    '=' + field_val;
                return validateAdjudicationFormSingleQuestion(frmData);

            }

            function validateAdjudicationForm(stepIdStr) {
                return new Promise(function(resolve, reject) {
                    var frmData = $("#adjudication_form_master_" + stepIdStr).serialize() + '&' + $(
                            "#adjudication_form_" + stepIdStr)
                        .serialize();
                    $.ajax({
                        url: "{{ route('subjectAdjudicationFormSubmission.validateSectionQuestionsForm') }}",
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

            function validateAdjudicationFormSingleQuestion(frmData) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ route('subjectAdjudicationFormSubmission.validateSingleQuestion') }}",
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

            function validateAndSubmitAdjudicationForm(stepIdStr, formStatusIdStr) {
                if(canSubmitAdjudicationForm(stepIdStr)){
                    if(needToPutAdjudicationFormInEditMode(stepIdStr) == false){
                        //if(window['validateAdjudicationStep' + stepIdStr]()){
                            submitAdjudicationForm(stepIdStr, formStatusIdStr);
                        }
                    // }else{
                    //     showPutFormInEditModeError();
                    // }
                }else{
                    showPermissionError();
                }
            }

            function validateAndSubmitAdjudicationFormField(stepIdStr, sectionIdStr, questionId, questionIdStr, field_name, fieldId) {
                if(canSubmitAdjudicationForm(stepIdStr)){
                    if(needToPutAdjudicationFormInEditMode(stepIdStr) == false){
                        //if(window['validateAdjudicationQuestion' + questionIdStr](true, stepIdStr)){
                            if(eval("typeof " + window['checkQuestionSkipLogicForAdjudication' + questionIdStr]) != 'undefined'){
                                window['checkQuestionSkipLogicForAdjudication' + questionIdStr]();
                            }
                            submitAdjudicationFormField(stepIdStr, questionId, field_name, fieldId);
                        }
                    // // }else{
                    // //     showPutFormInEditModeError();
                    // }
                }else{
                    showPermissionError();
                }
            }

            function checkAdjudicationFormTermCond(stepIdStr) {
                if ($('#adjudication_form_terms_cond_' + stepIdStr).prop('checked')) {
                    return true;
                } else {
                    showAlert('Alert',
                        'Please acknowledge the truthfulness and correctness of information being submitting in this form!',
                        'error'
                    );
                    return false;
                }
            }

            function isAdjudicationFormInEditMode(stepIdStr) {
                var formEditStatus = $('#adjudication_form_editing_status_' + stepIdStr).val();
                var returnVal = false;
                if (formEditStatus == 'yes') {
                    returnVal = true;
                }
                return returnVal;
            }


            function checkAdjudicationFormReason(stepIdStr) {
                var returnVal = false;
                if (($('#adjudication_form_edit_reason_text_' + stepIdStr).val() == '')) {
                    showAlert('Alert', 'Please tell the reason to edit', 'error');
                } else {
                    returnVal = true;
                }
                return returnVal;
            }

            function submitAdjudicationFormField(stepIdStr, questionId, field_name, fieldId) {
                var submitAdjudicationFormFlag = true;
                if (submitAdjudicationFormFlag) {
                    var frmData = $("#adjudication_form_master_" + stepIdStr).serialize();
                    var field_val;
                    field_val = getAdjudicationFormFieldValue(stepIdStr, field_name, fieldId);
                    frmData = frmData + '&' + field_name + '=' + field_val + '&' + 'questionId=' + questionId;
                    submitAdjudicationFormFieldRequest(frmData, stepIdStr, '', '');
                }
            }

            function getAdjudicationFormFieldValue(stepIdStr, field_name, fieldId) {
                var field_val = '';
                var checkedCheckBoxes = [];
                if ($('#adjudication_form_' + stepIdStr + ' #' + fieldId).is("textarea")) {
                    field_val = $('#adjudication_form_' + stepIdStr + ' #' + fieldId).val();
                } else if ($('#adjudication_form_' + stepIdStr + ' #' + fieldId).is("select")) {
                    field_val = $('#adjudication_form_' + stepIdStr + ' #' + fieldId).find(":selected").val();
                } else if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').attr('type') == 'radio') {
                    field_val = $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                } else if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]').attr('type') == 'checkbox') {
                    $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]:checked').each(function() {
                        checkedCheckBoxes.push($(this).val());
                    });
                    field_val = checkedCheckBoxes.join(",");
                } else {
                    field_val = $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                }
                return field_val;
            }

            function getAdjudicationFormFieldValueForRequired(stepIdStr, field_name, fieldId) {
                var field_val = '';
                var field_val_for_disabled = 'disabledField';
                var checkedCheckBoxes = [];
                if ($('#adjudication_form_' + stepIdStr + ' #' + fieldId).is("textarea")) {
                    if ($('#adjudication_form_' + stepIdStr + ' #' + fieldId).prop('disabled') == false) {
                        field_val = $('#adjudication_form_' + stepIdStr + ' #' + fieldId).val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else if ($('#adjudication_form_' + stepIdStr + ' #' + fieldId).is("select")) {
                    if ($('#adjudication_form_' + stepIdStr + ' #' + fieldId).prop('disabled') == false) {
                        field_val = $('#adjudication_form_' + stepIdStr + ' #' + fieldId).find(":selected").val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').attr('type') == 'radio') {
                    if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').prop('disabled') == false) {
                        field_val = $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]').attr('type') == 'checkbox') {
                    if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]').prop('disabled') == false) {
                        $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]:checked').each(function() {
                            checkedCheckBoxes.push($(this).val());
                        });
                        field_val = checkedCheckBoxes.join(",");
                    }else{
                        field_val = field_val_for_disabled;
                    }
                } else {
                    if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').prop('disabled') == false) {
                        field_val = $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                    }else{
                        field_val = field_val_for_disabled;
                    }
                }
                return field_val;
            }

            function openAdjudicationFormForEditing(stepIdStr, stepClsStr, formType, formStatusIdStr) {
                if (isFormDataLocked(stepIdStr) == false) {
                    if(canSubmitAdjudicationForm(stepIdStr)){
                            var frmData = $("#adjudication_form_master_" + stepIdStr).serialize();
                            frmData = frmData + '&' + 'open_adjudication_form_to_edit=1';
                            $.ajax({
                                url: "{{ route('SubjectAdjudicationFormSubmission.openSubjectAdjudicationFormToEdit') }}",
                                type: 'POST',
                                data: frmData,
                                success: function(response) {
                                    showAdjudicationFormReasonField(stepIdStr, stepClsStr, formType, formStatusIdStr);
                                }
                            });
                    }else{
                        showPermissionError();
                    }
                } else {
                    showDataLockError();
                }
            }

            function isFormDataLocked(stepIdStr) {
                var isFormDataLocked = $('#form_master_' + stepIdStr + ' input[name="isFormDataLocked"]').val();
                if (isFormDataLocked == 1) {
                    return true;
                } else {
                    return false;
                }
            }

            function showDataLockError() {
                showAlert('Data lock status', 'Form data is locked, you can not change data!', 'error');
            }

            function showAdjudicationFormReasonField(stepIdStr, stepClsStr, formType, formStatusIdStr) {
                $("#adjudication_form_edit_div_" + stepIdStr).show(500);
                $("#adjudication_form_edit_button_" + stepIdStr).hide(500);
                $('#adjudication_form_edit_reason_text_' + stepIdStr).prop('required', true);
                $('#fieldset_adjudication_' + stepIdStr).prop('disabled', false);
                enableByClass(stepClsStr);
                $('.adjudication_form_hid_editing_status_' + stepIdStr).val('yes');
                putResponseImage(stepIdStr, 'resumable', formType, formStatusIdStr);
            }

            function hideAdjudicationFormReasonField(stepIdStr, stepClsStr, formType, formStatusIdStr) {
                $("#adjudication_form_edit_div_" + stepIdStr).hide(500);
                $('#adjudication_form_edit_reason_text_' + stepIdStr).prop('required', false);
                $('#fieldset_adjudication_' + stepIdStr).prop('disabled', true);
                $('#adjudication_form_edit_reason_text_' + stepIdStr).val('');
                disableByClass(stepClsStr);
                $('.adjudication_form_hid_editing_status_' + stepIdStr).val('no');
                $('.nav-link').removeClass('active');
                $('.first_navlink_' + stepIdStr).addClass('active');
                $('.tab-pane_' + stepIdStr).removeClass('active show');
                $('.first_tab_' + stepIdStr).addClass('active show');
                putResponseImage(stepIdStr, 'complete', formType, formStatusIdStr);
            }

            function copyValueToField(stepIdStr, sectionIdStr, questionId, questionIdStr, field_name, fieldId, copyToFieldId) {
                var fieldVal = $('#' + fieldId).val();
                $('#adjudication_form_' + stepIdStr + ' #' + copyToFieldId).val(fieldVal);
                var copyToFieldName = $("#" + copyToFieldId).attr("name");
                validateAndSubmitAdjudicationFormField(stepIdStr, sectionIdStr, questionId, questionIdStr, copyToFieldName, copyToFieldId);
            }

            function calculateAverage(stepIdStr, sectionIdStr, questionId, questionIdStr, copyToFieldId, decimalPoint){
                var numberValues = [];
                $('.' + questionIdStr).each(function() {
                    numberValues.push($(this).val());
                });
                var total = 0;
                for(var i = 0; i < numberValues.length; i++) {
                    total += parseFloat(numberValues[i]);
                }
                //var avg = (Math.round(total / numberValues.length)).toFixed(decimalPoint);
                var avg = total / numberValues.length;
                var avg = avg.toFixed(decimalPoint);
                $('#adjudication_form_' + stepIdStr + ' #' + copyToFieldId).val(avg);
                var copyToFieldName = $("#" + copyToFieldId).attr("name");
                validateAndSubmitAdjudicationFormField(stepIdStr, sectionIdStr, questionId, questionIdStr, copyToFieldName, copyToFieldId);
            }

            function showAllQuestions(){
                var phaseId = $('#current_phase_id').val();
                var stepId = $('#current_step_id').val();
                var sectionId = $('#current_section_id').val();
                var isAdjudication = $('#isAdjudication').val();

                var url = "{{ url('/') }}/subjectFormLoader/{{ $studyId }}/{{ $subjectId }}/" + phaseId + '/' + stepId + '/' + sectionId + '/' + isAdjudication + '/yes';
                location.href = url;
            }
            function showOnlyAdjudicationRequiredQuestions(){
                var phaseId = $('#current_phase_id').val();
                var stepId = $('#current_step_id').val();
                var sectionId = $('#current_section_id').val();
                var isAdjudication = $('#isAdjudication').val();

                var url = "{{ url('/') }}/subjectFormLoader/{{ $studyId }}/{{ $subjectId }}/" + phaseId + '/' + stepId + '/' + sectionId + '/' + isAdjudication + '/no';
                location.href = url;
            }

            function canSubmitAdjudicationForm(stepIdStr){
                var canAdjudication = {{ (canAdjudication(['create', 'store', 'edit', 'update']))? 'true':'false' }};
                var canSubmit = false;
                var adjudication_status = $('#adjudication_form_master_' + stepIdStr + ' input[name="adjudication_status"]').val();
                var form_adjudicated_by_id = $('#adjudication_form_master_' + stepIdStr + ' input[name="form_adjudicated_by_id"]').val();
                var current_user_id = '{{ auth()->user()->id }}';

                if(canAdjudication == true){
                    if((adjudication_status == 'no_status') && (form_adjudicated_by_id == 'no-user-id')){
                        canSubmit = true;
                    }
                    if((adjudication_status != 'no_status') && (form_adjudicated_by_id == current_user_id)){
                        canSubmit = true;
                    }
                    return canSubmit;
                }
            }

            function needToPutAdjudicationFormInEditMode(stepIdStr){
                var putFormInEditMode = false;
                var adjudication_status = $('#adjudication_form_master_' + stepIdStr + ' input[name="adjudication_status"]').val();
                var isFormInEditMode = $('#adjudication_form_editing_status_' + stepIdStr).val();
                if((adjudication_status == 'complete') && (isFormInEditMode == 'no')){
                    putFormInEditMode = true;
                }
                return putFormInEditMode;
            }

        </script>
    @endpush
