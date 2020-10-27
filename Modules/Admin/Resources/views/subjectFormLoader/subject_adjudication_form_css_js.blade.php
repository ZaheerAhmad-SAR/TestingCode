    @push('script')
        <script>
            function submitAdjudicationForm(stepIdStr, formTypeId, formStatusIdStr) {
                disableByClass('make_disable_it');
                var submitAdjudicationFormFlag = true;
                if (isAdjudicationFormInEditMode(stepIdStr)) {
                    if (checkAdjudicationFormReason(stepIdStr) === false) {
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
                    submitAdjudicationFormRequest(frmData, stepIdStr, formTypeId, formStatusIdStr);
                }
            }

            function submitAdjudicationFormRequest(frmData, stepIdStr, formTypeId, formStatusIdStr) {
                $.ajax({
                    url: "{{ route('SubjectAdjudicationFormSubmission.submitStudyPhaseStepQuestionAdjudicationForm') }}",
                    type: 'POST',
                    data: frmData,
                    dataType: 'JSON',
                    success: function(response) {
                        putResponseImage(stepIdStr, response.adjudicationFormStatus, formTypeId, response
                            .adjudicationFormStatusIdStr);
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
                        putResponseImage(stepIdStr, response.adjudicationFormStatus, 0, response
                            .adjudicationFormStatusIdStr);
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

            function validateAndSubmitAdjudicationForm(stepIdStr, formTypeId, formStatusIdStr) {
                const promise = validateAdjudicationForm(stepIdStr);
                promise
                    .then((data) => {
                        console.log(data);
                        submitAdjudicationForm(stepIdStr, formTypeId, formStatusIdStr);
                    })
                    .catch((error) => {
                        console.log(error);
                        handleValidationErrors(error);
                    });
            }

            function validateAndSubmitAdjudicationFormField(stepIdStr, sectionIdStr, questionId, field_name, fieldId) {
                checkIsThisFieldDependent(sectionIdStr, questionId, field_name, fieldId);
                const validationPromise = validateAdjudicationFormField(stepIdStr, questionId, field_name, fieldId);
                validationPromise
                    .then((data) => {
                        console.log(data)
                        submitAdjudicationFormField(stepIdStr, questionId, field_name, fieldId);
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

            function checkAdjudicationFormTermCond(stepIdStr) {
                if ($('#adjudication_form_terms_cond_' + stepIdStr).prop('checked')) {
                    return true;
                } else {
                    showAlert(
                        'Please acknowledge the truthfulness and correctness of information being submitting in this form!'
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
                    showAlert('Please tell the reason to edit');
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
                var field_val;
                var checkedCheckBoxes = [];
                if ($('#' + fieldId).is("textarea")) {
                    field_val = $('#' + fieldId).val();
                } else if ($('#' + fieldId).is("select")) {
                    field_val = $('#' + fieldId).find(":selected").val();
                } else if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').attr('type') ==
                    'radio') {
                    field_val = $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]:checked').val();
                } else if ($('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]').attr('type') ==
                    'checkbox') {

                    $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '[]"]:checked').each(function() {
                        checkedCheckBoxes.push($(this).val());
                    });
                    field_val = checkedCheckBoxes.join(",");

                } else {
                    field_val = $('#adjudication_form_' + stepIdStr + ' input[name="' + field_name + '"]').val();
                }
                return field_val;
            }

            function openAdjudicationFormForEditing(stepIdStr, stepClsStr, formTypeId, formStatusIdStr) {
                var frmData = $("#adjudication_form_master_" + stepIdStr).serialize();
                frmData = frmData + '&' + 'open_adjudication_form_to_edit=1';
                $.ajax({
                    url: "{{ route('SubjectAdjudicationFormSubmission.openSubjectAdjudicationFormToEdit') }}",
                    type: 'POST',
                    data: frmData,
                    success: function(response) {
                        showAdjudicationFormReasonField(stepIdStr, stepClsStr, formTypeId, formStatusIdStr);
                    }
                });
            }

            function showAdjudicationFormReasonField(stepIdStr, stepClsStr, formTypeId, formStatusIdStr) {
                $("#adjudication_form_edit_div_" + stepIdStr).show(500);
                $("#adjudication_form_edit_button_" + stepIdStr).hide(500);
                $('#adjudication_form_edit_reason_text_' + stepIdStr).prop('required', true);
                $('#fieldset_adjudication_' + stepIdStr).prop('disabled', false);
                enableByClass(stepClsStr);
                $('.adjudication_form_hid_editing_status_' + stepIdStr).val('yes');
                putResponseImage(stepIdStr, 'resumable', formTypeId, formStatusIdStr);
            }

            function hideAdjudicationFormReasonField(stepIdStr, stepClsStr, formTypeId, formStatusIdStr) {
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
                putResponseImage(stepIdStr, 'complete', formTypeId, formStatusIdStr);
            }

            function copyValueToField(stepIdStr, sectionIdStr, questionId, field_name, fieldId, copyToFieldId) {
                var fieldVal = $('#' + fieldId).val();
                $('#' + copyToFieldId).val(fieldVal);
                var copyToFieldName = $("#" + copyToFieldId).attr("name");
                validateAndSubmitAdjudicationFormField(stepIdStr, sectionIdStr, questionId, copyToFieldName, copyToFieldId);
            }

            function calculateAverage(stepIdStr, sectionIdStr, questionId, questionIdStr, copyToFieldId){
                var numberValues = [];
                $('.' + questionIdStr).each(function() {
                    numberValues.push($(this).val());
                });
                var total = 0;
                for(var i = 0; i < numberValues.length; i++) {
                    total += parseFloat(numberValues[i]);
                }
                var avg = total / numberValues.length;
                $('#' + copyToFieldId).val(avg);
                var copyToFieldName = $("#" + copyToFieldId).attr("name");
                validateAndSubmitAdjudicationFormField(stepIdStr, sectionIdStr, questionId, copyToFieldName, copyToFieldId);
            }

            function showAllQuestions(){
                var route = "{{ route('subjectFormLoader.showSubjectForm', ['study_id'=>$studyId, 'subject_id'=>$subjectId, 'showAllQuestions'=>'yes' ])}}";
                location.href = route;
            }
            function showOnlyAdjudicationRequiredQuestions(){
                var route = "{{ route('subjectFormLoader.showSubjectForm', ['study_id'=>$studyId, 'subject_id'=>$subjectId, 'showAllQuestions'=>'no' ])}}";
                location.href = route;
            }

        </script>
    @endpush
