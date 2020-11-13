<?php
function buildSafeStr($id, $str = '')
{
    $safeStr = '';
    if (!empty($id)) {
        $safeStr = $str . str_replace('-', '_', $id);
    }
    return $safeStr;
}

function buildFormFieldName($str = '')
{
    return str_replace(' ', '', $str);
}

function buildGradingStatusIdClsStr($id)
{
    return buildSafeStr($id, 'img_grading_form_status_');
}

function buildAdjudicationStatusIdClsStr($id)
{
    return buildSafeStr($id, 'img_adjudication_form_status_');
}

function checkPermission($permissionText, $permissionsArray = ['index', 'create', 'store', 'edit', 'update'])
{
    $retVal = true;
    $user = auth()->user();
    foreach ($permissionsArray as $permission) {
        $permissionCheck = $permissionText . $permission;
        if (!hasPermission($user, $permissionCheck)) {
            $retVal = false;
            break;
        }
    }

    return $retVal;
}
function canQualityControl($permissionsArray = ['index', 'create', 'store', 'edit', 'update'])
{
    $permissionText = 'qualitycontrol.';
    return checkPermission($permissionText, $permissionsArray);
}

function canGrading($permissionsArray = ['index', 'create', 'store', 'edit', 'update'])
{
    $permissionText = 'grading.';
    return checkPermission($permissionText, $permissionsArray);
}

function canAdjudication($permissionsArray = ['index', 'create', 'store', 'edit', 'update'])
{
    $permissionText = 'adjudication.';
    return checkPermission($permissionText, $permissionsArray);
}
function canEligibility($permissionsArray = ['index', 'create', 'store', 'edit', 'update'])
{
    $permissionText = 'eligibility.';
    return checkPermission($permissionText, $permissionsArray);
}
function canManageData($permissionsArray = ['index', 'create', 'store', 'edit', 'update'])
{
    $permissionText = 'data_management.';
    return checkPermission($permissionText, $permissionsArray);
}
function printSqlQuery($builder, $dd = true)
{
    $query = vsprintf(str_replace(array('?'), array('\'%s\''), $builder->toSql()), $builder->getBindings());
    if ($dd) {
        dd($query);
    } else {
        echo ($query);
    }
}
