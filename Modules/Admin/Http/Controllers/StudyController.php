<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\Annotation;
use Modules\Admin\Entities\Answer;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\CrushFtpTransmission;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\Admin\Entities\FinalAnswer;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\QuestionAdjudicationStatus;
use Modules\Admin\Entities\QuestionDependency;
use Modules\Admin\Entities\QuestionValidation;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\StudyUser;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\TrailLog;
use Modules\Admin\Scopes\StudyStructureOrderByScope;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Modules\Admin\Traits\QuestionDataValidation;
use Modules\Queries\Entities\Query;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\RolePermission;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Http\Controllers\RoleController;
use Illuminate\Support\Str;
use function Symfony\Component\String\s;


class StudyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (hasPermission(\auth()->user(), 'systemtools.index')) {
            $user = User::with('studies', 'user_roles')->find(Auth::id());
            session(['current_study'=>'']);
            $user = User::with('studies', 'user_roles')->find(Auth::id());
            $studies  =   Study::with('users')->where('id','!=', Null)->orderBy('study_short_name')->get();
            $permissionsIdsArray = Permission::where(function ($query) {
                $query->where('permissions.name', '=', 'studytools.index')
                    ->orwhere('permissions.name', '=', 'studytools.store')
                    ->orWhere('permissions.name', '=', 'studytools.edit')
                    ->orwhere('permissions.name', '=', 'studytools.update');
            })->distinct('id')->pluck('id')->toArray();

            $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
            $userIdsArrayFromUserRole = UserRole::whereIn('role_id', $roleIdsArrayFromRolePermission)->distinct()->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIdsArrayFromUserRole)->distinct()->orderBy('name','asc')->get();
            $sites = Site::all();
            $study = '';
