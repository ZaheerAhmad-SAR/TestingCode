<?php

namespace Modules\Admin\Entities;

use Modules\UserRoles\Entities\Role;
use Modules\Admin\Entities\Study;
use Modules\Admin\Scopes\StudyStructureOrderByScope;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\CohortSkipLogicOption;
use Modules\FormSubmission\Traits\JSCohortSkipLogic;

class StudyStructure extends Model
{
    use SoftDeletes;
    use JSCohortSkipLogic;

    protected $fillable = ['id', 'study_id', 'name', 'position', 'duration','window','is_repeatable', 'parent_id', 'replicating_or_cloning', 'count', 'old_id', 'deleted_at'];
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

    public function replicationStructures()
    {
        return $this->hasMany(PhaseReplicationStructure::class, 'study_structures_id', 'id');
    }

    public static function getStudyPhaseIdsArray($studyId)
    {
        return self::where('study_id', 'like', $studyId)
            ->withOutGlobalScope(StudyStructureWithoutRepeatedScope::class)
            ->pluck('id')
            ->toArray();
    }

    public static function getStepIdsInPhaseArray($phaseId)
    {
        return PhaseSteps::where('phase_id', 'like', $phaseId)->pluck('step_id')->toArray();
    }

    public static function getSectionIdsInPhaseArray($phaseId)
    {
        $stepIds = PhaseSteps::where('phase_id', 'like', $phaseId)->pluck('step_id')->toArray();
        return Section::whereIn('phase_steps_id', $stepIds)->pluck('id')->toArray();
    }

    public static function getQuestionIdsInPhaseArray($phaseId)
    {
        $stepIds = PhaseSteps::where('phase_id', 'like', $phaseId)->pluck('step_id')->toArray();
        $sectionIds = Section::whereIn('phase_steps_id', $stepIds)->pluck('id')->toArray();
        return Question::whereIn('section_id', $sectionIds)->pluck('id')->toArray();
    }

    public static function getPhaseIdByQuestionId($questionId)
    {
        $question = Question::find($questionId);
        $section = Section::find($question->section_id);
        $step = PhaseSteps::find($section->phase_steps_id);
        $phase = self::where('id', $step->phase_id)->withOutGlobalScopes()->first();
        return $phase->id;
    }

    public function cohortSkipLogics()
    {
        return $this->hasMany(CohortSkipLogic::class, 'phase_id', 'id');
    }

    public function questionOptionsCohortSkipLogics()
    {
        return $this->hasMany(CohortSkipLogicOption::class, 'phase_id', 'id');
    }
}
