<?php

namespace Modules\Admin\Entities;

use Modules\UserRoles\Entities\Role;
use Modules\Admin\Entities\Study;
use Modules\Admin\Scopes\StudyStructureOrderByScope;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Illuminate\Database\Eloquent\Model;

class StudyStructure extends Model
{
    protected $fillable = ['id', 'study_id', 'name', 'position', 'duration'];
    // protected $keyType = 'string';
    protected $casts = [
        'id' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StudyStructureOrderByScope);
        static::addGlobalScope(new StudyStructureWithoutRepeatedScope);
    }

    public function phases()
    {
        return $this->hasMany(PhaseSteps::class, 'phase_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'study_structures_roles', 'phase_id', 'role_id');
    }

    static function phasesbyRoles($studyId, $userRoleIds)
    {
        return self::whereHas('roles', function ($query) use ($userRoleIds) {
            $query->whereIn('role_id', $userRoleIds);
        })
            ->where('study_id', $studyId)
            ->get();
    }
    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }
    public function steps()
    {
        return $this->hasMany(PhaseSteps::class, 'phase_id', 'id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subjects_phases', 'phase_id', 'subject_id');
    }

    public function phaseSubjects()
    {
        return $this->hasMany(SubjectsPhases::class, 'phase_id', 'id');
    }

    public function phaseSubjectsArray()
    {
        return $this->phaseSubjects()->distinct()->pluck('subject_id')->toArray();
    }
}