//            dd($studies);

        }
        else{
            $user=\auth()->user()->id;
            if (hasPermission(\auth()->user(),'grading.index')){
                $studies  =   UserRole::select('user_roles.*','users.*','studies.*')
                    ->join('users','users.id','=','user_roles.user_id')
                    ->join('studies','studies.id','=','user_roles.study_id')
                    ->where('users.id','=',\auth()->user()->id)
                    ->where('studies.study_status','=','Live')
                    ->orderBy('study_short_name')->get();
                $study = '';
            }
            if (hasPermission(\auth()->user(),'adjudication.index')){
                $studies  =   UserRole::select('user_roles.*','users.*','studies.*')
                    ->join('users','users.id','=','user_roles.user_id')
                    ->join('studies','studies.id','=','user_roles.study_id')
                    ->where('users.id','=',\auth()->user()->id)
                    ->where('studies.study_status','=','Live')
                    ->orderBy('study_short_name')->get();
                $study = '';
            }
            if (hasPermission(\auth()->user(),'qualitycontrol.index')){
                $studies  =   UserRole::select('user_roles.*','users.*','studies.*')
                    ->join('users','users.id','=','user_roles.user_id')
                    ->join('studies','studies.id','=','user_roles.study_id')
                    ->where('users.id','=',\auth()->user()->id)
                    ->where('studies.study_status','=','Live')
                    ->orderBy('study_short_name')->get();
                $study = '';
            }
            if (hasPermission(\auth()->user(),'studytools.index')) {
                $studies = StudyUser::select('study_user.*', 'users.*', 'studies.*')
                    ->join('users', 'users.id', '=', 'study_user.user_id')
                    ->join('studies', 'studies.id', '=', 'study_user.study_id')
                    ->where('users.id', '=', \auth()->user()->id)
                    ->orderBy('study_short_name')->get();
                $study = '';
            }
        //dd($studies);

        $users = User::all();
        $sites = Site::all();
            $study = '';
        }

        return view('admin::studies.index', compact('studies', 'sites', 'users','study'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function studyStatus(Request $request)
    {
        $study_id = $request->study_ID;

        $study = Study::find($study_id);
        $study = Study::where('id', $study_id)->update(['study_status'=> $request->status]);

        //return \response()->json($data);
                return redirect()->route('studies.index');
    }

    public function create()
    {
        if (Auth::user()->can('users.create')) {
            $users = User::all();
            $sites = Site::get();
            return view('admin::studies.create', compact('users', 'sites')); //->with(compact('permissions'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request){
        $id    = Str::uuid();
        $study = Study::create([
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
        $last_id = Study::select('id')->latest()->first();
        // Disease cohort insertion here
        $id    = Str::uuid();
        $disease = [];
        if (isset($request->disease_cohort_name) && count($request->disease_cohort_name) > 0) {
            for ($i = 0; $i < count($request->disease_cohort_name); $i++) {
                $disease = [
                    'id' => Str::uuid(),
                    'study_id' =>$last_id->id,
                    'name' => $request->disease_cohort_name[$i],
                ];
                DiseaseCohort::insert($disease);
            }
        }
        // insert multi users here
        $id    = Str::uuid();
        $users = [];
        if (isset($request->users) && count($request->users) > 0) {
            for ($i = 0; $i < count($request->users); $i++) {
                $users = [
                    'id' => Str::uuid(),
                    'study_id' =>$last_id->id,
                    'user_id' => $request->users[$i],
                ];
                StudyUser::insert($users);
            }
        }

        $oldStudy = [];
        // log event details
        $logEventDetails = eventDetails($study->id, 'Study', 'Add', $request->ip(), $oldStudy);

        return redirect()->route('studies.index')->with('message', 'Record Added Successfully!');
    }
    public function add_studies(Request $request)
    {

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Study $study)
    {
        session(['current_study' => $study->id, 'study_short_name' => $study->study_short_name]);
        $id = $study->id;
        $studies  =   StudyUser::select('study_user.*','users.*','studies.*')
            ->join('users','users.id','=','study_user.user_id')
            ->join('studies','studies.id','=','study_user.study_id')
            ->where('users.id','=',\auth()->user()->id)
            ->orderBy('study_short_name')->get();
        $study_role= StudyUser::where('study_id','=',$id)->get();
        $currentStudy = Study::find($id);
        $study = Study::find($id);

        $subjects = Subject::select(['subjects.*', 'sites.site_name', 'sites.site_address', 'sites.site_city', 'sites.site_state', 'sites.site_code', 'sites.site_country', 'sites.site_phone'])
            ->where('subjects.study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'subjects.site_id')
            ->get();
        $site_study = StudySite::where('study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->get();

        $diseaseCohort = DiseaseCohort::where('study_id', '=', $id)->get();
        return view('admin::studies.show', compact('study', 'studies', 'subjects', 'currentStudy', 'site_study', 'diseaseCohort'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $studies  =   Study::with('users')
            ->where('id','=',$id)
            ->orderBy('study_short_name')->get();
        $study  = Study::with('diseaseCohort','users')
            ->find($id);


        return \response()->json($study);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request){

    }
    public function update_studies(Request $request)
    {
        // get old data for audit section
        $oldStudy = Study::find($request->study_id);

        $study = Study::where('id', $request->study_id)->first();
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
        $studycohorts = DiseaseCohort::where('study_id',$request->study_id)->delete();
        // Update Disease cohort
        $disease = [];
        if (isset($request->disease_cohort_name) && count($request->disease_cohort_name) > 0) {
            for ($i = 0; $i < count($request->disease_cohort_name); $i++) {
                $disease = [
                    'id' => Str::uuid(),
                    'study_id' =>$request->study_id,
                    'name' => $request->disease_cohort_name[$i],
                ];
                DiseaseCohort::insert($disease);
            }
        }
        // update multi users here
        $study_users = StudyUser::where('study_id',$request->study_id)->delete();
        $users = [];
        if (isset($request->users) && count($request->users) > 0) {
            for ($i = 0; $i < count($request->users); $i++) {
                $users = [
                    'id' => Str::uuid(),
                    'study_id' =>$request->study_id,
                    'user_id' => $request->users[$i],
                ];
                StudyUser::insert($users);
            }
        }

        // log event details
        $logEventDetails = eventDetails($study->id, 'Study', 'Update', $request->ip(), $oldStudy);

        return redirect()->route('studies.index')->with('message', 'Study updated successfully');
    }


    /** get clone of the study */
    public function cloneStudy(Request $request)
    {
        $study_id = $request->study_ID;
        $mystudy = Study::with('users','subjects', 'diseaseCohort')
            ->find($study_id);
        $id = \Illuminate\Support\Str::uuid();

        if (!empty($mystudy) && $request->basic_info == 'on') {
            $replica = Study::create([
                'id'    => $id,
                'study_short_name'  =>  $mystudy->study_short_name . ' Cloned ',
                'study_title' => $mystudy->study_title,
                'study_status'  => 'Development',
                'study_code' => $mystudy->study_code,
                'protocol_number' => $mystudy->protocol_number,
                'study_phase' => $mystudy->study_phase,
                'trial_registry_id' => $mystudy->trial_registry_id,
                'study_sponsor' => $mystudy->study_sponsor,
                'start_date' => $mystudy->start_date,
                'end_date' => $mystudy->end_date,
                'description'   =>  $mystudy->description,
                'user_id'       => auth()->user()->id
            ]);
            $replica_id = Study::select('id')->latest()->first();
            if ($mystudy->diseaseCohort) {
                foreach ($mystudy->diseaseCohort as $disease_cohort) {
                    $id = \Illuminate\Support\Str::uuid();
                    $diseaseCohort = DiseaseCohort::create([
                        'id'    => $id,
                        'study_id'  => $replica_id->id,
                        'name'      => $disease_cohort->name
                    ]);
                }
            }
            if ($request->studyUsers  == 'on') {
                $study_users = UserRole::where('study_id','=',$study_id)->get();
                foreach ($study_users  as $user) {
                    $id = \Illuminate\Support\Str::uuid();
                    $user = UserRole::create([
                        'id'    => $id,
                        'role_id' => $user->role_id,
                        'user_id' => $user->id,
                        'study_id' => $replica_id->id
                    ]);
                }
            }
            if ($request->studySites == 'on') {
                $study_sites = StudySite::where('study_id', '=',$study_id)->get();
                foreach ($study_sites as $site) {
                    $id = \Illuminate\Support\Str::uuid();
                   $site = StudySite::create([
                        'study_id' => $replica_id->id,
                        'site_id' => $site->site_id,
                        'study_site_id' => $site->study_site_id . 'Cloned'
                    ]);
                    $replica_site_id = Study::select('id')->latest()->first();
                    $primary_investigators = PrimaryInvestigator::where('site_id','=',$site->site_id)->get();
                    foreach ($primary_investigators as $primary_investigator){
                        $id = \Illuminate\Support\Str::uuid();
                        PrimaryInvestigator::create([
                            'id' => $id,
                            'first_name'    => $primary_investigator->first_name,
                            'mid_name'    => $primary_investigator->mid_name,
                            'last_name'    => $primary_investigator->last_name,
                            'site_id'    => $replica_site_id->id,
                            'phone'    => $primary_investigator->phone,
                            'email'    => $primary_investigator->email,
                        ]);
                    }
                    $coordinators = Coordinator::where('site_id','=',$site->site_id)->get();
                    foreach ($coordinators as $coordinator){
                        $id = \Illuminate\Support\Str::uuid();
                        PrimaryInvestigator::create([
                            'id' => $id,
                            'first_name'    => $coordinator->first_name,
                            'mid_name'    => $coordinator->mid_name,
                            'last_name'    => $coordinator->last_name,
                            'site_id'    => $replica_site_id->id,
                            'phone'    => $coordinator->phone,
                            'email'    => $coordinator->email,
                        ]);
                    }
                    $photographers = Photographer::where('site_id','=',$site->site_id)->get();
                    foreach ($photographers as $photographer){
                        $id = \Illuminate\Support\Str::uuid();
                        PrimaryInvestigator::create([
                            'id' => $id,
                            'first_name'    => $photographer->first_name,
                            'mid_name'    => $photographer->mid_name,
                            'last_name'    => $photographer->last_name,
                            'site_id'    => $replica_site_id->id,
                            'phone'    => $photographer->phone,
                            'email'    => $photographer->email,
                        ]);
                    }
                    $others = Other::where('site_id','=',$site->site_id)->get();
                    foreach ($others as $other){
                        $id = \Illuminate\Support\Str::uuid();
                        PrimaryInvestigator::create([
                            'id' => $id,
                            'first_name'    => $other->first_name,
                            'mid_name'    => $other->mid_name,
                            'last_name'    => $other->last_name,
                            'site_id'    => $replica_site_id->id,
                            'phone'    => $other->phone,
                            'email'    => $other->email,
                        ]);
                    }

                }
            }
            if ($request->studySubjects == 'on'){
                $study_subjects = Subject::where('study_id',$study_id)->get();
                foreach ($study_subjects as $subject){
                    $id = \Illuminate\Support\Str::uuid();
                    Subject::create([
                        'id' => $id,
                        'study_id'  => $replica_id->id,
                        'subject_id'    => $subject->subject_id,
                        'enrollment_date'   => $subject->enrollment_date,
                        'study_eye' => $subject->study_eye,
                        'site_id' => $subject->site_id,
                        'disease_cohort_id' => $subject->disease_cohort_id,
                    ]);
                    $replica_subject_id = Study::select('id')->latest()->first();
                }
            }
            if ($request->phasesSteps == 'on'){
                $study_phases = StudyStructure::where('study_id','=',$study_id)->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)->get();
                foreach ($study_phases as $phase){
                    $id = \Illuminate\Support\Str::uuid();
                    StudyStructure::create([
                        'id'    =>  $id,
                        'study_id'  => $replica_id->id,
                        'name'  => $phase->name,
                        'position'  => $phase->position,
                        'duration'  => $phase->duration,
                    ]);
                    $replica_phase_id = StudyStructure::select('id')->latest()->first();
                    $study_phase_steps = PhaseSteps::where('phase_id','=',$phase->id)->get();
                    foreach ($study_phase_steps as $step){
                        $id = \Illuminate\Support\Str::uuid();
                        PhaseSteps::create([
                            'step_id'    =>  $id,
                            'phase_id'  =>   $replica_phase_id,
                            'step_position' => $step->step_position,
                            'form_type' => $step->form_type,
                            'form_type_id' => $step->form_type_id,
                            'step_name' => $step->step_name,
                            'step_description' => $step->step_description,
                            'graders_number' => $step->graders_number,
                            'q_c' => $step->q_c,
                            'eligibility' => $step->eligibility,
                        ]);
                        $replica_step_id = PhaseSteps::select('id')->latest()->first();
                        $study_step_sections = Section::where('phase_steps_id','=',$step->step_id)->get();
                        foreach ($study_step_sections  as $section){
                            $id = \Illuminate\Support\Str::uuid();
                            Section::create([
                                'id'    =>  $id,
                                'phase_steps_id'    =>  $replica_step_id,
                                'name'  =>  $section->name,
                                'description'  =>  $section->description,
                                'sort_number'  =>  $section->sort_number,
                            ]);
                            $replica_section_id = Section::select('id')->latest()->first();
                            $study_section_questions = Question::where('section_id','=',$section->id)->get();
                            foreach ($study_section_questions as $question){
                                $id = \Illuminate\Support\Str::uuid();
                                Question::create([
                                    'id'    =>  $id,
                                    'form_field_type_id'    => $question->form_field_type_id,
                                    'section_id'    => $replica_section_id,
                                    'option_group_id'    => $question->option_group_id,
                                    'question_sort'    => $question->question_sort,
                                    'question_text'    => $question->question_text,
                                    'c_disk'    => $question->c_disk,
                                    'measurement_unit'    => $question->measurement_unit,
                                    'is_dependent'    => $question->is_dependent,
                                    'dependent_on'    => $question->dependent_on,
                                    'annotations'    => $question->annotations,
                                    'certification_type'    => $question->certification_type,
                                    'certification_type'    => $question->certification_type,
                                    'deleted_at'    => Null
                                ]);
                                $replica_question_id = Question::select('id')->latest()->first();
                                $question_validations = QuestionValidation::where('question_id','=',$question->id)->get();
                                foreach ($question_validations as $question_validation){
                                    $id = Str::uuid();
                                    QuestionValidation::create([
                                        'id'    => $id,
                                        'question_id'   => $replica_question_id,
                                        'validation_rule_id'    => $question->validation_rule_id,
                                        'decision_one'  => $question->decision_one,
                                        'opertaor_one'  => $question->opertaor_one,
                                        'dep_on_question_one_id'  => $question->dep_on_question_one_id,
                                        'decision_two'  => $question->decision_two,
                                        'opertaor_two'  => $question->opertaor_two,
                                        'error_type'  => $question->error_type,
                                        'error_message'  => $question->error_message,
                                        'deleted_at'  => $question->deleted_at,
                                    ]);
                                }
                                $question_dependencies = QuestionDependency::where('question_id','=',$question->id)->get();
                                foreach ($question_dependencies as $question_dependency){
                                    $id = Str::uuid();
                                    QuestionDependency::create([
                                        'id'    => $id,
                                        'question_id'   => $replica_question_id,
                                        'q_d_status'    => $question->q_d_status,
                                        'dep_on_question_id'    => $question->dep_on_question_id,
                                        'opertaor'  => $question->opertaor,
                                        'custom_value'  => $question->custom_value
                                    ]);
                                }
                                $question_annotations = Annotation::where('study_id','=',$mystudy)->get();
                                foreach ($question_annotations as $question_annotation){
                                    $id = Str::uuid();
                                    Annotation::create([
                                        'id'    => $id,
                                        'study_id'  => $replica_id->id,
                                        'label' => $question_annotation->label,
                                        'deleted_at'    => Null
                                    ]);
                                }
                                $question_adjudications = QuestionAdjudicationStatus::where('question_id','=',$question->id)->get();
                                foreach ($question_adjudications as $question_adjudication){
                                    $id = Str::uuid();
                                    QuestionAdjudicationStatus::create([
                                        'id'    => $id,
                                        'question_id'   => $replica_question_id,
                                        'adj_status'    => $question_adjudication->adj_status,
                                        'decision_based_on'    => $question_adjudication->decision_based_on,
                                        'opertaor'    => $question_adjudication->opertaor,
                                        'differnce_status'    => $question_adjudication->differnce_status,
                                        'custom_value'    => $question_adjudication->custom_value,
                                        'deleted_at'    => NULL,
                                    ]);
                                }
                                if($request->answers == 'on'){
                                    $answers = Answer::where('question_id','=',$question)->get();
                                    $final_answers = FinalAnswer::where('question_id','=',$question)->get();
                                    foreach ($answers as $answer){
                                        $id = \Illuminate\Support\Str::uuid();
                                        Answer::create([
                                            'id'    => $id,
                                            'form_filled_by_user_id'    => $question->form_filled_by_user_id,
                                            'grader_id' => $question->grader_id,
                                            'adjudicator_id'   => $question->adjudicator_id,
                                            'subject_id'    => '',
                                            'study_id'  => $replica_id->id,
                                            'study_structures_id'   => $replica_phase_id,
                                            'phase_steps_id'    => $replica_step_id,
                                            'section_id'    => $replica_section_id,
                                            'question_id'   => $replica_question_id,
                                            'field_id'  => $request->field_id,
                                            'answer'    => $request->answer,
                                            'is_answer_accepted'    => $request->is_answer_accepted
                                        ]);
                                    }
                                    foreach ($final_answers as $final_answer){
                                        $id = \Illuminate\Support\Str::uuid();
                                        FinalAnswer::create([
                                            'id'    => $id,
                                            'study_id'  => $replica_id->id,
                                            'subject_id'    => '',
                                            'study_structures_id'   => $replica_phase_id,
                                            'phase_steps_id'    => $replica_step_id,
                                            'section_id'    => $replica_section_id,
                                            'question_id'   => $replica_question_id,
                                            'field_id'  => $request->field_id,
                                            'answer'    => $request->answer,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($request->transmissions ==  'on'){
                $transmissions = CrushFtpTransmission::where('study_id','=',$mystudy)->get();
                foreach ($transmissions as $transmission){
                    $id = Str::uuid();
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
                        'received-mesc'  => $transmission->received-mesc,
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
            if ($request->auditTrail == 'on'){
                $auditTrails = TrailLog::where('study_id','=',$mystudy)->get();
                foreach ($auditTrails as $auditTrail){
                    $id = Str::uuid();
                    TrailLog::create([
                        'id'    => $id,
                        'user_id'   => $auditTrail->user_id,
                        'user_name' => $auditTrail->user_name,
                        'role_id'   => $auditTrail->role_id,
                        'event_id'  => $auditTrail->event_id,
                        'event_section' =>$auditTrail->event_section,
                        'event_type'    => $auditTrail->event_type,
                        'event_message' => $auditTrail->event_message,
                        'ip_address'    => $auditTrail->ip_address,
                        'study_id'      => $replica_id->id,
                        'event_url'     => $request->event_url,
                        'event_details' => $auditTrail->event_details,
                        'event_old_details' => $auditTrail->event_old_details,
                    ]);
                }
            }
        }
        $studies = Study::all();
       // return \response()->json($studies);
       return redirect()->route('studies.index')->with('success','Study cloned successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $study = Study::where('id', $id)->delete();
        $studyusers = UserRole::where('study_id','=',$id)->get();
        foreach ($studyusers as $studyuser){
            $studyuser->delete();
        }
        $studysites = StudySite::where('study_id','=',$id)->get();
        foreach ($studysites as $studysite){
            $studysite->delete();
        }
        return \response()->json($study);
    }
}
