    @push('script')
        <script>
            function mustRequired(isFormValid, fieldTitle, fieldValue) {
                if (isFormValid == true) {
                    if ((fieldValue == "") || (typeof fieldValue === 'undefined')) {
                        alert(fieldTitle + " is required");
                        isFormValid = false;
                    }
                }
                return isFormValid;
            }

            function in_range(isFormValid, fieldTitle, fieldValue, min, max, messageType, message) {
                if (isFormValid == true || messageType != 'error') {
                    if (fieldValue < min || fieldValue > max) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function max(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue > conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function greaterThan(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue > conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function greaterThanOrEqual(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue >= conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function min(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue < conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function lessThen(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue < conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function lessThanOrEqual(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue <= conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

            function equalTo(isFormValid, fieldTitle, fieldValue, conditionVal, messageType, message) {
                if (isFormValid == true) {
                    if (fieldValue == conditionVal) {
                        showAlert(fieldTitle, fieldTitle + " : " + message, messageType);
                        if (messageType == 'error') {
                            isFormValid = false;
                        }
                    }
                }
                return isFormValid;
            }

        </script>
    @endpush
