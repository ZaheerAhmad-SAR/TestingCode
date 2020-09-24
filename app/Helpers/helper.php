<?php
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\OptionsGroup;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\TrailLog;

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

function eventDetails($eventId, $eventSection, $eventType, $ip) {

    $data = [];
    if ($eventSection == 'Option Group') {
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
        // set message for log
        $messageType = $eventType == 'Add' ? 'added' : 'updated';
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $eventId;
        $trailLog->event_type = $eventType;
        $trailLog->event_message = \Auth::user()->name.' '.$messageType.' option group '.$eventData->option_group_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $ip;
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('optionsGroup');
        $trailLog->event_details = json_encode($data);
        $trailLog->save();

    } else if($eventSection == 'Site') {
        // get event data
        $eventData = Site::find($eventId);
        // store data in event array
        $data = array(
            'site_code' => $eventData->site_code,
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
        // set message for log
        $messageType = $eventType == 'Add' ? 'added' : 'updated';
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $eventId;
        $trailLog->event_type = $eventType;
        $trailLog->event_message = \Auth::user()->name.' '.$messageType.' site '.$eventData->site_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $ip;
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($data);
        $trailLog->save();

    } else if($eventSection == 'PI') {
        // get event data
        $eventData = PrimaryInvestigator::find($eventId);
        // store data in event array
        $data = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => $eventData->created_at,
            'updated_at' => $eventData->updated_at,
        );
        // set message for log
        $messageType = $eventType == 'Add' ? 'added' : 'updated';
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $eventId;
        $trailLog->event_type = $eventType;
        $trailLog->event_message = \Auth::user()->name.' '.$messageType.' primary investigator '.$eventData->first_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $ip;
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($data);
        $trailLog->save();

    } else if($eventSection == 'Coordinator') {
        // get event data
        $eventData = Coordinator::find($eventId);
        // store data in event array
        $data = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => $eventData->created_at,
            'updated_at' => $eventData->updated_at,
        );
        // set message for log
        $messageType = $eventType == 'Add' ? 'added' : 'updated';
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $eventId;
        $trailLog->event_type = $eventType;
        $trailLog->event_message = \Auth::user()->name.' '.$messageType.' coordinator '.$eventData->first_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $ip;
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($data);
        $trailLog->save();

    } else if($eventSection == 'Photographer') {
        // get event data
        $eventData = Photographer::find($eventId);
        // store data in event array
        $data = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => $eventData->created_at,
            'updated_at' => $eventData->updated_at,
        );
        // set message for log
        $messageType = $eventType == 'Add' ? 'added' : 'updated';
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $eventId;
        $trailLog->event_type = $eventType;
        $trailLog->event_message = \Auth::user()->name.' '.$messageType.' photographer '.$eventData->first_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $ip;
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($data);
        $trailLog->save();

    } else if($eventSection == 'Others') {
        // get event data
        $eventData = Other::find($eventId);
        // store data in event array
        $data = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => $eventData->created_at,
            'updated_at' => $eventData->updated_at,
        );
        // set message for log
        $messageType = $eventType == 'Add' ? 'added' : 'updated';
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $eventId;
        $trailLog->event_type = $eventType;
        $trailLog->event_message = \Auth::user()->name.' '.$messageType.' others '.$eventData->first_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $ip;
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($data);
        $trailLog->save();
    }

    // return data
    return $data;
}

function buildSafeStr($id, $str = ''){
    return $str . str_replace('-', '_', $id);
}

