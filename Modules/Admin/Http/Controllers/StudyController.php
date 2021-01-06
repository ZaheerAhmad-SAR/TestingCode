<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\AdjudicationFormStatus;
use Modules\Admin\Entities\Annotation;
use Modules\Admin\Entities\AnnotationDescription;
use Modules\Admin\Entities\Preference;
use Modules\Admin\Entities\SiteStudyCoordinator;
use Modules\FormSubmission\Entities\AdjudicationFormRevisionHistory;
use Modules\FormSubmission\Entities\Answer;
use Modules\Admin\Entities\CrushFtpTransmission;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\StudyUser;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\TrailLog;
use Modules\FormSubmission\Entities\FormRevisionHistory;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Entities\SubjectsPhases;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;
use Modules\Queries\Entities\Query;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\UserRole;
use Illuminate\Support\Str;
use Modules\Admin\Entities\AssignWork;
use Modules\Admin\Entities\FormFields;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionComments;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\QuestionOption;
use Modules\Admin\Entities\QuestionValidation;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\SkipLogic;
use Modules\FormSubmission\Entities\ExportType;
use Modules\FormSubmission\Entities\FormVersion;
use Modules\FormSubmission\Entities\QuestionAdjudicationRequired;
use Modules\Queries\Entities\QueryNotification;
use Modules\UserRoles\Entities\StudyRoleUsers;

class StudyController extends Controller
{
    use ReplicatePhaseStructure;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        session([
            'current_study' => '',
            'study_short_name' => '',
            'study_code' => ''
        ]);
        $studyAdminRoleId = Permission::getStudyAdminRole();
        if (count((array)$studyAdminRoleId) == 0) {
            echo '<a href="' . route('roles.index') . '">Please add study admin role first</a>';
            exit;
        }
        $systemRoleIds = Role::where('role_type', 'system_role')->pluck('id')->toArray();
        $userIdsWithStudyAdminRole = UserRole::whereIn('role_id', $studyAdminRoleId)->pluck('user_id')->toArray();
        if (count((array)$userIdsWithStudyAdminRole) == 0) {
            echo '<a href="' . route('users.index') . '">Please add study admin first</a>';
            exit;
        }
        $users = User::whereIn('id', $userIdsWithStudyAdminRole)->get();

        $sites = Site::all();
        $studies = [];
        $studiesIDs = [];
        $user = \auth()->user()->id;
        if (hasPermission(\auth()->user(), 'systemtools.index')) {
            if (isThisUserSuperAdmin(\auth()->user())) {
                $studiesIDs = array_merge($studiesIDs, Study::all()->pluck('id')->toArray());
            } else {
                $userRole = UserRole::where('user_id', \auth()->user()->id)->first();
                if (count($studyAdminRoleId) > 0 && in_array($userRole->role_id, $studyAdminRoleId)) {
                    $studiesIDs = array_merge($studiesIDs, Study::getStudiesAganistAdmin());
                } else {
                    $studiesIDs = array_merge($studiesIDs, Study::all()->pluck('id')->toArray());
                }
            }
            $user = User::with('studies', 'user_roles')->find(Auth::id());
        }

        if (hasPermission(\auth()->user(), 'studytools.index')) {
            $studiesIDs = array_merge($studiesIDs, Study::getStudiesAganistAdmin());
        }

        if (hasPermission(\auth()->user(), 'grading.index')) {
            $studiesIDs = array_merge($studiesIDs, Study::getStudiesAganistGrader());
        }

        if (hasPermission(\auth()->user(), 'adjudication.index')) {
            $studiesIDs = array_merge($studiesIDs, Study::getStudiesAganistAdjudicator());
        }

        if (hasPermission(\auth()->user(), 'qualitycontrol.index')) {
            $studiesIDs = array_merge($studiesIDs, Study::getStudiesAganistQC());
        }

        if (
            (hasPermission(\auth()->user(), 'systemtools.index')) ||
            (hasPermission(\auth()->user(), 'studytools.index'))
        ) {
            $studies = Study::whereIn('id', array_unique($studiesIDs));
        } else {
            $studies = Study::where('study_status', 'Live')->whereIn('id', array_unique($studiesIDs));
        }

        if ($request->study_code != '') {
            $studies = $studies->where('study_code', 'like', '%' . $request->study_code . '%');
        }
        if ($request->study_short_name != '') {
            $studies = $studies->where('study_short_name', 'like', '%' . $request->study_short_name . '%');
        }
        if ($request->study_status != '') {
            $studies = $studies->where('study_status', $request->study_status);
        }
        if ($request->study_sponsor != '') {
            $studies = $studies->where('study_sponsor', 'like', '%' . $request->study_sponsor . '%');
        }
        if ($request->id != '') {
            $studies = $studies->orderBy('id', $request->id);
        }
        $studies = $studies->get();

