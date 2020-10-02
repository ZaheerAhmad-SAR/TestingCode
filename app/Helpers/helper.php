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

function eventDetails($eventId, $eventSection, $eventType, $ip, $previousData) {

    $newData = [];
    $oldData = [];
    $auditMessage = '';
    $auditUrl = '';

    ////////////////////// Option Group //////////////////////////////////////////
    if ($eventSection == 'Option Group') {
        // get event data
        $eventData = OptionsGroup::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added option group '.$eventData->option_group_name.'.';
        // set audit url
        $auditUrl = url('optionsGroup');
        // store data in event array
        $newData = array(
            'option_group_name' => $eventData->option_group_name,
            'option_group_description' => $eventData->option_group_description,
            'option_layout' => $eventData->option_layout,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // check if it is update case
        if ($eventType == 'Update') {
            // store data in event array
            $oldData = array(
            'option_group_name' => $previousData->option_group_name,
            'option_group_description' => $previousData->option_group_description,
            'option_layout' => $previousData->option_layout,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

        $auditMessage = \Auth::user()->name.' updated option group '.$eventData->option_group_name.'.';

        } // update case ends

    //////////////////////////////// Site ////////////////////////////////////////////////////////
    } else if($eventSection == 'Site') {
        // get event data
        $eventData = Site::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added site '.$eventData->site_name.'.';
        // set audit url
        $auditUrl = url('sites');
        // store data in event array
        $newData = array(
            'site_code' => $eventData->site_code,
            'site_name' => $eventData->site_name,
            'site_address' => $eventData->site_address,
            'site_city' => $eventData->site_city,
            'site_state' => $eventData->site_state,
            'site_code' => $eventData->site_code,
            'site_country' => $eventData->site_country,
            'site_phone' => $eventData->site_phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if($eventType == 'Update') {
            
            $oldData = array(
            'site_code' => $previousData->site_code,
            'site_name' => $previousData->site_name,
            'site_address' => $previousData->site_address,
            'site_city' => $previousData->site_city,
            'site_state' => $previousData->site_state,
            'site_code' => $previousData->site_code,
            'site_country' => $previousData->site_country,
            'site_phone' => $previousData->site_phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated site '.$eventData->site_name.'.';
        }

    /////////////////////////////// Primary Investigator /////////////////////////////////////////////
    } else if($eventSection == 'PI') {
        // get event data
        $eventData = PrimaryInvestigator::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added primary investigator '.$eventData->first_name.'.';
        // set audit url
        $auditUrl = url('sites');
        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if($eventType == 'Update') {
            
            $oldData = array(
            'first_name' => $previousData->first_name,
            'mid_name' => $previousData->mid_name,
            'last_name' => $previousData->last_name,
            'email' => $previousData->email,
            'phone' => $previousData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated primary investigator '.$eventData->first_name.'.';
        }

    ///////////////////////////////////// Coordinator ///////////////////////////////////////////////////
    } else if($eventSection == 'Coordinator') {
        // get event data
        $eventData = Coordinator::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added coordinator '.$eventData->first_name.'.';
        // set audit url
        $auditUrl = url('sites');
        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {
            $oldData = array(
            'first_name' => $previousData->first_name,
            'mid_name' => $previousData->mid_name,
            'last_name' => $previousData->last_name,
            'email' => $previousData->email,
            'phone' => $previousData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated coordinator '.$eventData->first_name.'.';
        }

    //////////////////////////////////////// Photographer /////////////////////////////////////////////
    } else if($eventSection == 'Photographer') {
        // get event data
        $eventData = Photographer::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added photographer '.$eventData->first_name.'.';
        // set audit url
        $auditUrl = url('sites');
        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if this is update case
        if ($eventType == 'Update') {
            
            $oldData = array(
            'first_name' => $previousData->first_name,
            'mid_name' => $previousData->mid_name,
            'last_name' => $previousData->last_name,
            'email' => $previousData->email,
            'phone' => $previousData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated photographer '.$eventData->first_name.'.';
        }

    //////////////////////////////////////////// Others ////////////////////////////////////////////////
    } else if($eventSection == 'Others') {
        // get event data
        $eventData = Other::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added others '.$eventData->first_name.'.';
        // set audit url
        $auditUrl = url('sites');
        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if its update case
        if ($eventType == 'Update') {
            $oldData = array(
            'first_name' => $previousData->first_name,
            'mid_name' => $previousData->mid_name,
            'last_name' => $previousData->last_name,
            'email' => $previousData->email,
            'phone' => $previousData->phone,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated others '.$eventData->first_name.'.';
        }
        
    /////////////////////////////// Others Section ends //////////////////////////////////////    
    } else if($eventSection == 'Annotation') {
        // get event data
        $eventData = Annotation::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added annotation '.$eventData->label.'.';
        // set audit url
        $auditUrl = url('annotation');
        // store data in event array
        $newData = array(
            'label' => $eventData->label, 
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if($eventType == 'Update') {
            
            $oldData = array(
            'label' => $previousData->label,
            'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated annotation '.$eventData->label.'.';
        }
       
    ///////////////////////// Annotaion Sections ends ///////////////////////////////////////////
    } else if ($eventSection == 'Role') {
        // get event data
        $eventData = Role::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added role '.$eventData->name.'.';
        // set audit url
        $auditUrl = url('roles');
        // store data in event array
        $newData = array(
            'name' => $eventData->name,
            'description' => $eventData->description,
            'role_type' => $eventData->role_type,
            'created_by' => \Auth::user()->name,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if($eventType == 'Update') {
            
            $oldData = array(
                'name' => $previousData->name,
                'description' => $previousData->description,
                'role_type' => $previousData->role_type,
                'created_by' => \Auth::user()->name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated role '.$eventData->name.'.';
        }
       
    ////////////////////////// Role Ends ///////////////////////////////////////////////////
    } else if ($eventSection == 'User') {
        // get event data
        $eventData = User::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name.' added system user '.$eventData->name.'.';
        // set audit url
        $auditUrl = url('users');
        // store data in event array
        $newData = array(
            'name' => $eventData->name,
            'email' => $eventData->email,
            'created_by' => \Auth::user()->name,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if($eventType == 'Update') {
            
            $oldData = array(
                'name' => $previousData->name,
                'email' => $previousData->email,
                'created_by' => \Auth::user()->name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name.' updated system user '.$eventData->name.'.';
        }
       
    ////////////////////////// System Users Ends ///////////////////////////////////////////////////
    } // main If else ends 

    // Log the event
    $trailLog = new TrailLog;
    $trailLog->event_id = $eventId;
    $trailLog->event_section = $eventSection;
    $trailLog->event_type = $eventType;
    $trailLog->event_message = $auditMessage;
    $trailLog->user_id = \Auth::user()->id;
    $trailLog->user_name = \Auth::user()->name;
    $trailLog->role_id = \Auth::user()->role_id;
    $trailLog->ip_address = $ip;
    $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
    $trailLog->event_url = $auditUrl;
    $trailLog->event_details = json_encode($newData);
    $trailLog->event_old_details = json_encode($oldData);
    $trailLog->save();

} // trail log function ends

function buildSafeStr($id, $str = ''){
    return $str . str_replace('-', '_', $id);
}

