<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserRoles\Entities\UserRole;
use Modules\Admin\Entities\Annontation;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Entities\SubjectsPhases;

class Study extends Model
{
    use SoftDeletes;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'parent_id',
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
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class)->withPivot('study_id', 'site_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
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
        return $this->hasMany(StudyStructure::class, 'id', 'study_id');
    }

    public function studySteps()
    {
        return $this->hasManyThrough(PhaseSteps::class, StudyStructure::class, 'study_id', 'phase_id', 'id', 'id');
    }

    public static function calculateFormPercentage($studyId)
    {
        $qc_percentage = 0;
        $grading_percentage = 0;
        $adjudication_percentage = 0;

        $studyPhasesIdsArray = StudyStructure::getStudyPhaseIdsArray($studyId);
        $activatedPhasesidsArray = SubjectsPhases::getActivatedPhasesidsArray($studyPhasesIdsArray);
        $qcStepsIdsArray = PhaseSteps::getStepsIdsArray(1, $activatedPhasesidsArray);
        $gradingStepsIdsArray = PhaseSteps::getStepsIdsArray(2, $activatedPhasesidsArray);

        /*********************************************************/

        $completedQcStepsIdsArray = FormStatus::getStepsIdsArrayByStatusAndFormType(1, 'complete', $qcStepsIdsArray);
        if (count($qcStepsIdsArray) > 0) {
            $qc_percentage = round((count($completedQcStepsIdsArray) / count($qcStepsIdsArray)) * 100);
        }


        /*********************************************************/

        $completedGradingStepsIdsArray = FormStatus::getStepsIdsArrayByStatusAndFormType(2, 'complete', $gradingStepsIdsArray);
        if (count($gradingStepsIdsArray) > 0) {
            $grading_percentage = round((count($completedGradingStepsIdsArray) / count($gradingStepsIdsArray)) * 100);
        }

        /*********************************************************/

        $completedAdjudicationStepsIdsArray = AdjudicationFormStatus::getStepsIdsArrayByStatus('complete', $gradingStepsIdsArray);
        if (count($gradingStepsIdsArray) > 0) {
            $adjudication_percentage = round((count($completedAdjudicationStepsIdsArray) / count($gradingStepsIdsArray)) * 100);
        }

        return '<div class="progress">
            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="' . $qc_percentage . '"
            aria-valuemin="0" aria-valuemax="100" style="width:' . $qc_percentage . '%">
            ' . $qc_percentage . '%
            </div>
        </div>
        <br/>
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $grading_percentage . '"
            aria-valuemin="0" aria-valuemax="100" style="width:' . $grading_percentage . '%">
            ' . $grading_percentage . '%
            </div>
        </div>
        <br/>
        <div class="progress">
            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="' . $adjudication_percentage . '"
            aria-valuemin="0" aria-valuemax="100" style="width:' . $adjudication_percentage . '%">
            ' . $adjudication_percentage . '%
            </div>
        </div>';
    }
}
