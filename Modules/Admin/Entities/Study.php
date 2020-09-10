<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\UserRoles\Entities\UserRole;

class Study extends Model
{
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'study_short_name',
        'study_title',
        'study_status',
        'study_code',
        'protocol_number',
        'study_phase',
        'trial_registry_id',
        'study_sponsor',
        'description','start_date','end_date'];
    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class,'study_user');
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class)->withPivot('study_id', 'site_id');
    }

    public function subjects(){
        return $this->belongsTo(Subject::class);
    }

    public function diseaseCohort(){
        return $this->hasMany(DiseaseCohort::class);
    }

    public function roles(){
        return $this->hasMany(RoleStudy::class);
    }

}
