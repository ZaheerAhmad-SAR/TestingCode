<?php

use App\User;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\OptionsGroup;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\Annotation;
use Modules\UserRoles\Entities\Role;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\ChildModilities;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\Admin\Entities\TrailLog;
use Modules\Admin\Entities\CrushFtpTransmission;

include('trail_log_helper.php');
include('form_helper.php');

function hasrole($role)
{
    return auth()->user()->hasRole($role);
}
function hasanyrole($roles)
{
    return auth()->user()->hasAnyRole($roles);
}
function is_active($name)
{
    return Request::route()->getName() == $name;
}
function search_auth($arr, $auth)
{
    foreach ($arr as $row) {
        if ($auth == $row) {
            return true;
        }
    }
    return false;
}

function hasPermission($user, $routeName)
{
    $roles = $user->user_roles;
    foreach ($roles as $role) {
        $permission = Permission::where('name', '=', $routeName)->first();
        $rolePermission = RolePermission::where('role_id', $role->role_id)
            ->where('permission_id', $permission->id)->first();
        if ($rolePermission) {
            return true;
            break;
        } else {
            return false;
        }
    }
}
