<?php

namespace Modules\CertificationApp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyModility extends Model
{

    //use SoftDeletes;
    protected $table = 'study_modilities';

    protected $fillable = [];

    public static function checkAssignedModilities($parentModility, $childModility, $studyId)
    {
        $checkAssignedModilities = self::where('parent_modility_id', $parentModility)
            ->where('child_modility_id', $childModility)
            ->where('study_id', decrypt($studyId))
            ->first();

        // check if this modality is already assigned to study
        if ($checkAssignedModilities != null) {

            return '<span class="badge badge-success">Yes</span>';
        } else {

            return '<span class="badge badge-primary">No</span>';
        } // check ends
    }

    public static function checkAssignedUser($parentModility, $childModility, $studyId)
    {
        $checkAssignedUser = self::select('users.name')
            ->leftjoin('users', 'users.id', '=', 'study_modilities.assign_by')
            ->where('parent_modility_id', $parentModility)
            ->where('child_modility_id', $childModility)
            ->where('study_id', decrypt($studyId))
            ->first();

        // check if this modality is already assigned to study
        if ($checkAssignedUser != null) {

            return $checkAssignedUser->name;
        } else {

            return 'N/A';
        } // check ends
    }
}
