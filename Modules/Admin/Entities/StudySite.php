<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudySite extends Model
{
    use softDeletes;
    protected $table = 'site_study';
    protected $fillable = ['id', 'study_id', 'site_id', 'study_site_id', 'primaryInvestigator_id'];
    protected $keyType = 'string';

    public function primaryInvestigator()
    {
        return $this->hasMany(PrimaryInvestigator::class, 'site_id', 'id');
    }

    public function siteStudyCoordinator()
    {
        return $this->hasMany(SiteStudyCoordinator::class, 'site_study_id', 'id');
    }

    public function siteStudyCoordinatorIds()
    {
        return $this->siteStudyCoordinator->pluck('coordinator_id')->toArray();
    }


    public static function checkAssignedStudySite($study_id, $site_id)
    {
        $checkAssignedSite = self::where('study_id', $study_id)
            ->where('site_id', $site_id)
            ->first();
        // check if this modality is already assigned to study
        if ($checkAssignedSite != null) {
            return '<span class="badge badge-success">Yes</span>';
        } else {
            return '<span class="badge badge-primary">No</span>';
        } // check ends
    }
}
