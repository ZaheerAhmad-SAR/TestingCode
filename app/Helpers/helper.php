<?php
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\OptionsGroup;
use Modules\Admin\Entities\Site;

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
    foreach ($roles as $role) {
        $role = $role;
    }
    $permission = Permission::where('name','=',$routeName)->first();

    $rolePermission = RolePermission::where('role_id',$role->role_id)
        ->where('permission_id',$permission->id)->first();
        if ($rolePermission){

        return true;
    }
    else {
        return false;
    }
}

function eventDetails($eventId, $eventType) {

    $data = [];
    if ($eventType == 'Option Group') {
        // get event data
        $eventData = OptionsGroup::find($eventId);
        // store data in event array
        $data = array(
            'option_group_name' => $eventData->option_group_name,
            'option_group_description' => $eventData->option_group_description,
            'option_layout' => $eventData->option_layout,
            'created_at' => $eventData->created_at,
            'updated_at' => $eventData->updated_at,
        );
    } else if($eventType == 'Site') {
        // get event data
        $eventData = Site::find($eventId);
        // store data in event array
        $data = array(
            'site_name' => $eventData->site_name,
            'site_address' => $eventData->site_address,
            'site_city' => $eventData->site_city,
            'site_state' => $eventData->site_state,
            'site_code' => $eventData->site_code,
            'site_country' => $eventData->site_country,
            'site_phone' => $eventData->site_country,
            'created_at' => $eventData->created_at,
            'updated_at' => $eventData->updated_at,
        );
    }

    // return data
    return $data;
}


