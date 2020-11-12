function mustRequired(isFormValid, fieldTitle, fieldValue) {
    if (isFormValid == true) {
        if ((fieldValue == "") || (typeof fieldValue === 'undefined')) {
            alert(fieldTitle + " is required");
            isFormValid = false;
        }
    }
    return isFormValid;
}

function in_range(isFormValid, fieldTitle, fieldValue, min, max) {
    if (isFormValid == true) {
        if (fieldValue < min || fieldValue > max) {
            alert(fieldTitle + " must be in range (" + min + " - " + max + ")");
            isFormValid = false;
        }
    }
    return isFormValid;
}

function lessThan(isFormValid, fieldTitle, fieldValue, conditionVal) {
    if (isFormValid == true) {
        if (fieldValue > conditionVal) {
            alert(fieldTitle + " must be less than (" + conditionVal + ")");
            isFormValid = false;
        }
    }
    return isFormValid;
}

function greaterThan(isFormValid, fieldTitle, fieldValue, conditionVal) {
    if (isFormValid == true) {
        if (fieldValue < conditionVal) {
            alert(fieldTitle + " must be greater than (" + conditionVal + ")");
            isFormValid = false;
        }
    }
    return isFormValid;
}
