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
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr).serialize() + '&terms_cond_' + stepIdStr + '=' + term_cond + '&' + 'edit_reason_text=' + reason;
                    submitRequest(frmData, stepIdStr, formTypeId, formStatusIdStr);
                    reloadPage(2);
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
                var frmData = $("#form_master_" + stepIdStr).serialize() + '&questionId=' + questionId + '&' + field_name + '=' + field_val;
                return validateSingleQuestion(frmData);

            }

            function validateForm(stepIdStr) {
                return new Promise(function(resolve, reject) {
                    var frmData = $("#form_master_" + stepIdStr).serialize() + '&' + $("#form_" + stepIdStr).serialize();
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

            function validateAndSubmitForm_bk123445567(stepIdStr, formTypeId, formStatusIdStr) {
                if(isFormDataLocked(stepIdStr) == false){
                    if(canSubmitForm(formTypeId,stepIdStr)){
                        if(needToPutFormInEditMode(stepIdStr) == false){
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
                            showPutFormInEditModeError();
                        }
                    }else{
                        showPermissionError();
                    }
                }else{
                    showDataLockError();
                }

            }

            function validateAndSubmitForm(stepIdStr, formTypeId, formStatusIdStr) {
                if(isFormDataLocked(stepIdStr) == false){
                    if(canSubmitForm(formTypeId,stepIdStr)){
                        if(needToPutFormInEditMode(stepIdStr) == false){
                            if(window['validateStep' + stepIdStr]()){
                                submitForm(stepIdStr, formTypeId, formStatusIdStr);
                            }
                        }else{
                            showPutFormInEditModeError();
                        }
                    }else{
                        showPermissionError();
                    }
                }else{
                    showDataLockError();
                }

            }

            function validateAndSubmitField(stepIdStr, sectionIdStr, questionId, questionIdStr, formTypeId, field_name, fieldId) {
                if(isFormDataLocked(stepIdStr) == false){
                    if(canSubmitForm(formTypeId,stepIdStr)){
                        if(needToPutFormInEditMode(stepIdStr) == false){
                            checkIsThisFieldDependent(sectionIdStr, questionId, field_name, fieldId);
                            if(window['validateQuestion' + questionIdStr](true, stepIdStr)){
                                if(eval("typeof " + window['showHideQuestion' + questionIdStr]) != 'undefined'){
                                    window['showHideQuestion' + questionIdStr](stepIdStr);
                                }
                                submitFormField(stepIdStr, questionId, field_name, fieldId);
                            }
                        }else{
                            showPutFormInEditModeError();
                        }
                    }else{
                        showPermissionError();
                    }
                }else{
                    showDataLockError();
                }
            }

            function validateAndSubmitField_bk123(stepIdStr, sectionIdStr, questionId, formTypeId, field_name, fieldId) {
                if(isFormDataLocked(stepIdStr) == false){
                    if(canSubmitForm(formTypeId,stepIdStr)){
                        if(needToPutFormInEditMode(stepIdStr) == false){
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
                            showPutFormInEditModeError();
                        }
                    }else{
                        showPermissionError();
                    }
                }else{
                    showDataLockError();
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
                if(isFormDataLocked(stepIdStr) == false){
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
                }else{
                    showDataLockError();
                }
            }

            function lockFormData(stepIdStr) {
                if(canLockFormData() == true){
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
                }else{
                    showAlert('Data lock status', 'You can not lock data!', 'error');
                }
            }

            function unlockFormData(stepIdStr) {
                if(canLockFormData() == true){
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
                }else{
                    showAlert('Data lock status', 'You can not unlock data!', 'error');
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
                $("#assignPhasesToSubjectPopup").modal('hide');
                startWait();
                $.ajax({
                    url: "{{route('assignPhaseToSubject.submitAssignPhaseToSubjectForm')}}",
                    type: 'POST',
                    data: $( "#assignPhaseToSubjectForm" ).serialize(),
                    success: function(response){
                        $('#assignPhaseToSubjectMainDiv').empty();
                        reloadPage(0);
                    }
                });

            }

            function unAssignPhaseToSubject(subjectId, phaseId){
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

            function unAssignPhaseToSubjectAjax(subjectId, phaseId){
                startWait();
                $.ajax({
                    url: "{{route('assignPhaseToSubject.unAssignPhaseToSubject')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'subjectId': subjectId,
                        'phaseId': phaseId
                    },
                    success: function(response){
                        reloadPage(0);
                    }
                });
            }
            function isFormDataLocked(stepIdStr){
                var isFormDataLocked = $('#form_master_' + stepIdStr + ' input[name="isFormDataLocked"]').val();
                if(isFormDataLocked == 1){
                    return true;
                }else{
                    return false;
                }
            }

            function canLockFormData(){
                var canManageData = {{ (canManageData(['create', 'store', 'edit', 'update']))? 'true':'false' }};
                userCanLockFormData = false;
                if(canManageData == true){
                    userCanLockFormData = true;
                }
                return userCanLockFormData;
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
                    (canQualityControl == true)
                ){
                    if((formStatus == 'no_status') && (formFilledByUserId == 'no-user-id')){
                        canSubmit = true;
                    }
                    if((formStatus != 'no_status') && (formFilledByUserId == current_user_id)){
                        canSubmit = true;
                    }
                    if((formStatus == 'complete') && (isFormInEditMode == 'no')){
                        canSubmit = false;
                    }
                }
                if(
                    (formTypeId == 2) &&
                    (canGrading == true)
                ){
                    if((formStatus == 'no_status') && (formFilledByUserId == 'no-user-id')){
                        canSubmit = true;
                    }
                    if((formStatus != 'no_status') && (formFilledByUserId == current_user_id)){
                        canSubmit = true;
                    }
                }
                return canSubmit;
            }

            function needToPutFormInEditMode(stepIdStr){
                var putFormInEditMode = false;
                var formStatus = $('#form_master_' + stepIdStr + ' input[name="formStatus"]').val();
                var isFormInEditMode = $('#form_editing_status_' + stepIdStr).val();

                if((formStatus == 'complete') && (isFormInEditMode == 'no')){
                    putFormInEditMode = true;
                }
                return putFormInEditMode;
            }

            function showPermissionError(){
                showAlert('Alert', 'You do not have permission to submit form', 'error');
            }

            function showPutFormInEditModeError(){
                showAlert('Alert', 'Please put form in edit mode first', 'error');
            }

            function showDataLockError(){
                showAlert('Data lock status', 'Form data is locked, you can not change data!', 'error');
            }

            function calculateField(firstFieldId, secondFieldId, operator, make_decision, customVal, stepIdStr, sectionIdStr, questionId, questionIdStr, form_type_id, field_name, fieldId) {

                var firstVal = 0;
                var secondVal = 0;
                customVal = Number(customVal);

                var firstFieldName = $("#" + firstFieldId).attr("name");
                var firstFieldVal = getFormFieldValue(stepIdStr, firstFieldName, firstFieldId);
                firstVal = Number(firstFieldVal);

                if(make_decision == 'question'){
                    var secondFieldName = $("#" + secondFieldId).attr("name");
                    var secondFieldVal = getFormFieldValue(stepIdStr, secondFieldName, secondFieldId);
                    secondVal = Number(secondFieldVal);
                }

                if(make_decision == 'custom'){
                    secondVal = customVal;
                }

                var answer = 0;
                if(operator == '+'){
                    answer = firstVal + secondVal;
                }else if(operator == '-'){
                    answer = firstVal - secondVal;
                }else if(operator == '*'){
                    answer = firstVal * secondVal;
                }else if(operator == '/'){
                    answer = firstVal / secondVal;
                }

                $('#' + fieldId).val(answer);
                validateAndSubmitField(stepIdStr, sectionIdStr, questionId, questionIdStr, form_type_id, field_name, fieldId);
            }

            function reloadPage(waitSeconds) {
                startWait();
                var seconds = waitSeconds * 1000;
                setTimeout(function() {
                   location.reload();
               }, seconds);
            }
            //disableByClass('{{ $studyClsStr }}');
        </script>
    @endpush
