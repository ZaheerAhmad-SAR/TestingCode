    @push('script')
        <script>
            function mustRequired(isFormValid, fieldTitle, fieldValue, questionRowId) {
                //alert(fieldTitle+' : '+fieldValue);
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if ((fieldValue == "") && (fieldValue == "") || (typeof fieldValue === 'undefined')) {
                            showAlert(fieldTitle, fieldTitle + " : is required", 'error');
                            isFormValid = false;
                            $('#'+questionRowId).addClass('validation-border');
                        }
                    }
                }
                return isFormValid;
            }

            function in_range(isFormValid, fieldTitle, fieldValue, questionRowId, min, max, messageType, message) {
                if (isFormValid == true || messageType != 'error') {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue < min || fieldValue > max) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function max(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue > conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function greaterThan(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue > conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function greaterThanOrEqual(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue >= conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function min(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue < conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function lessThen(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue < conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function lessThanOrEqual(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue <= conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

            function equalTo(isFormValid, fieldTitle, fieldValue, questionRowId, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue != "disabledField") {
                        if (fieldValue != -9999) {
                            if (fieldValue == conditionVal) {
                                showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                                if (messageType == 'error') {
                                    isFormValid = false;
                                    $('#'+questionRowId).addClass('validation-border');
                                }
                            }
                        }
                    }
                }
                return isFormValid;
            }

        </script>
    @endpush
