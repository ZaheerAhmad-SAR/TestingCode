<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserRoles\Entities\UserRole;
use Modules\Admin\Entities\Annontation;
use Modules\Admin\Entities\StudyStructure;

class Study extends Model
{
    use SoftDeletes;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'study_short_name',
        'study_title',
        'study_status',
        'study_code',
        'protocol_number',
        'trial_registry_id',
        'study_sponsor',
        'description', 'start_date', 'end_date'
    ];
    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'study_user');
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class)->withPivot('study_id', 'site_id');
    }

    public function subjects()
    {
        return $this->hasOne(Subject::class);
    }

    public function diseaseCohort()
    {
        return $this->hasMany(DiseaseCohort::class);
    }

    public function roles()
    {
        return $this->hasMany(RoleStudy::class);
    }
    public function studySites()
    {
        return $this->belongsToMany(StudySite::class, 'site_study', 'study_id', 'site_id');
    }
    public function phase()
    {
        return $this->hasMany(StudyStructure::class, 'study_id', 'id');
    }

    public function studySteps(){
       return $this->hasManyThrough(PhaseSteps::class,StudyStructure::class,'study_id','phase_id','step_id','id');
    }


}
