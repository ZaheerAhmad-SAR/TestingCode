<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyDevice extends Model
{
    use SoftDeletes;
    protected $fillable = [];

    public static function checkAssignedDevices($deviceId, $studyId)
    {

        $checkAssignedDevices = self::where('device_id', $deviceId)
                                    ->where('study_id', $studyId)
                                    ->first();

            // check if this device is already assigned to study
            if ($checkAssignedDevices != null) { 

            	return '<span class="badge badge-success">Yes</span>';

            } else {

            	return '<span class="badge badge-primary">No</span>';

            } // check ends

    } // devices ends

    public static function checkAssignedUser($deviceId, $studyId)
    {
        $checkAssignedUser = self::select('users.name')
        						->leftjoin('users', 'users.id', '=', 'study_devices.assign_by')
								->where('device_id', $deviceId)
                                ->where('study_id', $studyId)
                                ->first();

            // check if this modality is already assigned to study
            if ($checkAssignedUser != null) {

            	return $checkAssignedUser->name;

            } else {

            	return 'N/A';

            } // check ends
    }
}
