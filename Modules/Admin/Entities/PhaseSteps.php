<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Scopes\PhaseStepOrderByScope;
use Modules\Admin\Scopes\ActivePhaseStepScope;
use Modules\UserRoles\Entities\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Study;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FormVersion;
use Modules\FormSubmission\Entities\SubjectsPhases;
use Modules\FormSubmission\Traits\JSQuestionDataValidation;
use Modules\FormSubmission\Traits\JSStepCalculatedFields;
use Modules\FormSubmission\Entities\FormStatus;

class PhaseSteps extends Model
{
    use softDeletes;
    use JSQuestionDataValidation;
    use JSStepCalculatedFields;

    protected $fillable = [
        'step_id', 'phase_id', 'step_position', 'form_type', 'form_type_id', 'modility_id', 'step_name',
        'step_description', 'graders_number', 'q_c', 'eligibility', 'parent_id', 'replicating_or_cloning', 'deleted_at'
    ];
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

    public function scopeActive($query)
    {
        return $query->where('phase_steps.is_active', 1);
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

    static function phaseStepsbyPermissions($subjectId, $phaseId)
    {
        $formTypeArray = [];
        if (canQualityControl(['index'])) {
            $formTypeArray[] = 1;
        }
        if (canGrading(['index'])) {
            $formTypeArray[] = 2;
        }
        if (canEligibility(['index'])) {
            $formTypeArray[] = 3;
        }
        if (canAdjudication(['index'])) {
            $formTypeArray[] = 2;
            $formTypeArray[] = 3;
        } else {
            $formTypeArray[] = 4;
        }

        $modalityIdsArray = SubjectsPhases::where('subject_id', 'like', $subjectId)->where('phase_id', 'like', $phaseId)->distinct()->pluck('modility_id')->toArray();
        return self::where('phase_id', $phaseId)
            ->whereIn('form_type_id', $formTypeArray)
            ->whereIn('modility_id', $modalityIdsArray)
            //->active()
            ->get();
    }
    public function phase()
    {
        return $this->belongsTo(StudyStructure::class, 'phase_id', 'id');
    }

    public function modility()
    {
        return $this->belongsTo(Modility::class, 'modility_id', 'id');
    }

    public static function getStepsIdsArray($form_type_id, $activatedPhasesid)
    {
        return self::where('form_type_id', $form_type_id)
            ->where('phase_id', $activatedPhasesid)
            ->pluck('modility_id')
            ->toArray();
    }

    public static function getStepStatus($studyId, $subject, $phase, $modality, $formStatus, $formTypeId, $type) {
        
        // check grader number 
        $assignedUserNumbers = self::where('phase_id', $phase)
            ->where('modility_id', $modality)
            ->where('form_type_id', $formTypeId)
            ->first();

        // check Qc and Grading
        if ($type == 'Qc' || $type == 'Grading') {
            // check complete forms
            $getFormStatus = FormStatus::where('study_id', $studyId)
                                        ->where('subject_id', $subject)
                                        ->where('study_structures_id', $phase)
                                        ->where('modility_id', $modality)
                                        ->where('form_type_id', $formTypeId)
                                        ->where('form_status', $formStatus)
                                        ->get();
            
            if(!$getFormStatus->isEmpty()) {
                // compare grader numbers with form complete number
                if($getFormStatus->count() >= $assignedUserNumbers->graders_number) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {

            $getFormStatus = AdjudicationFormStatus::where('study_id', $studyId)
                                        ->where('subject_id', $subject)
                                        ->where('study_structures_id', $phase)
                                        ->where('modility_id', $modality)
                                        ->where('form_type_id', $formTypeId)
                                        ->where('adjudication_status', $formStatus)
                                        ->first();
            if($getFormStatus != null){
                return true;
            } else {
                return false;
            }
       
        } // type check ends
    }

    public static function isStepActive($step_id)
    {
        $step = self::where('step_id', 'like', $step_id)->first();
        if ($step->is_active == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function getFormVersion($step_id)
    {
        $formVersionObj = FormVersion::getFormVersionObj($step_id);
        $formVersion = 0;
        if (null !== $formVersionObj) {
            $formVersion = $formVersionObj->form_version_num;
        }
        return $formVersion;
    }

    public static function isThisStepHasData($stepId)
    {
        $step = PhaseSteps::find($stepId);
        $answer = Answer::where('study_structures_id', 'like', $step->phase_id)
            ->where('phase_steps_id', 'like', $step->step_id)
            ->first();
        if (null !== $answer) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function countGradingSteps($activatedPhasesidsArray, $modilityIdsFromActivatedPhasesIdsArray)
    {
        $steps = self::where('form_type_id', 2)
            ->whereIn('phase_id', $activatedPhasesidsArray)
            ->whereIn('modility_id', $modilityIdsFromActivatedPhasesIdsArray)
            ->get();

        $count = 0;
        foreach ($steps as $step) {
            $count += $step->graders_number;
        }
        return $count;
    }

    public static function getEligibilityStep($phaseId, $modilityId)
    {
        return self::where('form_type_id', 3)
            ->where('phase_id', $phaseId)
            ->where('modility_id', $modilityId)
            ->first();
    }
}
