<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\PhaseStepOrderByScope;
use Modules\UserRoles\Entities\Role;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\PhaseSteps;

class PhaseSteps extends Model
{
    protected $fillable = ['step_id', 'phase_id', 'step_position', 'form_type', 'form_type_id', 'step_name', 'step_description', 'graders_number', 'q_c', 'eligibility'];
    // protected $key = 'string';
    protected $table = 'phase_steps';
    protected $primaryKey = "step_id";
    protected $casts = [
        'step_id' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PhaseStepOrderByScope);
    }

    // public function steps()
    // {
    //     return $this->belongsTo(StudyStructure::class,'step_id','phase_id');
    // }
    public function steps()
    {
        return $this->belongsTo(StudyStructure::class, 'phase_id', 'step_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'phase_steps_id', 'step_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'phase_steps_roles', 'step_id', 'role_id');
    }

    static function phaseStepsbyRoles($phaseId, $userRoleIds)
    {
        return self::whereHas('roles', function ($query) use ($userRoleIds) {
            $query->whereIn('role_id', $userRoleIds);
        })
            ->where('phase_id', $phaseId)
            ->get();
    }
    public function formType()
    {
        return $this->belongsTo(FormType::class, 'form_type_id', 'id')->withDefault();
    }

    static function phaseStepsbyPermissions($phaseId)
    {
        $formTypeArray = [];
        if (canQualityControl()) {
            $formTypeArray[] = 1;
        }
        if (canGrading()) {
            $formTypeArray[] = 2;
        }
        if (canEligibility()) {
            $formTypeArray[] = 3;
        }
        if (canAdjudication()) {
            $formTypeArray[] = 2;
        } else {
            $formTypeArray[] = 4;
        }
        //dd($formTypeArray);
        return self::where('phase_id', $phaseId)->whereIn('form_type_id', $formTypeArray)->get();
    }
    public function phase()
    {
        return $this->belongsTo(StudyStructure::class, 'phase_id', 'step_id');
    }
}
