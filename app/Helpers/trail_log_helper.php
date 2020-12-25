<?php

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

function eventDetails($eventId, $eventSection, $eventType, $ip, $previousData)
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
        $auditMessage = \Auth::user()->name . ' added option group ' . $eventData->option_group_name . '.';
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

            $auditMessage = \Auth::user()->name . ' updated option group ' . $eventData->option_group_name . '.';
        } // update case ends

        //////////////////////////////// Site ////////////////////////////////////////////
    } else if ($eventSection == 'Site') {
        // get event data
        $eventData = Site::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added site ' . $eventData->site_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated site ' . $eventData->site_name . '.';
        }

        /////////////////////////////// Primary Investigator /////////////////////////////////////////////
    } else if ($eventSection == 'Primary Investigator') {
        // get event data
        $eventData = PrimaryInvestigator::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added primary investigator ' . $eventData->first_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated primary investigator ' . $eventData->first_name . '.';
        }

        ///////////////////////////////////// Coordinator ///////////////////////////////////////////////////
    } else if ($eventSection == 'Coordinator') {
        // get event data
        $eventData = Coordinator::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added coordinator ' . $eventData->first_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated coordinator ' . $eventData->first_name . '.';
        }

        //////////////////////////////////////// Photographer /////////////////////////////////////////////
    } else if ($eventSection == 'Photographer') {
        // get event data
        $eventData = Photographer::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added photographer ' . $eventData->first_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated photographer ' . $eventData->first_name . '.';
        }

        //////////////////////////////////////////// Others ////////////////////////////////////////////////
    } else if ($eventSection == 'Others') {
        // get event data
        $eventData = Other::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added others ' . $eventData->first_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated others ' . $eventData->first_name . '.';
        }

        /////////////////////////////// Others Section ends //////////////////////////////////////
    } else if ($eventSection == 'Annotation') {
        // get event data
        $eventData = Annotation::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added annotation ' . $eventData->label . '.';
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
            $auditMessage = \Auth::user()->name . ' updated annotation ' . $eventData->label . '.';
        }

        ///////////////////////// Annotaion Sections ends ///////////////////////////////////////////
    } else if ($eventSection == 'Role') {
        // get event data
        $eventData = Role::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added role ' . $eventData->name . '.';
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
        if ($eventType == 'Update') {

            $oldData = array(
                'name' => $previousData->name,
                'description' => $previousData->description,
                'role_type' => $previousData->role_type,
                'created_by' => \Auth::user()->name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name . ' updated role ' . $eventData->name . '.';
        }

        ////////////////////////// Role Ends ///////////////////////////////////////////////////
    } else if ($eventSection == 'User') {
        // get event data
        $eventData = User::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added system user ' . $eventData->name . '.';
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
        if ($eventType == 'Update') {

            $oldData = array(
                'name' => $previousData->name,
                'email' => $previousData->email,
                'created_by' => \Auth::user()->name,
                'created_at' => date("Y-m-d h:i:s", strtotime($previousData->created_at)),
                'updated_at' => date("Y-m-d h:i:s", strtotime($previousData->updated_at)),
            );

            // set message for audit
            $auditMessage = \Auth::user()->name . ' updated system user ' . $eventData->name . '.';
        }

        ////////////////////////// System Users Ends ///////////////////////////////////////////////////
    } else if ($eventSection == 'Modality') {
        // get event data
        $eventData = Modility::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added modality ' . $eventData->modility_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated modality ' . $eventData->modility_name . '.';
        }

        //////////////////////////// Modality Ends /////////////////////////////////////////
    } else if ($eventSection == 'Child Modality') {
        // get event data
        $eventData = ChildModilities::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added child modality ' . $eventData->modility_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated child modality ' . $eventData->modility_name . '.';
        }

        ////////////////////////// Child Modality Ends /////////////////////////////////////////
    } else if ($eventSection == 'Device') {
        // get event data
        $eventData = Device::find($eventId);
        // set message for audit
        $auditMessage = \Auth::user()->name . ' added device ' . $eventData->device_name . '.';
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
            $auditMessage = \Auth::user()->name . ' updated device ' . $eventData->device_name . '.';
        }

        //////////////////////////// Device Ends /////////////////////////////////////////
    } else if ($eventSection == 'Study Site') {
        // get event data
        $eventData = StudySite::select('sites.site_name')
            ->leftjoin('sites', 'sites.id', '=', 'site_study.site_id')
            ->where('site_study.study_id', $eventId)
            ->pluck('sites.site_name')
            ->toArray();

        $eventData = $eventData != '' ? implode(', ', $eventData) : '';
        // get study name
        $getStudyName = Study::where('id', $eventId)->first();
        // set message for audit
        $auditMessage = \Auth::user()->name . ' updated sites of study ' . $getStudyName->study_title . '.';
        // set audit url
        $auditUrl = url('studySite');
        // store data in event array
        $newData = array(
            'study_id' => $getStudyName->id,
            'study_name' => $getStudyName->study_title,
            'study_sites' => $eventData,
            'created_at' => date("Y-m-d h:i:s", strtotime($getStudyName->created_at)),
            'updated_at' => date("Y-m-d h:i:s", strtotime($getStudyName->updated_at)),
        );
        // if it is update case
        if ($eventType == 'Update') {
            $previousData = $previousData != '' ? implode(', ', $previousData) : '';
            $oldData = array(
                'study_id' => $getStudyName->id,
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
        $auditMessage = \Auth::user()->name . ' added study ' . $eventData->study_title . '.';

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
            $auditMessage = \Auth::user()->name . ' updated study ' . $eventData->study_title . '.';
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
        $auditMessage = \Auth::user()->name . ' deleted study ' . $eventData->study_title . '.';
    } else if ($eventSection == 'Subject' && $eventType != 'Delete') {
        // get event data
        $eventData = Subject::find($eventId);

        // set message for audit
        $auditMessage = \Auth::user()->name . ' added subject ' . $eventData->subject_id . '.';
        // set audit url
        $auditUrl = url('subjects/' . $eventData->id);
        // get site name
        $site_study = StudySite::where('study_id', '=', \Session::get('current_study'))
            ->where('site_id', $eventData->site_id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->first();

        $site_study = $site_study != null ? $site_study->site_name : 'N/A';

        // get disease cohort
        $diseaseCohort = DiseaseCohort::where('study_id', '=', \Session::get('current_study'))
            ->where('id', $eventData->disease_cohort_id)
            ->first();

        $diseaseCohort = $diseaseCohort != null ? $diseaseCohort->name : 'N/A';

        // store data in event array
        $newData = array(
            'study_id' => \Session::get('current_study'),
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
            $auditMessage = \Auth::user()->name . ' updated subject ' . $eventData->subject_id . '.';
        }

        //////////////////////////// Subjects Ends /////////////////////////////////////////
    } else if ($eventSection == 'Subject' && $eventType == 'Delete') {
        // get event data
        $eventData = Subject::find($eventId);

        // set message for audit
        $auditMessage = \Auth::user()->name . ' added subject ' . $eventData->subject_id . '.';
        // set audit url
        $auditUrl = url('subjects/' . $eventData->id);
        // get site name
        $site_study = StudySite::where('study_id', '=', \Session::get('current_study'))
            ->where('site_id', $eventData->site_id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->first();

        $site_study = $site_study != null ? $site_study->site_name : 'N/A';

        // get disease cohort
        $diseaseCohort = DiseaseCohort::where('study_id', '=', \Session::get('current_study'))
            ->where('id', $eventData->disease_cohort_id)
            ->first();

        $diseaseCohort = $diseaseCohort != null ? $diseaseCohort->name : 'N/A';

        // store data in event array
        $newData = array(
            'study_id' => \Session::get('current_study'),
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
        $auditMessage = \Auth::user()->name . ' updated transmission data of ' . $eventData->Transmission_Number . '.';
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

            $auditMessage = \Auth::user()->name . ' updated transmission data of ' . $eventData->Transmission_Number . '.';
        } // update case ends

        ////////////////////////////////// Transmission Data //////////////////////////////////////
    } else if ($eventSection == 'QC Form' || $eventSection == 'Grading Form' || $eventSection == 'Adjudication Form') {

        $ip = 'N/A';

        // get event data
        $eventData = $eventId;
        // get study name
        $studyName = Study::where('id', $eventData[1]['study_id'])->first();
        //get subject
        $subjectName = Subject::where('id', $eventData[1]['subject_id'])->first();
        // visit name
        $visitName = StudyStructure::find($eventData[1]['study_structures_id']);

        // get steps
        $stepName = PhaseSteps::where('step_id', $eventData[1]['phase_steps_id'])->first();
        // modality name
        $modalityName = Modility::where('id', $eventData[1]['modility_id'])->first();

        // set message for audit
        $auditMessage = \Auth::user()->name . ' added ' . $eventSection . ' for Study ' . $studyName->study_title . '.';
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
                $newData['form_version_num'] = $data['form_version_num'];
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

            $auditMessage = \Auth::user()->name . ' updated ' . $eventSection . ' for Study ' . $studyName->study_title . '.';
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
