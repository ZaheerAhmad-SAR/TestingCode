<?php
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\RolePermission;

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
    foreach ($arr as $row){
        if ($auth == $row){
            return true;
        }
    }
    return false;
}
function hasPermission($user, $routeName){
    $roles = $user->user_roles;
    foreach ($roles as $role){
        $role = $role;
    }
    $permission = Permission::where('name','=',$routeName)->first();

    $rolePermission = RolePermission::where('role_id',$role->role_id)
        ->where('permission_id',$permission->id)->first();
        if ($rolePermission){
        return true;
    }
    else{
        return false;
    }
}