        return view('admin::studies.index', compact('sites', 'users', 'studies'));
    }



    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function studyStatus(Request $request)
    {
        $id = $request->study_ID;
        $deleteExistingData = $request->deleteExistingData;
        Study::where('id', $id)->update(['study_status' => $request->status]);
        if ($deleteExistingData == 'deleteExistingData') {
            Subject::where('study_id', $id)->withTrashed()->forceDelete();
            AdjudicationFormStatus::where('study_id', $id)->withTrashed()->forceDelete();
            Answer::where('study_id', $id)->withTrashed()->forceDelete();
            FinalAnswer::where('study_id', $id)->withTrashed()->forceDelete();
            FormStatus::where('study_id', $id)->withTrashed()->forceDelete();
            Query::where('study_id', $id)->withTrashed()->forceDelete();
            QueryNotification::where('study_id', $id)->withTrashed()->forceDelete();

            $phaseIds = StudyStructure::where('study_id', 'like', $id)->pluck('id')->toArray();
            $stepIds = PhaseSteps::whereIn('phase_id', $phaseIds)->pluck('step_id')->toArray();
            FormVersion::whereIn('step_id', $stepIds)->withTrashed()->forceDelete();
        }
        //return \response()->json($data);
        return redirect()->route('studies.index');
    }

    public function create()
    {
        if (Auth::user()->can('users.create')) {
            $studyAdminRoleId = Permission::getStudyAdminRole();
            //$systemRoleIds = Role::whereIn('id', $studyAdminRoleId)->pluck('id')->toArray();
            $userIdsWithStudyAdminRole = UserRole::whereIn('role_id', $studyAdminRoleId)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIdsWithStudyAdminRole)->get();

            $sites = Site::get();
            return view('admin::studies.create', compact('users', 'sites')); //->with(compact('permissions'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */

    public function store(Request $request)
    {
        $id    = (string)Str::uuid();
        Study::create([
            'id'    => $id,
            'study_short_name'  =>  $request->study_short_name,
            'study_title' => $request->study_title,
            'study_status'  => 'Development',
            'study_code' => $request->study_code,
            'protocol_number' => $request->protocol_number,
            'study_phase' => $request->study_phase,
            'trial_registry_id' => $request->trial_registry_id,
            'study_sponsor' => $request->study_sponsor,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description'   =>  $request->description,
            'user_id'       => $request->user()->id
        ]);
        $study = Study::find($id);

        if ($request->disease_cohort_name != null) {
            for ($i = 0; $i < count($request->disease_cohort_name); $i++) {
                $disease = [
                    'id' => (string)Str::uuid(),
                    'study_id' => $study->id,
                    'name' => $request->disease_cohort_name[$i],
                ];
                DiseaseCohort::insert($disease);
            }
        }

        $this->updateStudyAdmin($request, $study);


        /*************************** */
        /*************************** */
        // Preferences
        $this->updatePreferences($study);
        /*************************** */
        /*************************** */




        $oldStudy = [];
        // log event details
        $logEventDetails = eventDetails($study->id, 'Study', 'Add', $request->ip(), $oldStudy);

        return redirect()->route('studies.index')->with('message', 'Study created successfully');
    }

    private function updatePreferences($study)
    {
        $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'VISIT_ACTIVATION')->withOutGlobalScopes()->first();
        if (null === $preference) {
            Preference::create([
                'study_id' => $study->id,
                'preference_title'    => 'VISIT_ACTIVATION',
                'preference_value'    => 'Manual',
                'is_selectable'       => 'yes', //yes/no
                'preference_options'  => 'Transmission|Manual', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }

        $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'STUDY_EMAIL')->withOutGlobalScopes()->first();
        if (null === $preference) {
            Preference::create([
                'study_id' => $study->id,
                'preference_title'    => 'STUDY_EMAIL',
                'preference_value'    => '',
                'is_selectable'       => 'no', //yes/no
                'preference_options'  => '', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }

        $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'STUDY_CC_EMAILS')->withOutGlobalScopes()->first();
        if (null === $preference) {
            Preference::create([
                'study_id' => $study->id,
                'preference_title'    => 'STUDY_CC_EMAILS',
                'preference_value'    => '',
                'is_selectable'       => 'no', //yes/no
                'preference_options'  => '', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }

        $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'PER_PAGE_PAGINATION')->withOutGlobalScopes()->first();
        if (null === $preference) {
            Preference::create([
                'study_id' => $study->id,
                'preference_title'    => 'PER_PAGE_PAGINATION',
                'preference_value'    => '25',
                'is_selectable'       => 'yes', //yes/no
                'preference_options'  => '15|25|50|100|200|500|1000|5000', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Request $request, Study $study)
    {
        $subjects = [];
        $assignedUserIds = RoleStudyUser::where('study_id', 'LIKE', $study->id)->pluck('user_id')->toArray();
        if (
            isThisUserSuperAdmin(\auth()->user()) ||
            in_array(auth()->user()->id, $assignedUserIds)
        ) {
            session([
                'current_study' => $study->id,
                'study_short_name' => $study->study_short_name,
                'study_code' => $study->study_code
            ]);
            $id = $study->id;

            $studies  =  UserRole::select('user_roles.*', 'users.*', 'studies.*')
                ->join('users', 'users.id', '=', 'user_roles.user_id')
                ->join('studies', 'studies.id', '=', 'user_roles.study_id')
                ->where('users.id', '=', \auth()->user()->id)
                ->orderBy('study_short_name')->get();
            $currentStudy = Study::find($id);
            $study = Study::find($id);

            $subjects = Subject::select(['subjects.*', 'sites.site_name', 'sites.site_address', 'sites.site_city', 'sites.site_state', 'sites.site_code', 'sites.site_country', 'sites.site_phone']);
            $subjects = $subjects->where('subjects.study_id', '=', $id);
            if ($request->subject_id != '') {
                $subjects = $subjects->where('subjects.subject_id', $request->subject_id);
            }
            if ($request->site_id != '') {
                $subjects = $subjects->where('subjects.site_id', $request->site_id);
            }
            if ($request->enrollment_date != '') {
                $subjects = $subjects->where('subjects.enrollment_date', $request->enrollment_date);
            }
            if ($request->disease_cohort != '') {
                $subjects = $subjects->where('subjects.disease_cohort_id', $request->disease_cohort);
            }
            if ($request->study_eye != '') {
                $subjects = $subjects->where('subjects.study_eye', $request->study_eye);
            }

            $subjects = $subjects->join('sites', 'sites.id', '=', 'subjects.site_id');
            $subjects = $subjects->orderBy('subject_id', 'asc')->get();

            $site_study = StudySite::where('study_id', '=', $id)
                ->join('sites', 'sites.id', '=', 'site_study.site_id')
                ->select('sites.site_name', 'sites.id', 'sites.site_code')
                ->get();
            $diseaseCohort = DiseaseCohort::where('study_id', '=', $id)->get();
            return view('admin::studies.show', compact('study', 'studies', 'subjects', 'currentStudy', 'site_study', 'diseaseCohort'));
        } else {
            return redirect()->route('studies.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $studyAdminRoleId = Permission::getStudyAdminRole();
        $assignedUserIds = RoleStudyUser::where('study_id', 'LIKE', $id)->whereIn('role_id', Permission::getStudyAdminRole())->pluck('user_id')->toArray();

        $userIdsWithStudyAdminRole = UserRole::whereIn('role_id', $studyAdminRoleId)
            ->whereNotIn('user_id', $assignedUserIds)
            ->pluck('user_id')
            ->toArray();
        $users = User::whereIn('id', $userIdsWithStudyAdminRole)->get();

        $userNames = [];
        foreach ($users as $user) {
            $userNames[$user->id] = $user->name;
        }

        $study  = Study::with('diseaseCohort')
            ->find($id);

        return \response()->json(['study' => $study, 'users' => $userNames]);
    }

    public  function getAssignedAdminsToStudy(Request $request)
    {
        $studyId = $request->studyId;
        $roleId = Permission::getStudyAdminRole();
        $users = Study::getAssignedStudyAdminsName($studyId);
        echo $users;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update_studies(Request $request)
    {
    }
    public function update(Request $request)
    {
        // get old data for audit section
        $study = $oldStudy = Study::find($request->study_id);

        $study->study_short_name  =  $request->study_short_name;
        $study->study_title = $request->study_title;
        $study->study_status  = 'Development';
        $study->study_code = $request->study_code;
        $study->protocol_number = $request->protocol_number;
        $study->study_phase = $request->study_phase;
        $study->trial_registry_id = $request->trial_registry_id;
        $study->study_sponsor = $request->study_sponsor;
        $study->start_date = $request->start_date;
        $study->end_date = $request->end_date;
        $study->description   =  $request->description;
        $study->save();

        $studycohorts = DiseaseCohort::where('study_id', $request->study_id)->delete();
        // Update Disease cohort
        $disease = [];
        if ($request->disease_cohort_name != null) {
            for ($i = 0; $i < count($request->disease_cohort_name); $i++) {
                $disease = [
                    'id' => (string)Str::uuid(),
                    'study_id' => $request->study_id,
                    'name' => $request->disease_cohort_name[$i],
                ];
                DiseaseCohort::insert($disease);
            }
        }

        $this->updateStudyAdmin($request, $study);

        /*************************** */
        /*************************** */
        // Preferences
        $this->updatePreferences($oldStudy);
        /*************************** */
        /*************************** */


        // log event details
        $logEventDetails = eventDetails($study->id, 'Study', 'Update', $request->ip(), $oldStudy);

        return redirect()->route('studies.index')->with('message', 'Study updated successfully');
    }

    private function updateStudyAdmin($request, $study)
    {
        if ($request->users != null) {
            $studyAdminRoleId = Permission::getStudyAdminRole();
            RoleStudyUser::where('study_id', 'like', $study->id)->whereIn('role_id', $studyAdminRoleId)->delete();
            foreach ($request->users as $user_id) {
                RoleStudyUser::create([
                    'id' => (string)Str::uuid(),
                    'study_id'   => $study->id,
                    'user_id'   => $user_id,
                    'role_id'   => $studyAdminRoleId[0]
                ]);
            }
        }
    }


    /**
     * get clone of the study
     */
    public function cloneStudy(Request $request)
    {
        $isReplicating = false;

        $study_id = $request->study_ID;
        $originalStudy = Study::find($study_id);
        $newStudyId = (string)Str::uuid();
        Study::create([
            'id'    => $newStudyId,
            'parent_id' => $study_id,
            'replicating_or_cloning' => 'cloning',
            'study_short_name'  =>  $request->study_short_name,
            'study_title' => $request->study_title,
            'study_status'  => 'Development',
            'study_code' => $request->study_code,
            'protocol_number' => $request->protocol_number,
            'study_phase' => $request->study_phase,
            'trial_registry_id' => $request->trial_registry_id,
            'study_sponsor' => $request->study_sponsor,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description'   =>  $request->description,
            'user_id'       => auth()->user()->id
        ]);
        $clonedStudy = Study::find($newStudyId);
        if ($originalStudy->diseaseCohort) {
            foreach ($originalStudy->diseaseCohort as $disease_cohort) {
                $id = (string)Str::uuid();
                DiseaseCohort::create([
                    'id'    => $id,
                    'study_id'  => $clonedStudy->id,
                    'name'      => $disease_cohort->name
                ]);
            }
        }
        if ($originalStudy->preferences) {
            foreach ($originalStudy->preferences as $preference) {
                $newPreferenceId = (string)Str::uuid();
                //`id`, `study_id`, `preference_title`, `preference_value`, `is_selectable`, `preference_options`
                Preference::create([
                    'id'    => $newPreferenceId,
                    'study_id'  => $clonedStudy->id,
                    'preference_title'      => $preference->preference_title,
                    'preference_value'      => $preference->preference_value,
                    'is_selectable'      => $preference->is_selectable,
                    'preference_options'      => $preference->preference_options,
                ]);
            }
        }
        if ($request->studyUsers  == 'on') {
            $study_users = UserRole::where('study_id', '=', $study_id)->get();
            foreach ($study_users  as $user) {
                UserRole::createUserRole($user->user_id, $user->role_id, $clonedStudy->id);
            }
        }
        if ($request->studySites == 'on') {
            $study_sites = StudySite::where('study_id', '=', $study_id)->get();
            foreach ($study_sites as $site) {
                $id = (string)Str::uuid();
                $cloned_site = StudySite::create([
                    'id' => $id,
                    'study_id' => $clonedStudy->id,
                    'site_id' => $site->site_id,
                    'primaryInvestigator_id' => $site->primaryInvestigator_id,
                    'study_site_id' => $site->study_site_id,
                ]);
            }
            $site_coordinators = SiteStudyCoordinator::where('site_study_id', '=', $site->id)->get();
            foreach ($site_coordinators as $site_coordinator) {
                $id = (string)Str::uuid();
                $coordinator =   SiteStudyCoordinator::create([
                    'id'    => $id,
                    'site_study_id'     => NULL,
                    'coordinator_id'    => $site_coordinator->coordinator_id
                ]);
                $cloned_study_site = StudySite::where('study_id', '=', $clonedStudy->id)->first();
                $coordinator->site_study_id = $cloned_study_site->id;
                $coordinator->save();
            }
        }
        if ($request->studySubjects == 'on') {
            $study_subjects = Subject::where('study_id', $study_id)->get();
            foreach ($study_subjects as $subject) {
                $newSubjectId = (string)Str::uuid();
                Subject::create([
                    'id' => $newSubjectId,
                    'old_id' => $subject->id,
                    'study_id'  => $clonedStudy->id,
                    'subject_id'    => $subject->subject_id,
                    'enrollment_date'   => $subject->enrollment_date,
                    'study_eye' => $subject->study_eye,
                    'site_id' => $subject->site_id,
                    'disease_cohort_id' => $subject->disease_cohort_id,
                ]);
                $clonedSubject = Subject::find($newSubjectId);
            }
            if ($request->phasesSteps == 'on') {
                $study_phases = StudyStructure::where('study_id', '=', $study_id)->get();
                foreach ($study_phases as $phase) {
                    $id = (string)Str::uuid();
                    if ($phase->replicating_or_cloning == 'not-any') {
                        StudyStructure::create([
                            'id' => $id,
                            'study_id' => $clonedStudy->id,
                            'name' => $phase->name,
                            'position' => $phase->position,
                            'duration' => $phase->duration,
                            'is_repeatable' => $phase->is_repeatable,
                            'parent_id' => $phase->parent_id,
                            'replicating_or_cloning' => 'cloning',
                            'count' => $phase->count
                        ]);
                    }
                    $replica_phase_id = StudyStructure::select('id')->latest()->first();
                    if ($phase->replicating_or_cloning == 'replicating') {
                        $replica_phase_id = StudyStructure::select('id')->latest()->first();
                        StudyStructure::create([
                            'id' => $id,
                            'study_id' => $clonedStudy->id,
                            'name' => $phase->name,
                            'position' => $phase->position,
                            'duration' => $phase->duration,
                            'is_repeatable' => $phase->is_repeatable,
                            'parent_id' => $replica_phase_id->id,
                            'replicating_or_cloning' => 'replicating',
                            'count' => $phase->count
                        ]);
                    }
                    $subjectPhases = SubjectsPhases::where('phase_id', '=', $phase->id)->get();
                    foreach ($subjectPhases as $subjectPhase) {
                        SubjectsPhases::create([
                            'id'    => (string)Str::uuid(),
                            'subject_id' => $clonedSubject->id,
                            'phase_id'  => $replica_phase_id->id,
                            'form_type_id'  => $subjectPhase->form_type_id,
                            'modility_id'  => $subjectPhase->modility_id,
                            'visit_date'    => $subjectPhase->visit_date,
                            'Transmission_Number'   => $subject->Transmission_Number,
                            'is_out_of_window'  => $subjectPhase->is_out_of_window,
                        ]);
                    }
                    $newQuestionIdsArray = [];
                    foreach ($phase->steps as $step) {

                        $newStepId = $this->addReplicatedStep($step, $replica_phase_id->id, $isReplicating);

                        /******************************* */
                        /***  Replicate Step Sections ** */
                        /******************************* */
                        foreach ($step->sections as $section) {

                            $newSectionId = $this->addReplicatedSection($section, $newStepId, $isReplicating);

                            /******************************* */
                            /* Replicate Section Questions * */
                            /******************************* */
                            foreach ($section->questions as $question) {

                                $newQuestionIdsArray[$question->id] = $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId, $isReplicating);

                                /******************************* */
                                /* Replicate Question Form Field */
                                /******************************* */

                                $this->addReplicatedFormField($question, $newQuestionId, $isReplicating);

                                /******************************* */
                                /* Replicate Question Data Validation */
                                /******************************* */

                                $this->addQuestionValidationToReplicatedQuestion($question->id, $newQuestionId, $isReplicating);

                                /******************************* */
                                /*Replicate Question Adjudication*/
                                /******************************* */

                                $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId, $isReplicating);
                            }
                        }
                    }

                    foreach ($newQuestionIdsArray as $questionId => $newQuestionId) {
                        $question = Question::find($questionId);

                        /******************************* */
                        /* Replicate Question Dependency */
                        /******************************* */

                        $this->addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating);

                        /******************************* */
                        /* Replicate Question Skip Logic */
                        /******************************* */

                        $this->updateSkipLogicsToReplicatedVisits($question->id, $isReplicating);

                        /******************************* */
                        /* Replicate Question Option Skip Logic */
                        /******************************* */

                        $this->updateOptionSkipLogicsToReplicatedVisits($question->id, $isReplicating);
                    }
                    /******************************* */
                    /*** Replicate Cohort Skip Logic */
                    /******************************* */
                    foreach ($phase->cohortSkipLogics as $cohortSkipLogic) {
                        $this->addPhaseSkipLogicToReplicatedPhase($cohortSkipLogic, $replica_phase_id->id, $isReplicating);
                    }

                    foreach ($phase->questionOptionsCohortSkipLogics as $cohortSkipLogic) {
                        $this->addPhaseOptionsSkipLogicToReplicatedPhase($cohortSkipLogic, $replica_phase_id->id, $isReplicating);
                    }
                }
                if ($request->answers == 'on') {
                    $answers = Answer::where('study_id', '=', $originalStudy->id)->get();
                    foreach ($answers as $answer) {
                        $cloned_answer = Answer::create([
                            'id' => (string)Str::uuid(),
                            'form_filled_by_user_id' => $answer->form_filled_by_user_id,
                            'grader_id' => $answer->grader_id,
                            'adjudicator_id' => $answer->adjudicator_id,
                            'subject_id' => $clonedSubject->id,
                            'study_id' => $clonedStudy->id,
                            'study_structures_id' => $replica_phase_id->id,
                            'phase_steps_id' => $newStepId,
                            'section_id' => $newSectionId,
                            'question_id' => $newQuestionId,
                            'field_id' => $answer->field_id,
                            'answer' => $answer->answer,
                            'is_answer_accepted' => $answer->is_answer_accepted,
                        ]);
                    }
                    $final_answers = FinalAnswer::where('study_id', '=', $originalStudy->id)->get();
                    foreach ($final_answers as $final_answer) {
                        $cloned_answer_final = FinalAnswer::create([
                            'id' => (string)Str::uuid(),
                            'study_id' => $clonedStudy->id,
                            'form_filled_by_user_id' => $final_answer->form_filled_by_user_id,
                            'grader_id' => $final_answer->grader_id,
                            'adjudicator_id' => $final_answer->adjudicator_id,
                            'subject_id' => $clonedSubject->id,
                            'study_structures_id' => $replica_phase_id->id,
                            'phase_steps_id' => $newStepId,
                            'section_id' => $newSectionId,
                            'question_id' => $newQuestionId,
                            'field_id' => $final_answer->field_id,
                            'answer' => $final_answer->answer,
                        ]);
                    }
                    /*                    $getclonedAnswers = Answer::where('study_id','=',$clonedStudy->id)->get();
                    foreach ($getclonedAnswers as $getclonedAnswer){
                        $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                        foreach ($clonedSubjects as $clonedSubject){
                            $getclonedAnswer->subject_id = $clonedSubject->id;
                            $getclonedAnswer->save();
                        }
                    }
                    $getclonedFinalAnswers = Answer::where('study_id','=',$clonedStudy->id)->get();
                    foreach ($getclonedFinalAnswers as $getclonedFinalAnswer){
                        $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                        foreach ($clonedSubjects as $clonedSubject){
                            $getclonedFinalAnswer->subject_id = $clonedSubject->id;
                            $getclonedFinalAnswer->save();
                        }
                    }*/
                }
                $annotations = Annotation::where('study_id', '=', $originalStudy)->get();
                foreach ($annotations as $annotation) {
                    Annotation::create([
                        'id'    => (string)Str::uuid(),
                        'study_id' => $clonedStudy->id,
                        'label' => $annotation->label,
                        'deleted_at' => $annotation->deleted_at,
                    ]);
                    $replica_annotation = Annotation::select('id')->latest()->first();
                    $annotation_descriptions = AnnotationDescription::where('annotation_id', '=', $annotation->id)->get();
                    foreach ($annotation_descriptions as $annotation_description) {
                        AnnotationDescription::create([
                            'id'    => (string)Str::uuid(),
                            'annotation_id' => $replica_annotation->id,
                            'value' => $annotation_description->value,
                            'question_id'   => $newQuestionId,
                            'description'   => $annotation_description->description,
                            'deleted_at'    => NULL
                        ]);
                    }
                }
            }
            if ($request->studyData == 'on') {
                $adjudicationformstatuses = AdjudicationFormStatus::where('study_id', '=', $originalStudy->id)->get();
                foreach ($adjudicationformstatuses as $adjudicationformstatus) {
                    AdjudicationFormStatus::create([
                        'id' => (string)Str::uuid(),
                        'form_adjudicated_by_id' => $adjudicationformstatus->form_adjudicated_by_id,
                        'subject_id' => $clonedSubject->id,
                        'study_id' => $clonedStudy->id,
                        'study_structures_id' => $replica_phase_id->id,
                        'phase_steps_id' => $newStepId,
                        'section_id' => $newSectionId,
                        'modility_id' => $adjudicationformstatus->modility_id,
                        'adjudication_status' => $adjudicationformstatus->adjudication_status
                    ]);

                    /*$getclonedAdjudications = AdjudicationFormStatus::where('study_id','=',$clonedStudy->id)->get();
                    foreach ($getclonedAdjudications as $getclonedAdjudication){
                        $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                        foreach ($clonedSubjects as $clonedSubject){
                            $getclonedAdjudication->subject_id = $clonedSubject->id;
                            $getclonedAdjudication->save();
                        }
                    }
                    */

                    $latest_adjudication = AdjudicationFormStatus::select('id')->latest()->first();
                    $adjudicationformsrevisiontatuses = AdjudicationFormRevisionHistory::where('adjudication_form_submit_status_id', '=', $adjudicationformstatus->id)->get();
                    foreach ($adjudicationformsrevisiontatuses as $adjudicationformsrevisiontatus) {
                        AdjudicationFormRevisionHistory::create([
                            'id' => (string)Str::uuid(),
                            'adjudication_form_submit_status_id' => $latest_adjudication->id,
                            'adjudication_form_edit_reason_text' => $adjudicationformsrevisiontatus->adjudication_form_edit_reason_text
                        ]);
                    }
                }
                $formsubmitstatuses = FormStatus::where('study_id', '=', $originalStudy->id)->get();
                foreach ($formsubmitstatuses as $formsubmitstatus) {
                    $id = (string)Str::uuid();
                    FormStatus::create([
                        'id' => $id,
                        'form_filled_by_user_id' => $formsubmitstatus->form_filled_by_user_id,
                        'form_filled_by_user_role_id' => $formsubmitstatus->form_filled_by_user_role_id,
                        'subject_id' => $clonedSubject->id,
                        'study_id' => $clonedStudy->id,
                        'study_structures_id' => $replica_phase_id->id,
                        'phase_steps_id' => $newStepId,
                        'section_id' => $newSectionId,
                        'form_type_id' => $formsubmitstatus->form_type_id,
                        'modility_id' => $formsubmitstatus->modility_id,
                        'form_status' => $formsubmitstatus->form_status,
                    ]);



                    /*$getclonedFormstatuses = FormStatus::where('study_id','=',$clonedStudy->id)->get();
                    foreach ($getclonedFormstatuses as $getclonedFormstatus){
                        $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                        foreach ($clonedSubjects as $clonedSubject){
                            $getclonedFormstatus->subject_id = $clonedSubject->id;
                            $getclonedFormstatus->save();
                        }
                    }*/


                    $replica_form_status_id = Study::select('id')->latest()->first();
                    $formrevisions = FormRevisionHistory::where('form_submit_status_id', '=', $formsubmitstatus)->get();
                    foreach ($formrevisions as $formrevision) {
                        FormRevisionHistory::create([
                            'id'    => (string)Str::uuid(),
                            'form_submit_status_id' => $replica_form_status_id->id,
                            'edit_reason_text'  => $formrevision->edit_reason_text
                        ]);
                    }
                }
            }
        }

        if ($request->transmissions ==  'on') {
            /* $transmissions = CrushFtpTransmission::all();
             dd($transmissions);*/
            $transmissions = CrushFtpTransmission::where('StudyI_ID', '=', $originalStudy->study_code)->get();
            foreach ($transmissions as $transmission) {
                $id = (string)Str::uuid();
                CrushFtpTransmission::create([
                    'id' => $id,
                    'data'  => $transmission->data,
                    'Transmission_Number'  => $transmission->Transmission_Number,
                    'Study_Name'  => $transmission->Study_Name . 'Cloned',
                    'StudyI_ID'  => $transmission->StudyI_ID,
                    'sponsor'  => $transmission->sponsor,
                    'Study_central_email'  => $transmission->Study_central_email,
                    'Salute'  => $transmission->Salute,
                    'Submitter_First_Name'  => $transmission->Submitter_First_Name,
                    'Submitter_Last_Name'  => $transmission->Submitter_Last_Name,
                    'Submitter_email'  => $transmission->Submitter_email,
                    'Submitter_phone'  => $transmission->Submitter_phone,
                    'Submitter_Role'  => $transmission->Submitter_Role,
                    'Site_Initials'  => $transmission->Site_Initials,
                    'Site_Name'  => $transmission->Site_Name,
                    'Site_ID'  => $transmission->Site_ID,
                    'sit_id'  => $transmission->sit_id,
                    'PI_Name'  => $transmission->PI_Name,
                    'PI_FirstName'  => $transmission->PI_FirstName,
                    'PI_LastName'  => $transmission->PI_LastName,
                    'PI_email'  => $transmission->PI_email,
                    'Site_st_address'  => $transmission->Site_st_address,
                    'Site_city'  => $transmission->Site_city,
                    'Site_state'  => $transmission->Site_state,
                    'Site_Zip'  => $transmission->Site_Zip,
                    'Site_country'  => $transmission->Site_country,
                    'Subject_ID'  => $transmission->Subject_ID,
                    'subj_id'  => $transmission->subj_id,
                    'new_subject'  => $transmission->new_subject,
                    'StudyEye'  => $transmission->StudyEye,
                    'visit_name'  => $transmission->visit_name,
                    'phase_id'  => $transmission->phase_id,
                    'visit_date'  => $transmission->visit_date,
                    'ImageModality'  => $transmission->ImageModality,
                    'modility_id'  => $transmission->modility_id,
                    'device_model'  => $transmission->device_model,
                    'device_oirrcID'  => $transmission->device_oirrcID,
                    'Compliance'  => $transmission->Compliance,
                    'Compliance_comments'  => $transmission->Compliance_comments,
                    'Submitted_By'  => $transmission->Submitted_By,
                    'photographer_full_name'  => $transmission->photographer_full_name,
                    'photographer_email'  => $transmission->photographer_email,
                    'photographer_ID'  => $transmission->photographer_ID,
                    'Number_files'  => $transmission->Number_files,
                    'transmitted_file_name'  => $transmission->transmitted_file_name,
                    'transmitted_file_size'  => $transmission->transmitted_file_size,
                    'archive_physical_location'  => $transmission->archive_physical_location,
                    'received_month'  => $transmission->received_month,
                    'received_day'  => $transmission->received_day,
                    'received_year'  => $transmission->received_year,
                    'received_hours'  => $transmission->received_hours,
                    'received_minutes'  => $transmission->received_minutes,
                    'received_seconds'  => $transmission->received_seconds,
                    'received-mesc'  => $transmission->received,
                    'Study_QCO1'  => $transmission->Study_QCO1,
                    'StudyQCO2'  => $transmission->StudyQCO2,
                    'Study_cc1'  => $transmission->Study_cc1,
                    'Study_cc2'  => $transmission->Study_cc2,
                    'QC_folder'  => $transmission->QC_folder,
                    'Graders_folder'  => $transmission->Graders_folder,
                    'QClink'  => $transmission->QClink,
                    'Glink'  => $transmission->Glink,
                    'created_by'  => $transmission->created_by,
                    'updated_by'  => $transmission->Study_QCO1,
                    'status'  => $transmission->status,
                    'is_read'  => $transmission->is_read,
                    'dcm_availability'  => $transmission->dcm_availability,
                    'received_file_format'  => $transmission->received_file_format,
                    'qc_officerId'  => $transmission->qc_officerId,
                    'qc_officerName'  => $transmission->qc_officerName,
                    'cms_visit_reference'  => $transmission->cms_visit_reference,
                    'comment'  => $transmission->comment,
                    'created_date'  => $transmission->created_date,
                    'updated_date'  => $transmission->updated_date,
                ]);
            }
        }
        if ($request->auditTrail == 'on') {
            $auditTrails = TrailLog::where('study_id', '=', $originalStudy)->get();
            foreach ($auditTrails as $auditTrail) {
                $id = (string)Str::uuid();
                TrailLog::create([
                    'id'    => $id,
                    'user_id'   => $auditTrail->user_id,
                    'user_name' => $auditTrail->user_name,
                    'role_id'   => $auditTrail->role_id,
                    'event_id'  => $auditTrail->event_id,
                    'event_section' => $auditTrail->event_section,
                    'event_type'    => $auditTrail->event_type,
                    'event_message' => $auditTrail->event_message,
                    'ip_address'    => $auditTrail->ip_address,
                    'study_id'      => $clonedStudy->id,
                    'event_url'     => $request->event_url,
                    'event_details' => $auditTrail->event_details,
                    'event_old_details' => $auditTrail->event_old_details,
                ]);
            }
        }
        if ($request->studyPreferences == 'on') {
            $studyPrefrences = Preference::where('study_id', '=', $originalStudy)->get();
            foreach ($studyPrefrences as $studyPrefrence) {
                Preference::create([
                    'id'        => (string)Str::uuid(),
                    'preference_title'      => $studyPrefrence->preference_title,
                    'preference_value'      => $studyPrefrence->preference_value,
                    'is_selectable'      => $studyPrefrence->is_selectable,
                    'preference_options'      => $studyPrefrence->preference_options,
                    'study_id'      => $studyPrefrence->$clonedStudy->id,
                ]);
            }
        }
        $oldStudy = [];
        // log event details
        $logEventDetails = eventDetails($clonedStudy->id, 'Study', 'Add', $request->ip(), $oldStudy);

        $studies = Study::all();
        // return \response()->json($studies);

        return redirect()->route('studies.index')->with('message', 'Study cloned successfully');
    }

    /**
     * get export of the study
     */
    public function exportStudy(Request $request)
    {
        $isReplicating = false;
        $study_id = $request->study_ID;
        $originalStudy = Study::find($study_id);
        $disease_cohorts = DiseaseCohort::where('study_id', '=', $study_id)->get();

        return \response()->xml(['study' => $originalStudy->toArray($disease_cohorts)]);

        $id = (string)Str::uuid();
        $replica = Study::create([
            'id'    => $id,
            'parent_id' => $study_id,
            'replicating_or_cloning' => 'cloning',
            'study_short_name'  =>  $request->study_short_name,
            'study_title' => $request->study_title,
            'study_status'  => 'Development',
            'study_code' => $request->study_code,
            'protocol_number' => $request->protocol_number,
            'study_phase' => $request->study_phase,
            'trial_registry_id' => $request->trial_registry_id,
            'study_sponsor' => $request->study_sponsor,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description'   =>  $request->description,
            'user_id'       => auth()->user()->id
        ]);
        $clonedStudy = Study::select('id')->latest()->first();
        if ($originalStudy->diseaseCohort) {
            foreach ($originalStudy->diseaseCohort as $disease_cohort) {
                $id = (string)Str::uuid();
                $diseaseCohort = DiseaseCohort::create([
                    'id'    => $id,
                    'study_id'  => $clonedStudy->id,
                    'name'      => $disease_cohort->name
                ]);
            }
        }
        if ($request->studyUsers  == 'on') {
            $study_users = UserRole::where('study_id', '=', $study_id)->get();
            foreach ($study_users  as $user) {
                UserRole::createUserRole($user->user_id, $user->role_id, $clonedStudy->id);
            }
        }
        if ($request->studySites == 'on') {
            $study_sites = StudySite::where('study_id', '=', $study_id)->get();
            foreach ($study_sites as $site) {
                $id = (string)Str::uuid();
                $cloned_site = StudySite::create([
                    'id' => $id,
                    'study_id' => $clonedStudy->id,
                    'site_id' => $site->site_id,
                    'primaryInvestigator_id' => $site->primaryInvestigator_id,
                    'study_site_id' => $site->study_site_id,
                ]);
            }
            $site_coordinators = SiteStudyCoordinator::where('site_study_id', '=', $site->id)->get();
            foreach ($site_coordinators as $site_coordinator) {
                $id = (string)Str::uuid();
                $coordinator =   SiteStudyCoordinator::create([
                    'id'    => $id,
                    'site_study_id'     => NULL,
                    'coordinator_id'    => $site_coordinator->coordinator_id
                ]);
                $cloned_study_site = StudySite::where('study_id', '=', $clonedStudy->id)->first();
                $coordinator->site_study_id = $cloned_study_site->id;
                $coordinator->save();
            }
        }
        if ($request->studySubjects == 'on') {
            $study_subjects = Subject::where('study_id', $study_id)->get();
            foreach ($study_subjects as $subject) {
                $id = (string)Str::uuid();
                Subject::create([
                    'id' => $id,
                    'old_id' => $subject->id,
                    'study_id'  => $clonedStudy->id,
                    'subject_id'    => $subject->subject_id,
                    'enrollment_date'   => $subject->enrollment_date,
                    'study_eye' => $subject->study_eye,
                    'site_id' => $subject->site_id,
                    'disease_cohort_id' => $subject->disease_cohort_id,
                ]);
                $clonedSubject = Subject::select('id')->latest()->first();
                if ($request->phasesSteps == 'on') {
                    $study_phases = StudyStructure::where('study_id', '=', $study_id)->get();
                    foreach ($study_phases as $phase) {
                        $id = (string)Str::uuid();
                        if ($phase->replicating_or_cloning == 'not-any') {
                            StudyStructure::create([
                                'id' => $id,
                                'study_id' => $clonedStudy->id,
                                'name' => $phase->name,
                                'position' => $phase->position,
                                'duration' => $phase->duration,
                                'is_repeatable' => $phase->is_repeatable,
                                'parent_id' => $phase->parent_id,
                                'replicating_or_cloning' => 'cloning',
                                'count' => $phase->count
                            ]);
                        }
                        $replica_phase_id = StudyStructure::select('id')->latest()->first();
                        if ($phase->replicating_or_cloning == 'replicating') {
                            $replica_phase_id = StudyStructure::select('id')->latest()->first();
                            StudyStructure::create([
                                'id' => $id,
                                'study_id' => $clonedStudy->id,
                                'name' => $phase->name,
                                'position' => $phase->position,
                                'duration' => $phase->duration,
                                'is_repeatable' => $phase->is_repeatable,
                                'parent_id' => $replica_phase_id->id,
                                'replicating_or_cloning' => 'replicating',
                                'count' => $phase->count
                            ]);
                        }
                        $subjectPhases = SubjectsPhases::where('phase_id', '=', $phase->id)->get();
                        foreach ($subjectPhases as $subjectPhase) {
                            SubjectsPhases::create([
                                'id'    => (string)Str::uuid(),
                                'subject_id' => $subjectPhase->subject_id,
                                'phase_id'  => $replica_phase_id->id,
                                'visit_date'    => $subjectPhase->visit_date,
                                'is_out_of_window'  => $subjectPhase->is_out_of_window,
                                'modility_id'  => $subjectPhase->modility_id,
                                'form_type_id'  => $subjectPhase->form_type_id,
                            ]);
                        }
                        $newQuestionIdsArray = [];
                        foreach ($phase->steps as $step) {
                            $newStepId = $this->addReplicatedStep($step, $replica_phase_id->id, $isReplicating);

                            /******************************* */
                            /***  Replicate Step Sections ** */
                            /******************************* */
                            foreach ($step->sections as $section) {

                                $newSectionId = $this->addReplicatedSection($section, $newStepId, $isReplicating);

                                /******************************* */
                                /* Replicate Section Questions * */
                                /******************************* */
                                foreach ($section->questions as $question) {

                                    $newQuestionIdsArray[$question->id] = $newQuestionId = $this->addReplicatedQuestion($question, $newSectionId, $isReplicating);

                                    /******************************* */
                                    /* Replicate Question Form Field */
                                    /******************************* */

                                    $this->addReplicatedFormField($question, $newQuestionId, $isReplicating);

                                    /******************************* */
                                    /* Replicate Question Data Validation */
                                    /******************************* */

                                    $this->addQuestionValidationToReplicatedQuestion($question->id, $newQuestionId, $isReplicating);

                                    /******************************* */
                                    /*Replicate Question Adjudication*/
                                    /******************************* */

                                    $this->addReplicatedQuestionAdjudicationStatus($question, $newQuestionId, $isReplicating);
                                }
                            }
                        }
                        foreach ($newQuestionIdsArray as $questionId => $newQuestionId) {
                            $question = Question::find($questionId);

                            /******************************* */
                            /* Replicate Question Dependency */
                            /******************************* */

                            $this->addReplicatedQuestionDependency($question, $newQuestionId, $isReplicating);

                            /******************************* */
                            /* Replicate Question Skip Logic */
                            /******************************* */

                            $this->updateSkipLogicsToReplicatedVisits($question->id, $isReplicating);

                            /******************************* */
                            /* Replicate Question Option Skip Logic */
                            /******************************* */

                            $this->updateOptionSkipLogicsToReplicatedVisits($question->id, $isReplicating);
                        }
                        /******************************* */
                        /*** Replicate Cohort Skip Logic */
                        /******************************* */
                        foreach ($phase->cohortSkipLogics as $cohortSkipLogic) {
                            $this->addPhaseSkipLogicToReplicatedPhase($cohortSkipLogic, $replica_phase_id->id, $isReplicating);
                        }

                        foreach ($phase->questionOptionsCohortSkipLogics as $cohortSkipLogic) {
                            $this->addPhaseOptionsSkipLogicToReplicatedPhase($cohortSkipLogic, $replica_phase_id->id, $isReplicating);
                        }
                    }
                    if ($request->answers == 'on') {

                        $answers = Answer::where('study_id', '=', $originalStudy->id)->get();
                        foreach ($answers as $answer) {
                            $cloned_answer = Answer::create([
                                'id' => (string)Str::uuid(),
                                'form_filled_by_user_id' => $answer->form_filled_by_user_id,
                                'grader_id' => $answer->grader_id,
                                'adjudicator_id' => $answer->adjudicator_id,
                                'subject_id' => $answer->subject_id,
                                'study_id' => $clonedStudy->id,
                                'study_structures_id' => $replica_phase_id->id,
                                'phase_steps_id' => $newStepId,
                                'section_id' => $newSectionId,
                                'question_id' => $newQuestionId,
                                'field_id' => $answer->field_id,
                                'answer' => $answer->answer,
                                'is_answer_accepted' => $answer->is_answer_accepted,
                            ]);
                        }

                        $final_answers = FinalAnswer::where('study_id', '=', $originalStudy->id)->get();
                        foreach ($final_answers as $final_answer) {
                            $cloned_answer_final = FinalAnswer::create([
                                'id' => (string)Str::uuid(),
                                'study_id' => $clonedStudy->id,
                                'form_filled_by_user_id' => $final_answer->form_filled_by_user_id,
                                'grader_id' => $final_answer->grader_id,
                                'adjudicator_id' => $final_answer->adjudicator_id,
                                'subject_id' => $final_answer->subject_id,
                                'study_structures_id' => $replica_phase_id->id,
                                'phase_steps_id' => $newStepId,
                                'section_id' => $newSectionId,
                                'question_id' => $newQuestionId,
                                'field_id' => $final_answer->field_id,
                                'answer' => $final_answer->answer,
                            ]);
                        }

                        $getclonedAnswers = Answer::where('study_id', '=', $clonedStudy->id)->get();


                        /*  foreach ($getclonedAnswers as $getclonedAnswer){
                            $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                            foreach ($clonedSubjects as $clonedSubject){
                                $getclonedAnswer->subject_id = $clonedSubject->id;
                                $getclonedAnswer->save();
                            }
                        }
                        $getclonedFinalAnswers = Answer::where('study_id','=',$clonedStudy->id)->get();
                        foreach ($getclonedFinalAnswers as $getclonedFinalAnswer){
                            $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                            foreach ($clonedSubjects as $clonedSubject){
                                $getclonedFinalAnswer->subject_id = $clonedSubject->id;
                                $getclonedFinalAnswer->save();
                            }
                        }
                        $getclonedSubjectPhases = SubjectsPhases::where('phase_id','=',$replica_phase_id->id)->get();
                        foreach ($getclonedSubjectPhases as $getclonedSubjectPhase){
                            $clonedSubjects = Subject::where('study_id','=',$clonedStudy->id)->get();
                            foreach ($clonedSubjects as $clonedSubject){
                                $getclonedSubjectPhase->subject_id = $clonedSubject->id;
                                $getclonedSubjectPhase->save();
                            }
                        }*/


                        $annotations = Annotation::where('study_id', '=', $originalStudy)->get();
                        foreach ($annotations as $annotation) {
                            Annotation::create([
                                'id'    => (string)Str::uuid(),
                                'study_id' => $clonedStudy->id,
                                'label' => $annotation->label,
                                'deleted_at' => $annotation->deleted_at,
                            ]);
                            $replica_annotation = Annotation::select('id')->latest()->first();
                            $annotation_descriptions = AnnotationDescription::where('annotation_id', '=', $annotation->id)->get();
                            foreach ($annotation_descriptions as $annotation_description) {
                                AnnotationDescription::create([
                                    'id'    => (string)Str::uuid(),
                                    'annotation_id' => $replica_annotation->id,
                                    'value' => $annotation_description->value,
                                    'question_id'   => $newQuestionId,
                                    'description'   => $annotation_description->description,
                                    'deleted_at'    => NULL
                                ]);
                            }
                        }
                        if ($request->studyData == 'on') {
                            $adjudicationformstatuses = AdjudicationFormStatus::where('study_structures_id', '=', $phase->id)->get();
                            foreach ($adjudicationformstatuses as $adjudicationformstatus) {
                                AdjudicationFormStatus::create([
                                    'id' => (string)Str::uuid(),
                                    'form_adjudicated_by_id' => $adjudicationformstatus->form_adjudicated_by_id,
                                    'subject_id' => $adjudicationformstatus->subject_id,
                                    'study_id' => $clonedStudy->id,
                                    'study_structures_id' => $replica_phase_id->id,
                                    'phase_steps_id' => $newStepId,
                                    'section_id' => $newSectionId,
                                    'modility_id' => $adjudicationformstatus->modility_id,
                                    'adjudication_status' => $adjudicationformstatus->adjudication_status
                                ]);
                                $getclonedAdjudications = AdjudicationFormStatus::where('study_id', '=', $clonedStudy->id)->get();
                                foreach ($getclonedAdjudications as $getclonedAdjudication) {
                                    $clonedSubjects = Subject::where('study_id', '=', $clonedStudy->id)->get();
                                    foreach ($clonedSubjects as $clonedSubject) {
                                        $getclonedAdjudication->subject_id = $clonedSubject->id;
                                        $getclonedAdjudication->save();
                                    }
                                }
                                $latest_adjudication = AdjudicationFormStatus::select('id')->latest()->first();
                                $adjudicationformsrevisiontatuses = AdjudicationFormRevisionHistory::where('adjudication_form_submit_status_id', '=', $adjudicationformstatus->id)->get();
                                foreach ($adjudicationformsrevisiontatuses as $adjudicationformsrevisiontatus) {
                                    AdjudicationFormRevisionHistory::create([
                                        'id' => (string)Str::uuid(),
                                        'adjudication_form_submit_status_id' => $latest_adjudication->id,
                                        'adjudication_form_edit_reason_text' => $adjudicationformsrevisiontatus->adjudication_form_edit_reason_text
                                    ]);
                                }
                            }
                            $formsubmitstatuses = FormStatus::where('study_id', '=', $originalStudy)->get();
                            foreach ($formsubmitstatuses as $formsubmitstatus) {
                                FormStatus::create([
                                    'id' => (string)Str::uuid(),
                                    'form_filled_by_user_id' => $formsubmitstatus->form_filled_by_user_id,
                                    'form_filled_by_user_role_id' => $formsubmitstatus->form_filled_by_user_role_id,
                                    'subject_id' => $formsubmitstatus->subject_id,
                                    'study_id' => $clonedStudy->id,
                                    'study_structures_id' => $replica_phase_id->id,
                                    'phase_steps_id' => $newStepId,
                                    'section_id' => $newSectionId,
                                    'form_type_id' => $formsubmitstatus->form_type_id,
                                    'modility_id' => $formsubmitstatus->modility_id,
                                    'form_status' => $formsubmitstatus->form_status,
                                ]);
                                $getclonedFormstatuses = FormStatus::where('study_id', '=', $clonedStudy->id)->get();
                                foreach ($getclonedFormstatuses as $getclonedFormstatus) {
                                    $clonedSubjects = Subject::where('study_id', '=', $clonedStudy->id)->get();
                                    foreach ($clonedSubjects as $clonedSubject) {
                                        $getclonedFormstatus->subject_id = $clonedSubject->id;
                                        $getclonedFormstatus->save();
                                    }
                                }
                                $replica_form_status_id = Study::select('id')->latest()->first();
                                $formrevisions = FormRevisionHistory::where('form_submit_status_id', '=', $formsubmitstatus)->get();
                                foreach ($formrevisions as $formrevision) {
                                    FormRevisionHistory::create([
                                        'id'    => (string)Str::uuid(),
                                        'form_submit_status_id' => $replica_form_status_id->id,
                                        'edit_reason_text'  => $formrevision->edit_reason_text
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($request->transmissions ==  'on') {
            /* $transmissions = CrushFtpTransmission::all();
             dd($transmissions);*/
            $transmissions = CrushFtpTransmission::where('StudyI_ID', '=', $originalStudy->study_code)->get();
            foreach ($transmissions as $transmission) {
                $id = (string)Str::uuid();
                CrushFtpTransmission::create([
                    'id' => $id,
                    'data'  => $transmission->data,
                    'Transmission_Number'  => $transmission->Transmission_Number,
                    'Study_Name'  => $transmission->Study_Name . 'Cloned',
                    'StudyI_ID'  => $transmission->StudyI_ID,
                    'sponsor'  => $transmission->sponsor,
                    'Study_central_email'  => $transmission->Study_central_email,
                    'Salute'  => $transmission->Salute,
                    'Submitter_First_Name'  => $transmission->Submitter_First_Name,
                    'Submitter_Last_Name'  => $transmission->Submitter_Last_Name,
                    'Submitter_email'  => $transmission->Submitter_email,
                    'Submitter_phone'  => $transmission->Submitter_phone,
                    'Submitter_Role'  => $transmission->Submitter_Role,
                    'Site_Initials'  => $transmission->Site_Initials,
                    'Site_Name'  => $transmission->Site_Name,
                    'Site_ID'  => $transmission->Site_ID,
                    'sit_id'  => $transmission->sit_id,
                    'PI_Name'  => $transmission->PI_Name,
                    'PI_FirstName'  => $transmission->PI_FirstName,
                    'PI_LastName'  => $transmission->PI_LastName,
                    'PI_email'  => $transmission->PI_email,
                    'Site_st_address'  => $transmission->Site_st_address,
                    'Site_city'  => $transmission->Site_city,
                    'Site_state'  => $transmission->Site_state,
                    'Site_Zip'  => $transmission->Site_Zip,
                    'Site_country'  => $transmission->Site_country,
                    'Subject_ID'  => $transmission->Subject_ID,
                    'subj_id'  => $transmission->subj_id,
                    'new_subject'  => $transmission->new_subject,
                    'StudyEye'  => $transmission->StudyEye,
                    'visit_name'  => $transmission->visit_name,
                    'phase_id'  => $transmission->phase_id,
                    'visit_date'  => $transmission->visit_date,
                    'ImageModality'  => $transmission->ImageModality,
                    'modility_id'  => $transmission->modility_id,
                    'device_model'  => $transmission->device_model,
                    'device_oirrcID'  => $transmission->device_oirrcID,
                    'Compliance'  => $transmission->Compliance,
                    'Compliance_comments'  => $transmission->Compliance_comments,
                    'Submitted_By'  => $transmission->Submitted_By,
                    'photographer_full_name'  => $transmission->photographer_full_name,
                    'photographer_email'  => $transmission->photographer_email,
                    'photographer_ID'  => $transmission->photographer_ID,
                    'Number_files'  => $transmission->Number_files,
                    'transmitted_file_name'  => $transmission->transmitted_file_name,
                    'transmitted_file_size'  => $transmission->transmitted_file_size,
                    'archive_physical_location'  => $transmission->archive_physical_location,
                    'received_month'  => $transmission->received_month,
                    'received_day'  => $transmission->received_day,
                    'received_year'  => $transmission->received_year,
                    'received_hours'  => $transmission->received_hours,
                    'received_minutes'  => $transmission->received_minutes,
                    'received_seconds'  => $transmission->received_seconds,
                    'received-mesc'  => $transmission->received,
                    'Study_QCO1'  => $transmission->Study_QCO1,
                    'StudyQCO2'  => $transmission->StudyQCO2,
                    'Study_cc1'  => $transmission->Study_cc1,
                    'Study_cc2'  => $transmission->Study_cc2,
                    'QC_folder'  => $transmission->QC_folder,
                    'Graders_folder'  => $transmission->Graders_folder,
                    'QClink'  => $transmission->QClink,
                    'Glink'  => $transmission->Glink,
                    'created_by'  => $transmission->created_by,
                    'updated_by'  => $transmission->Study_QCO1,
                    'status'  => $transmission->status,
                    'is_read'  => $transmission->is_read,
                    'dcm_availability'  => $transmission->dcm_availability,
                    'received_file_format'  => $transmission->received_file_format,
                    'qc_officerId'  => $transmission->qc_officerId,
                    'qc_officerName'  => $transmission->qc_officerName,
                    'cms_visit_reference'  => $transmission->cms_visit_reference,
                    'comment'  => $transmission->comment,
                    'created_date'  => $transmission->created_date,
                    'updated_date'  => $transmission->updated_date,
                ]);
            }
        }
        if ($request->auditTrail == 'on') {
            $auditTrails = TrailLog::where('study_id', '=', $originalStudy)->get();
            foreach ($auditTrails as $auditTrail) {
                $id = (string)Str::uuid();
                TrailLog::create([
                    'id'    => $id,
                    'user_id'   => $auditTrail->user_id,
                    'user_name' => $auditTrail->user_name,
                    'role_id'   => $auditTrail->role_id,
                    'event_id'  => $auditTrail->event_id,
                    'event_section' => $auditTrail->event_section,
                    'event_type'    => $auditTrail->event_type,
                    'event_message' => $auditTrail->event_message,
                    'ip_address'    => $auditTrail->ip_address,
                    'study_id'      => $clonedStudy->id,
                    'event_url'     => $request->event_url,
                    'event_details' => $auditTrail->event_details,
                    'event_old_details' => $auditTrail->event_old_details,
                ]);
            }
        }
        if ($request->studyPreferences == 'on') {
            $studyPrefrences = Preference::where('study_id', '=', $originalStudy)->get();
            foreach ($studyPrefrences as $studyPrefrence) {
                Preference::create([
                    'id'        => (string)Str::uuid(),
                    'preference_title'      => $studyPrefrence->preference_title,
                    'preference_value'      => $studyPrefrence->preference_value,
                    'is_selectable'      => $studyPrefrence->is_selectable,
                    'preference_options'      => $studyPrefrence->preference_options,
                    'study_id'      => $studyPrefrence->$clonedStudy->id,
                ]);
            }
        }
        if ($request->studyQueries == 'on') {
            $queries = Query::where('module_id', '=', $originalStudy)->get();
            foreach ($queries as $query) {
                $id = (string)Str::uuid();
                if ($query->parent_query_id == NULL) {
                    Query::create([
                        'id'       => $id,
                        'messages' => $query->messages,
                        'parent_query_id' => $query->parent_query_id,
                        'queried_remarked_by_id' => $query->queried_remarked_by_id,
                        'module_id' => $query->module_id,
                        'module_name' => $query->module_name,
                        'query_status' => $query->query_status,
                        'query_subject' => $query->query_subject,
                        'query_url' => $query->query_url,
                        'query_type' => $query->query_type,
                        'query_attachments' => $query->query_attachments,
                    ]);
                }
            }
        }
        $studies = Study::all();
        // return \response()->json($studies);
        return redirect()->route('studies.index')->with('success', 'Study cloned successfully');
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // log event details
        eventDetails($id, 'Study', 'Delete', $request->ip(), []);

        Study::where('id', $id)->delete();
        Subject::where('study_id', $id)->delete();
        AdjudicationFormStatus::where('study_id', $id)->delete();
        Annotation::where('study_id', $id)->delete();
        Answer::where('study_id', $id)->delete();
        ExportType::where('study_id', $id)->delete();
        FinalAnswer::where('study_id', $id)->delete();
        FormStatus::where('study_id', $id)->delete();
        QuestionAdjudicationRequired::where('study_id', $id)->delete();
        QuestionComments::where('study_id', $id)->delete();

        $phases = StudyStructure::where('study_id', 'like', $id)->get();
        foreach ($phases as $phase) {
            $this->deletePhase($phase, true);
        }
        StudyStructure::where('study_id', $id)->delete();

        AssignWork::where('study_id', $id)->delete();
        DiseaseCohort::where('study_id', $id)->delete();
        Preference::where('study_id', $id)->delete();
        Query::where('study_id', $id)->delete();
        QueryNotification::where('study_id', $id)->delete();
        StudySite::where('study_id', $id)->delete();
        RoleStudyUser::where('study_id', $id)->delete();
        StudyRoleUsers::where('study_id', $id)->delete();
        StudyUser::where('study_id', $id)->delete();
        TrailLog::where('study_id', $id)->delete();
        UserRole::where('study_id', $id)->delete();
        return \response()->json(['sucess' => 'Study deleted successfully.']);
    }

    public function permanentlyDeleteStudyAndItsRecord($id)
    {
        if (isThisUserSuperAdmin(auth()->user())) {

            Study::where('id', $id)->withTrashed()->forceDelete();
            Subject::where('study_id', $id)->withTrashed()->forceDelete();
            AdjudicationFormStatus::where('study_id', $id)->withTrashed()->forceDelete();
            Annotation::where('study_id', $id)->withTrashed()->forceDelete();
            Answer::where('study_id', $id)->withTrashed()->forceDelete();
            ExportType::where('study_id', $id)->withTrashed()->forceDelete();
            FinalAnswer::where('study_id', $id)->withTrashed()->forceDelete();
            FormStatus::where('study_id', $id)->withTrashed()->forceDelete();
            QuestionAdjudicationRequired::where('study_id', $id)->withTrashed()->forceDelete();
            QuestionComments::where('study_id', $id)->withTrashed()->forceDelete();


            $phases = StudyStructure::where('study_id', 'like', $id)->withTrashed()->get();
            foreach ($phases as $phase) {
                $this->deletePhase($phase, true);
            }
            StudyStructure::where('study_id', $id)->withTrashed()->forceDelete();

            AssignWork::where('study_id', $id)->withTrashed()->forceDelete();
            DiseaseCohort::where('study_id', $id)->withTrashed()->forceDelete();
            Preference::where('study_id', $id)->withTrashed()->forceDelete();
            Query::where('study_id', $id)->withTrashed()->forceDelete();
            QueryNotification::where('study_id', $id)->withTrashed()->forceDelete();
            StudySite::where('study_id', $id)->withTrashed()->forceDelete();
            RoleStudyUser::where('study_id', $id)->withTrashed()->forceDelete();
            StudyRoleUsers::where('study_id', $id)->withTrashed()->forceDelete();
            StudyUser::where('study_id', $id)->withTrashed()->forceDelete();
            TrailLog::where('study_id', $id)->withTrashed()->forceDelete();
            UserRole::where('study_id', $id)->withTrashed()->forceDelete();


            StudyStructure::onlyTrashed()->forceDelete();
            PhaseSteps::onlyTrashed()->forceDelete();
            Section::onlyTrashed()->forceDelete();
            Question::onlyTrashed()->forceDelete();
            FormFields::onlyTrashed()->forceDelete();
            QuestionDependency::onlyTrashed()->forceDelete();
            QuestionValidation::onlyTrashed()->forceDelete();
            SkipLogic::onlyTrashed()->forceDelete();
            QuestionComments::onlyTrashed()->forceDelete();
            QuestionOption::onlyTrashed()->forceDelete();

            return \response()->json(['sucess' => 'Study deleted successfully.']);
        }
    }
}
