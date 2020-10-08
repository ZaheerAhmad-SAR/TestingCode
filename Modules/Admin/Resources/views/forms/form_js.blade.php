@php
$sectionIdStringArray = [];
$sectionClsStringArray = [];
foreach($sections as $key => $section){
    $sectionClsStringArray[] = buildSafeStr($section->id, 'sec_cls_');
    $sectionIdStringArray[] = buildSafeStr($section->id, '');
}
$sectionIdString = implode('", "', $sectionIdStringArray);
$sectionClsString = implode('", "', $sectionClsStringArray);
@endphp
<script>
    function submitStepForms{{ $stepIdStr }}(stepIdStr, stepClsStr) {
        var submitFormFlag = true;
        var sectionIdStrArray = ["{!!  $sectionIdString !!}"];
        var sectionClsStrArray = ["{!!  $sectionClsString !!}"];
        if (checkTermCond(stepIdStr)) {
            sectionIdStrArray.forEach((sectionIdStr, key) => {
                if (isFormInEditMode(sectionIdStr)) {
                    if (checkReason(stepIdStr) === false) {
                        stopJsHere();
                    }
                }
                validateAndSubmitForm(sectionIdStr, sectionClsStrArray[key], stepIdStr);
            });
            reloadPage();
            //hideReasonField(stepIdStr, stepClsStr);
        }
    }

</script>
