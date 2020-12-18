<?php

use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
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
use Modules\Admin\Entities\FormType;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\Admin\Entities\TrailLog;
use Modules\Admin\Entities\CrushFtpTransmission;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\RolePermission;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Entities\RoleStudyUser;

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
    return Route::currentRouteName() == $name;
    //return Request::route()->getName() == $name;
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

function isThisUserSuperAdmin($user)
{
    $roles = $user->user_roles;
    $isSuperAdmin = false;
    foreach ($roles as $userRole) {
        $role = Role::find($userRole->role_id);
        if ($role->role_type == 'super_admin') {
            $isSuperAdmin = true;
            break;
        }
    }
    return $isSuperAdmin;
}

function isThisUserHasSystemRole($user)
{
    $roles = $user->user_roles;
    $isSuperAdmin = false;
    foreach ($roles as $userRole) {
        $role = Role::find($userRole->role_id);
        if (($role->role_type == 'super_admin') || $role->role_type == 'system_role') {
            $isSuperAdmin = true;
            break;
        }
    }
    return $isSuperAdmin;
}

function hasPermission($user, $routeName)
{
    if ((int)$user->is_active == 0) {
        Illuminate\Support\Facades\Auth::logout();
        return false;
    }

    if (isThisUserSuperAdmin($user)) {
        return true;
    }

    if (empty(\session('current_study'))) {
        $roles = $user->user_roles;
    } else {
        $roles = \Modules\Admin\Entities\RoleStudyUser::select('role_id')->where('user_id', $user->id)
            ->where('study_id', \session('current_study'))
            ->get();
    }
    $roleIds = [];
    foreach ($roles as $role) {

        $roleIds[] = $role->role_id;
    }

    $permission = Permission::where('name', '=', $routeName)->first();
    $rolePermission = RolePermission::whereIn('role_id', $roleIds)
        ->where('permission_id', $permission->id)->first();
    if ($rolePermission) {
        return true;
    } else {
        return false;
    }
}

