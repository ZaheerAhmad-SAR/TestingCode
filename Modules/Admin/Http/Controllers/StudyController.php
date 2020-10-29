<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Question;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\StudyUser;
use Modules\Admin\Entities\Subject;
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
        $mystudy = Study::with('users','subjects', 'diseaseCohort', 'sites','studySteps','phase')
            ->find($study_id);
        $id = \Illuminate\Support\Str::uuid();
        $study_subjects = Subject::where('study_id',$study_id)->get();
        $study_sites = StudySite::where('study_id', '=',$study_id)->get();
        foreach ($study_sites as $site){
            $primary_investigators = PrimaryInvestigator::where('site_id','=',$site->site_id)->get();
            $coordinators = Coordinator::where('site_id','=',$site->site_id)->get();
            $photographers = Photographer::where('site_id','=',$site->site_id)->get();
            $others = Other::where('site_id','=',$site->site_id)->get();
        }
        $study_users = UserRole::where('study_id','=',$study_id)->get();
        $study_phases = StudyStructure::where('study_id','=',$study_id)->get();
        foreach ($study_phases as $phase){
            $study_phase_steps = PhaseSteps::where('phase_id','=',$phase->id)->get();
           foreach ($study_phase_steps as $step){
               $study_step_sections = Section::where('phase_steps_id','=',$step->step_id)->get();
               foreach ($study_step_sections  as $section){
                   $study_section_questions = Question::where('section_id','=',$section->id)->get();
                   dd($study_section_questions);
               }
           }
        }

        if (!empty($mystudy)) {
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
            if ($mystudy->users) {
                foreach ($mystudy->users as $user) {
                    $id = \Illuminate\Support\Str::uuid();
                    $user = StudyUser::create([
                        'id'    => $id,
                        'user_id' => $user->id,
                        'study_id' => $replica_id->id
                    ]);
                }
            }
            if ($mystudy->sites) {
                foreach ($mystudy->sites as $site) {
                    $id = \Illuminate\Support\Str::uuid();
                    StudySite::create([
                        'id'    => $id,
                        'study_id' => $replica_id->id,
                        'site_id' => $site->id
                    ]);
                }
            }
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
            if ($mystudy->subjects) {
                foreach ($study_subjects as $subject) {
                    $disease_id = $subject->disease_cohort_id;
                    $id = \Illuminate\Support\Str::uuid();
                    $subject = Subject::create([
                        'id'    => $id,
                        'study_id' => $replica_id->id,
                        'subject_id'    => $subject->subject_id . ' cloned',
                        'user_id'       => \auth()->user()->id,
                        'enrollment_date'   => $subject->enrollment_date,
                        'study_eye'         => $subject->study_eye,
                        'site_id'           => $subject->site_id,
                        'disease_cohort_id' => $disease_id
                    ]);
                    //       dd($subject);

                }
            }
        }
        $studies = Study::all();
        return \response()->json($studies);
        //     return redirect()->route('studies.index')->with('success','Study cloned successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $study = Study::where('id', $id)->delete();
        //dd($study);
        return \response()->json($study);
    }
}
