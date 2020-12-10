<?php

namespace Modules\Admin\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserRoles\Entities\Permission;
use Modules\Admin\Entities\RoleStudyUser;
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
        return $this->belongsToMany(User::class, 'user_roles', 'user_id', '');
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
        $subjectIdsFromActivatedPhasesIdsArray = SubjectsPhases::getSubjectIdsFromActivatedPhasesidsArray($activatedPhasesidsArray);
        $subjectIdsFromActivatedPhasesIdsArray = array_unique($subjectIdsFromActivatedPhasesIdsArray);
        $modilityIdsFromActivatedPhasesIdsArray = SubjectsPhases::getModilityIdsFromActivatedPhasesidsArray($activatedPhasesidsArray);
        $qcStepsIdsArray = PhaseSteps::getStepsIdsArray(1, $activatedPhasesidsArray);
        $gradingStepsIdsArray = PhaseSteps::getStepsIdsArray(2, $activatedPhasesidsArray);
        $countGradingSteps = PhaseSteps::countGradingSteps($activatedPhasesidsArray, $modilityIdsFromActivatedPhasesIdsArray);

        /*********************************************************/

        $completedQcStepsIdsArray = FormStatus::getStepsIdsArrayByStatusAndFormType(1, 'complete', $qcStepsIdsArray);
        if (count($qcStepsIdsArray) > 0 && count($subjectIdsFromActivatedPhasesIdsArray) > 0) {
            $qc_percentage = round((count($completedQcStepsIdsArray) / (count($qcStepsIdsArray) * count($subjectIdsFromActivatedPhasesIdsArray))) * 100);
        }


        /*********************************************************/

        $completedGradingStepsIdsArray = FormStatus::getStepsIdsArrayByStatusAndFormType(2, 'complete', $gradingStepsIdsArray);
        //dd($subjectIdsFromActivatedPhasesIdsArray);
        if (count($gradingStepsIdsArray) > 0) {
            $grading_percentage = round((count($completedGradingStepsIdsArray) / ($countGradingSteps * count($subjectIdsFromActivatedPhasesIdsArray))) * 100);
        }

        /*********************************************************/

        $completedAdjudicationStepsIdsArray = AdjudicationFormStatus::getStepsIdsArrayByStatus('complete', $gradingStepsIdsArray);
        if (count($gradingStepsIdsArray) > 0) {
            $adjudication_percentage = round((count($completedAdjudicationStepsIdsArray) / (count($gradingStepsIdsArray) * count($subjectIdsFromActivatedPhasesIdsArray))) * 100);
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

    public function studyuserroles()
    {
        return $this->hasMany(RoleStudyUser::class);
    }

    public static function getAllStudies()
    {
        $studies = self::all();
        return $studies;
    }

    public static function getstudyAdminsName($id)
    {
        $userNames = '';
        if (null !== Permission::getStudyAdminRole()) {
            $userIds = RoleStudyUser::where('study_id', 'LIKE', $id)->whereIn('role_id', Permission::getStudyAdminRole())->pluck('user_id')->toArray();
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                $userNames .=  '<p style="border-bottom:1px solid gray;">';
                $userNames .=  $user->name;
                $userNames .=  '</p>';
            }
        }

        return $userNames;
    }

    public static function getStudiesAganistAdmin()
    {
        $studyIds = [];
        if (null !== Permission::getStudyAdminRole()) {
            $studyIds = RoleStudyUser::where('user_id', 'LIKE', auth()->user()->id)->whereIn('role_id', Permission::getStudyAdminRole())->pluck('study_id')->toArray();
        }
        return $studyIds;
    }

    public static function getStudiesAganistQC()
    {
        $studyIds = [];
        if (null !== Permission::getStudyQCRole()) {
            $studyIds = RoleStudyUser::where('user_id', 'LIKE', auth()->user()->id)->whereIn('role_id', Permission::getStudyQCRole())->pluck('study_id')->toArray();
        }
        return $studyIds;
    }

    public static function getStudiesAganistGrader()
    {
        $studyIds = [];
        if (null !== Permission::getStudyGraderRole()) {
            $studyIds = RoleStudyUser::where('user_id', 'LIKE', auth()->user()->id)->whereIn('role_id', Permission::getStudyGraderRole())->pluck('study_id')->toArray();
        }
        return $studyIds;
    }

    public static function getStudiesAganistAdjudicator()
    {
        $studyIds = [];
        if (null !== Permission::getStudyAdjudicationRole()) {
            $studyIds = RoleStudyUser::where('user_id', 'LIKE', auth()->user()->id)->whereIn('role_id', Permission::getStudyAdjudicationRole())->pluck('study_id')->toArray();
        }
        return $studyIds;
    }

    public static function getAssignedStudyAdminsName($id)
    {
        $userNames = [];
        if (null !== Permission::getStudyAdminRole()) {
            $userIds = RoleStudyUser::where('study_id', 'LIKE', $id)->whereIn('role_id', Permission::getStudyAdminRole())->pluck('user_id')->toArray();
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                $userNames[$user->id] = $user->name;
            }
        }

        return json_encode($userNames);
    }

    public static function getDiseaseCohort($study)
    {
        $diseaseCohortArray = [];
        foreach ($study->diseaseCohort as $diseaseCohort) {
            $diseaseCohortArray[] = $diseaseCohort->name;
        }
        return implode(', ', $diseaseCohortArray);
    }
}