function eventDetails($eventId, $eventSection, $eventType, $ip, $previousData, $systemUser = true)
{

    $newData = [];
    $oldData = [];
    $auditMessage = '';
    $auditUrl = '';

    ////////////////////// Option Group //////////////////////////////////////////
    if ($eventSection == 'Option Group') {
        // get event data
        $eventData = OptionsGroup::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added option group ' . $eventData->option_group_name . '.';
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

            $auditMessage = Auth::user()->name . ' updated option group ' . $eventData->option_group_name . '.';
        } // update case ends

        //////////////////////////////// Site ////////////////////////////////////////////
    } else if ($eventSection == 'Site') {
        // get event data
        $eventData = Site::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added site ' . $eventData->site_name . '.';
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
        if ($eventType == 'Update') {

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
            $auditMessage = Auth::user()->name . ' updated site ' . $eventData->site_name . '.';
        }

        /////////////////////////////// Primary Investigator ////////////////////////////////////
    } else if ($eventSection == 'Primary Investigator') {
        // get event data
        $eventData = PrimaryInvestigator::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added primary investigator ' . $eventData->first_name . '.';
        // set audit url
        $auditUrl = url('sites');

        // get site
        $getSite = Site::find($eventData->site_id);

        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'site' => $getSite->site_name,
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
                'site' => $getSite->site_name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated primary investigator ' . $eventData->first_name . '.';
        }

        ///////////////////////////////////// Coordinator ///////////////////////////////////////////////////
    } else if ($eventSection == 'Coordinator') {
        // get event data
        $eventData = Coordinator::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added coordinator ' . $eventData->first_name . '.';
        // set audit url
        $auditUrl = url('sites');

        // get site
        $getSite = Site::find($eventData->site_id);

        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'site' => $getSite->site_name,
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
                'site' => $getSite->site_name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated coordinator ' . $eventData->first_name . '.';
        }

        //////////////////////////////////////// Photographer /////////////////////////////////////////////
    } else if ($eventSection == 'Photographer') {
        // get event data
        $eventData = Photographer::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added photographer ' . $eventData->first_name . '.';
        // set audit url
        $auditUrl = url('sites');

        // get site
        $getSite = Site::find($eventData->site_id);

        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'site' => $getSite->site_name,
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
                'site' => $getSite->site_name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated photographer ' . $eventData->first_name . '.';
        }

        //////////////////////////////////////////// Others ////////////////////////////////////////////////
    } else if ($eventSection == 'Others') {
        // get event data
        $eventData = Other::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added others ' . $eventData->first_name . '.';
        // set audit url
        $auditUrl = url('sites');

        // get site
        $getSite = Site::find($eventData->site_id);

        // store data in event array
        $newData = array(
            'first_name' => $eventData->first_name,
            'mid_name' => $eventData->mid_name,
            'last_name' => $eventData->last_name,
            'email' => $eventData->email,
            'phone' => $eventData->phone,
            'site' => $getSite->site_name,
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
                'site' => $getSite->site_name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated others ' . $eventData->first_name . '.';
        }

        /////////////////////////////// Others Section ends //////////////////////////////////////
    } else if ($eventSection == 'Annotation') {
        // get event data
        $eventData = Annotation::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added annotation ' . $eventData->label . '.';
        // set audit url
        $auditUrl = url('annotation');
        // store data in event array
        $newData = array(
            'label' => $eventData->label,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'label' => $previousData->label,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated annotation ' . $eventData->label . '.';
        }

        ///////////////////////// Annotaion Sections ends ///////////////////////////////////////////
    } else if ($eventSection == 'Role') {
        // get event data
        $eventData = Role::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added role ' . $eventData->name . '.';
        // set audit url
        $auditUrl = url('roles');
        // store data in event array
        $newData = array(
            'name' => $eventData->name,
            'description' => $eventData->description,
            'role_type' => $eventData->role_type,
            'created_by' => Auth::user()->name,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'name' => $previousData->name,
                'description' => $previousData->description,
                'role_type' => $previousData->role_type,
                'created_by' => Auth::user()->name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated role ' . $eventData->name . '.';
        }

        ////////////////////////// Role Ends ///////////////////////////////////////////////////
    } else if ($eventSection == 'User') {

        // get event data
        $eventData = User::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added new user ' . $eventData->name . '.';

        if ($systemUser == false) {

            // get study user roles
            $getUserRoles = Role::leftjoin('study_role_users', 'study_role_users.role_id', '=', 'roles.id')
                ->where('study_role_users.study_id', 'like', session('current_study'))
                ->where('study_role_users.user_id', 'like',  $eventData->id)
                ->pluck('roles.name')
                ->toArray();
        } else {

            // get system user roles
            $getUserRoles = Role::leftjoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
                ->where('user_roles.user_id', $eventData->id)
                ->pluck('roles.name')
                ->toArray();
        }

        // set audit url
        $auditUrl = url('users');
        // store data in event array
        $newData = array(
            'name' => $eventData->name,
            'email' => $eventData->email,
            'role' => $getUserRoles != null ? implode(',', $getUserRoles) : '',
            'created_by' => Auth::user()->name,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'name' => $previousData->name,
                'email' => $previousData->email,
                'role' => $previousData->role,
                'created_by' => Auth::user()->name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated user ' . $eventData->name . '.';
        }

        ////////////////////////// System Users Ends ///////////////////////////////////////////////////
    } else if ($eventSection == 'Modality') {
        // get event data
        $eventData = Modility::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added modality ' . $eventData->modility_name . '.';
        // set audit url
        $auditUrl = url('modalities');
        // store data in event array
        $newData = array(
            'modility_name' => $eventData->modility_name,
            'type' => "Parent Modality",
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'modility_name' => $previousData->modility_name,
                'type' => "Parent Modality",
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated modality ' . $eventData->modility_name . '.';
        }

        //////////////////////////// Modality Ends /////////////////////////////////////////
    } else if ($eventSection == 'Child Modality') {
        // get event data
        $eventData = ChildModilities::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added child modality ' . $eventData->modility_name . '.';
        // set audit url
        $auditUrl = url('modalities');
        // get parent modality of this child
        $getParentModality = Modility::where('id', $eventData->modility_id)->first();
        // store data in event array
        $newData = array(
            'modility_name' => $eventData->modility_name,
            'type' => "Child Modality",
            'parent_modality' => $getParentModality->modility_name,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'modility_name' => $previousData->modility_name,
                'type' => "Child Modality",
                'parent_modality' => $getParentModality->modility_name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated child modality ' . $eventData->modility_name . '.';
        }

        ////////////////////////// Child Modality Ends /////////////////////////////////////
    } else if ($eventSection == 'Device') {
        // get event data
        $eventData = Device::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added device ' . $eventData->device_name . '.';
        // set audit url
        $auditUrl = url('devices');
        // store data in event array
        $newData = array(
            'device_name' => $eventData->device_name,
            'device_model' => $eventData->device_model,
            'device_manufacturer' => $eventData->device_manufacturer,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'device_name' => $previousData->device_name,
                'device_model' => $previousData->device_model,
                'device_manufacturer' => $previousData->device_manufacturer,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated device ' . $eventData->device_name . '.';
        }

        //////////////////////////// Device Ends /////////////////////////////////////////
    } else if ($eventSection == 'Phase' && ($eventType != 'Activate' && $eventType != 'Deactivate')) {

        // get event data
        $eventData = StudyStructure::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added phase ' . $eventData->name . '.';
        // set audit url
        $auditUrl = url('study');
        // store data in event array
        $newData = array(
            'study_name'    => session('study_short_name'),
            'position'  =>  $eventData->position,
            'name' =>  $eventData->name,
            'duration' =>  $eventData->duration,
            'is_repeatable' =>  $eventData->is_repeatable,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'study_name'    => session('study_short_name'),
                'position'  =>  $previousData->position,
                'name' =>  $previousData->name,
                'duration' =>  $previousData->duration,
                'is_repeatable' =>  $previousData->is_repeatable,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated phase ' . $previousData->name . '.';
        }


        //////////////////////////// phase Ends /////////////////////////////////////////
    } else if ($eventSection == 'Phase' && ($eventType == 'Activate' || $eventType == 'Deactivate')) {

        // phase activation
        if ($eventType == 'Activate') {

            // get subject Name
            $getSubjectName = Subject::find($eventId->subject_id);

            //get phase name
            $getPhaseName = StudyStructure::find($eventId['phase_id']);

            // set message for audit
            $auditMessage = Auth::user()->name . ' activated phase ' . $getPhaseName->name . '.';

            // set audit url
            $auditUrl = url('subjectFormLoader');

            $newData = array(
                'study_name'    => session('study_short_name'),
                'subject_id' => $getSubjectName->subject_id,
                'phase_id' => $getPhaseName->name,
                'visit_date' => date("d-M-Y h:m:i a", strtotime($eventId->visit_date)),
                'is_out_of_window' => $eventId->is_out_of_window,
                'form_type_id' => 'QC'
            );
        }

        // phase de-activation
        if ($eventType == 'Deactivate') {

            // get subject Name
            $getSubjectName = Subject::find($eventId->subject_id);

            //get phase name
            $getPhaseName = StudyStructure::find($eventId->phase_id);

            // set message for audit
            $auditMessage = Auth::user()->name . ' deactivated phase ' . $getPhaseName->name . '.';

            // set audit url
            $auditUrl = url('subjectFormLoader');

            $newData = array(
                'study_name'    => session('study_short_name'),
                'subject_id' => $getSubjectName->subject_id,
                'phase_id' => $getPhaseName->name,
                'visit_date' => date("d-M-Y h:m:i a", strtotime($eventId->visit_date)),
                'is_out_of_window' => $eventId->is_out_of_window,
                'form_type_id' => 'QC'
            );
        }

        //////////////////////////// phase Ends /////////////////////////////////////////
    } else if ($eventSection == 'Step') {
        // get event data
        $eventData = PhaseSteps::where('step_id', $eventId)->first();

        // set message for audit
        $auditMessage = Auth::user()->name . ' added step ' . $eventData->step_name . '.';
        // set audit url
        $auditUrl = url('study');

        // FIND PHASE NAME
        $getPhaseName = StudyStructure::find($eventData->phase_id);

        //get Form name
        $getFormName = FormType::find($eventData->form_type_id);

        // get modility name
        $getModilityName = Modility::find($eventData->modility_id);

        // store data in event array
        $newData = array(
            'study_id'    => session('current_study'),
            'phase_id'    => $getPhaseName->name,
            'step_position'  =>  $eventData->step_position,
            'form_type_id' =>  $getFormName->form_type,
            'modility_id' =>  $getModilityName->modility_name,
            'step_name' =>  $eventData->step_name,
            'step_description' =>  $eventData->step_description,
            'graders_number' =>  $eventData->graders_number,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            // FIND PHASE NAME
            $getOldPhaseName = StudyStructure::find($previousData->phase_id);

            //get Form name
            $getOldFormName = FormType::find($previousData->form_type_id);

            // get modility name
            $getOldModilityName = Modility::find($previousData->modility_id);

            $oldData = array(
                'study_id'    => session('current_study'),
                'phase_id'    => $getOldPhaseName->name,
                'step_position'  =>  $previousData->step_position,
                'form_type_id' =>  $getOldFormName->form_type,
                'modility_id' =>  $getOldModilityName->modility_name,
                'step_name' =>  $previousData->step_name,
                'step_description' =>  $previousData->step_description,
                'graders_number' =>  $previousData->graders_number,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated step ' . $previousData->step_name . '.';
        }

        //////////////////////////// step Ends /////////////////////////////////////////
    } else if ($eventSection == 'Section') {
        // get event data
        $eventData = Section::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' added section ' . $eventData->name . '.';
        // set audit url
        $auditUrl = url('study');

        // FIND PHASE NAME
        $getPhaseName = PhaseSteps::where('step_id', $eventData->phase_steps_id)->first();

        // store data in event array
        $newData = array(
            'study_id'    => session('current_study'),
            'step_name'    => $getPhaseName->step_name,
            'name'        =>  $eventData->name,
            'description' =>  $eventData->description,
            'sort_number' =>  $eventData->sort_number,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            // FIND PHASE NAME
            $getOldPhaseName = PhaseSteps::where('step_id', $previousData->phase_steps_id)->first();

            $oldData = array(
                'study_id'    =>  session('current_study'),
                'step_name'   =>  $getOldPhaseName->step_name,
                'name'        =>  $previousData->name,
                'description' =>  $previousData->description,
                'sort_number' =>  $previousData->sort_number,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated section ' . $previousData->name . '.';
        }

        //////////////////////////// Section Ends /////////////////////////////////////////
    } else if ($eventSection == 'Study Site') {

        // get event data
        $eventData = StudySite::select(\DB::raw('CONCAT(sites.site_name, " - ", sites.site_code) AS site_name_code'))
            ->leftjoin('sites', 'sites.id', '=', 'site_study.site_id')
            ->where('site_study.study_id', $eventId)
            ->pluck('site_name_code')
            ->toArray();

        $eventData = $eventData != '' ? implode(', ', $eventData) : '';
        // get study name
        $getStudyName = Study::where('id', $eventId)->first();
        // set message for audit
        $auditMessage = Auth::user()->name . ' updated sites of study ' . $getStudyName->study_title . '.';
        // set audit url
        $auditUrl = url('studySite');
        // store data in event array
        $newData = array(
            'study_code' => $getStudyName->study_code,
            'study_name' => $getStudyName->study_title,
            'study_sites' => $eventData,
            'created_at' => date("Y-m-d h:i:s", strtotime($getStudyName->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($getStudyName->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $previousData = $previousData != '' ? implode(', ', $previousData) : '';

            $oldData = array(
                'study_code' => $getStudyName->study_code,
                'study_name' => $getStudyName->study_title,
                'study_sites' => $previousData,
                'created_at' => date("Y-m-d h:i:s", strtotime($getStudyName->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($getStudyName->updated_at)),
            );
        }

        //////////////////////////// Study Sites Ends /////////////////////////////////////////
    } else if ($eventSection == 'Study' && $eventType != 'Delete') {
        // get event data
        $eventData = Study::find($eventId);

        // set message for audit
        $auditMessage = Auth::user()->name . ' added study ' . $eventData->study_title . '.';

        // set audit url
        $auditUrl = url('studies');
        // store data in event array
        $newData = array(
            'study_short_name'  =>  $eventData->study_short_name,
            'study_title' => $eventData->study_title,
            'study_status'  => 'Development',
            'study_code' => $eventData->study_code,
            'protocol_number' => $eventData->protocol_number,
            'study_phase' => $eventData->study_phase,
            'trial_registry_id' => $eventData->trial_registry_id,
            'study_sponsor' => $eventData->study_sponsor,
            'start_date' => $eventData->start_date,
            'end_date' => $eventData->end_date,
            'description'   =>  $eventData->description,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {

            $oldData = array(
                'study_short_name'  =>  $previousData->study_short_name,
                'study_title' => $previousData->study_title,
                'study_status'  => 'Development',
                'study_code' => $previousData->study_code,
                'protocol_number' => $previousData->protocol_number,
                'study_phase' => $previousData->study_phase,
                'trial_registry_id' => $previousData->trial_registry_id,
                'study_sponsor' => $previousData->study_sponsor,
                'start_date' => $previousData->start_date,
                'end_date' => $previousData->end_date,
                'description'   =>  $previousData->description,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated study ' . $eventData->study_title . '.';
        }

        //////////////////////////// Study Ends /////////////////////////////////////////
    } else if ($eventSection == 'Study' && $eventType == 'Delete') {

        // get event data
        $eventData = Study::find($eventId);

        $eventId = $eventData->id;

        // set audit url
        $auditUrl = url('studies');
        // store data in event array
        $newData = array(
            'study_short_name'  =>  $eventData->study_short_name,
            'study_title' => $eventData->study_title,
            'study_status'  => 'Development',
            'study_code' => $eventData->study_code,
            'protocol_number' => $eventData->protocol_number,
            'study_phase' => $eventData->study_phase,
            'trial_registry_id' => $eventData->trial_registry_id,
            'study_sponsor' => $eventData->study_sponsor,
            'start_date' => $eventData->start_date,
            'end_date' => $eventData->end_date,
            'description'   =>  $eventData->description,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );

        // set message for audit
        $auditMessage = Auth::user()->name . ' deleted study ' . $eventData->study_title . '.';
    } else if ($eventSection == 'Subject' && $eventType != 'Delete') {
        // get event data
        $eventData = Subject::find($eventId);

        // set message for audit
        $auditMessage = Auth::user()->name . ' added subject ' . $eventData->subject_id . '.';
        // set audit url
        $auditUrl = url('subjects/' . $eventData->id);
        // get site name
        $site_study = StudySite::where('study_id', '=', Session::get('current_study'))
            ->where('site_id', $eventData->site_id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->first();

        $site_study = $site_study != null ? $site_study->site_name : 'N/A';

        // get disease cohort
        $diseaseCohort = DiseaseCohort::where('study_id', '=', Session::get('current_study'))
            ->where('id', $eventData->disease_cohort_id)
            ->first();

        $diseaseCohort = $diseaseCohort != null ? $diseaseCohort->name : 'N/A';

        // store data in event array
        $newData = array(
            'study_id' => Session::get('current_study'),
            'subject_id' => $eventData->subject_id,
            'enrollment_date' => $eventData->enrollment_date,
            'site_name' => $site_study,
            'disease_cohort' => $diseaseCohort,
            'study_eye' => $eventData->study_eye,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {
            // get site name
            $old_site_study = StudySite::where('study_id', '=', $previousData->study_id)
                ->where('site_id', $previousData->site_id)
                ->join('sites', 'sites.id', '=', 'site_study.site_id')
                ->select('sites.site_name', 'sites.id')
                ->first();

            $old_site_study = $old_site_study != null ? $old_site_study->site_name : 'N/A';

            // get disease cohort
            $old_diseaseCohort = DiseaseCohort::where('study_id', '=', $previousData->study_id)
                ->where('id', $previousData->disease_cohort_id)
                ->first();

            $old_diseaseCohort = $old_diseaseCohort != null ? $old_diseaseCohort->name : 'N/A';

            $oldData = array(
                'study_id' => $previousData->study_id,
                'subject_id' => $previousData->subject_id,
                'enrollment_date' => $previousData->enrollment_date,
                'site_name' => $old_site_study,
                'disease_cohort' => $old_diseaseCohort,
                'study_eye' => $previousData->study_eye,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = Auth::user()->name . ' updated subject ' . $eventData->subject_id . '.';
        }

        //////////////////////////// Subjects Ends /////////////////////////////////////////
    } else if ($eventSection == 'Subject' && $eventType == 'Delete') {
        // get event data
        $eventData = Subject::find($eventId);

        // set message for audit
        $auditMessage = Auth::user()->name . ' added subject ' . $eventData->subject_id . '.';
        // set audit url
        $auditUrl = url('subjects/' . $eventData->id);
        // get site name
        $site_study = StudySite::where('study_id', '=', Session::get('current_study'))
            ->where('site_id', $eventData->site_id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->first();

        $site_study = $site_study != null ? $site_study->site_name : 'N/A';

        // get disease cohort
        $diseaseCohort = DiseaseCohort::where('study_id', '=', Session::get('current_study'))
            ->where('id', $eventData->disease_cohort_id)
            ->first();

        $diseaseCohort = $diseaseCohort != null ? $diseaseCohort->name : 'N/A';

        // store data in event array
        $newData = array(
            'study_id' => Session::get('current_study'),
            'subject_id' => $eventData->subject_id,
            'enrollment_date' => $eventData->enrollment_date,
            'site_name' => $site_study,
            'disease_cohort' => $diseaseCohort,
            'study_eye' => $eventData->study_eye,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );

        //////////////////////////// Subjects Ends /////////////////////////////////////////
    } else if ($eventSection == 'Transmission Data') {
        // get event data
        $eventData = CrushFtpTransmission::find($eventId);
        // set message for audit
        $auditMessage = Auth::user()->name . ' updated transmission data of ' . $eventData->Transmission_Number . '.';
        // set audit url
        $auditUrl = url('transmissions');
        // store data in event array
        $newData = array(
            'transmission_number' => $eventData->Transmission_Number,
            'study_id' => $eventData->StudyI_ID,
            'study_name' => $eventData->Study_Name,
            'sponsor' => $eventData->sponsor,
            'submitter_name' => $eventData->Submitter_First_Name . ' ' . $eventData->Submitter_Last_Name,
            'submitter_email' => $eventData->Submitter_email,
            'submitter_phone' => $eventData->Submitter_phone,
            'submitter_role' => $eventData->Submitter_Role,
            'site_id' => $eventData->Site_ID,
            'site_name' => $eventData->Site_Name,
            'site_initials' => $eventData->Site_Initials,
            'site_st_address' => $eventData->Site_st_address,
            'site_city' => $eventData->Site_city,
            'site_state' => $eventData->Site_state,
            'site_zip' => $eventData->Site_Zip,
            'site_country' => $eventData->Site_country,
            'pi_name' => $eventData->PI_Name,
            'pi_email' => $eventData->PI_email,
            'subject_id' => $eventData->Subject_ID,
            'study_eye' => $eventData->StudyEye,
            'visit_name' => $eventData->visit_name,
            'visit_date' => $eventData->visit_date,
            'image_modality' => $eventData->ImageModality,
            'device_model' => $eventData->device_model,
            'submitted_by' => $eventData->Submitted_By,
            'photographer_full_name' => $eventData->photographer_full_name,
            'photographer_email' => $eventData->photographer_email,
            'status' => $eventData->status,
            'created_at' => date("Y-m-d h:i:s", strtotime($eventData->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($eventData->updated_at)),
        );
        // check if it is update case
        if ($eventType == 'Update') {
            // store data in event array
            $oldData = array(
                'transmission_number' => $previousData->Transmission_Number,
                'study_id' => $previousData->StudyI_ID,
                'study_name' => $previousData->Study_Name,
                'sponsor' => $previousData->sponsor,
                'submitter_name' => $previousData->Submitter_First_Name . ' ' . $previousData->Submitter_Last_Name,
                'submitter_email' => $previousData->Submitter_email,
                'submitter_phone' => $previousData->Submitter_phone,
                'submitter_role' => $previousData->Submitter_Role,
                'site_id' => $previousData->Site_ID,
                'site_name' => $previousData->Site_Name,
                'site_initials' => $previousData->Site_Initials,
                'site_st_address' => $previousData->Site_st_address,
                'site_city' => $previousData->Site_city,
                'site_state' => $previousData->Site_state,
                'site_zip' => $previousData->Site_Zip,
                'site_country' => $previousData->Site_country,
                'pi_name' => $previousData->PI_Name,
                'pi_email' => $previousData->PI_email,
                'subject_id' => $previousData->Subject_ID,
                'study_eye' => $previousData->StudyEye,
                'visit_name' => $previousData->visit_name,
                'visit_date' => $previousData->visit_date,
                'image_modality' => $previousData->ImageModality,
                'device_model' => $previousData->device_model,
                'submitted_by' => $previousData->Submitted_By,
                'photographer_full_name' => $previousData->photographer_full_name,
                'photographer_email' => $previousData->photographer_email,
                'status' => $previousData->status,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            $auditMessage = Auth::user()->name . ' updated transmission data of ' . $eventData->Transmission_Number . '.';
        } // update case ends

        ////////////////////////////////// Transmission Data //////////////////////////////////////
    } else if ($eventSection == 'QC Form' || $eventSection == 'Grading Form' || $eventSection == 'Adjudication Form' || $eventSection == 'System Adjudication Form') {

        $ip = 'N/A';
        // get event data
        $eventData = $eventId;
        // get study name
        $studyName = Study::where('id', $eventData[1]['study_id'])->first();
        //get subject
        $subjectName = Subject::where('id', $eventData[1]['subject_id'])->first();
        // visit name
        $visitName = StudyStructure::where('id', $eventData[1]['study_structures_id'])
            ->withOutGlobalScope(StudyStructureWithoutRepeatedScope::class)
            ->first();

        // get steps
        $stepName = PhaseSteps::where('step_id', $eventData[1]['phase_steps_id'])->first();
        // modality name
        $modalityName = Modility::where('id', $eventData[1]['modility_id'])->first();

        // set message for audit
        $auditMessage = Auth::user()->name . ' added ' . $eventSection . ' for Study ' . $studyName->study_title . '.';
        // set audit url
        $auditUrl = '';
        // store data in event array
        $newData = [];
        $editReason = '';

        //  loop through the dorm data
        foreach ($eventData as $key => $data) {

            // check for edit key
            if ($key == 0) {

                $editReason = $data;
            } else if ($key == 1) {
                //first time loop data
                $newData['study'] = $studyName->study_title;
                $newData['subject'] = $subjectName->subject_id;
                $newData['visit'] = $visitName->name;
                $newData['steps'] = $stepName->step_name;
                $newData['modility'] = $modalityName->modility_name;
                $newData['form_type'] = $data['form_type'];

                if ($eventSection == 'Adjudication Form' || $eventSection == 'System Adjudication Form') {

                    $newData['form_version_num'] = '';
                } else {

                    $newData['form_version_num'] = $data['form_version_num'];
                }

                $newData['edit_reason'] = $editReason;

                // get section name
                $sectionName = Section::where('id', $data['section_id'])->first();
                // get question
                $questionName = Question::where('id', $data['question_id'])->first();

                $newData['section_id'][$sectionName->name][] = array(
                    // "question_id" => $data['question_id'],
                    // "field_id" => $data['field_id'],
                    // "answer_id" => $data['answer_id'],
                    "question_label" => $questionName->question_text,
                    "answer" => $data['answer']
                );
            } else {

                // get section name
                $sectionName = Section::where('id', $data['section_id'])->first();
                // get question
                $questionName = Question::where('id', $data['question_id'])->first();

                $newData['section_id'][$sectionName->name][] = array(
                    // "question_id" => $data['question_id'],
                    // "field_id" => $data['field_id'],
                    // "answer_id" => $data['answer_id'],
                    "question_label" => $questionName->question_text,
                    "answer" => $data['answer']
                );
            }
        } // main loop ends

        // check if it is update case
        if ($eventType == 'Update') {

            $auditMessage = Auth::user()->name . ' updated ' . $eventSection . ' for Study ' . $studyName->study_title . '.';
        } // update case ends

        $eventId = 0;

        ///////////////////////////// QC/ Grading Form Data //////////////////////////////
    }   // main If else ends

    // Log the event
    $trailLog = new TrailLog;
    $trailLog->event_id = $eventId;
    $trailLog->event_section = $eventSection;
    $trailLog->event_type = $eventType;
    $trailLog->event_message = $auditMessage;
    $trailLog->user_id = Auth::user()->id;
    $trailLog->user_name = Auth::user()->name;
    $trailLog->role_id = Auth::user()->role_id;
    $trailLog->ip_address = $ip;
    $trailLog->study_id = Session::get('current_study') != null ? Session::get('current_study') : '';
    $trailLog->event_url = $auditUrl;
    $trailLog->event_details = json_encode($newData);
    $trailLog->event_old_details = json_encode($oldData);
    $trailLog->save();
} // trail log function ends

function buildSafeStr($id, $str = '')
{
    $safeStr = '';
    if (!empty($id)) {
        $safeStr = $str . str_replace([' ', '-'], '_', $id);
    }
    return $safeStr;
}

function buildFormFieldName($str = '')
{
    return str_replace([' ', '-'], '_', $str);
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

function showMessage()
{
    if (session()->has('message') && session()->get('message') != '') {
        echo '<div class="col-lg-12 success-alert"><div class="alert alert-primary success-msg" role="alert">' . session()->get('message') . '<button class="close" data-dismiss="alert">&times;</button></div></div>';
        session()->put('message', '');
    }
}

function return_bytes($size_str)
{
    switch (substr($size_str, -1)) {
        case 'M':
        case 'm':
            return (int)$size_str * 1048576;
        case 'K':
        case 'k':
            return (int)$size_str * 1024;
        case 'G':
        case 'g':
            return (int)$size_str * 1073741824;
        default:
            return $size_str;
    }
}
