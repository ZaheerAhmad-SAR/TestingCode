<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\StudyUser;
use Modules\Admin\Entities\Subject;
use Psy\Util\Str;


class StudyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
      $user = User::with('studies','user_roles')->find(Auth::id());
        //$user = User::with('studies')->find(Auth::id());
       if(hasPermission(\auth()->user(),'users.create')){
            $studies  =   Study::orderBy('study_short_name')->get();
            $users = User::select('users.*')->join('user_roles','user_roles.user_id','=','users.id')
                ->join('roles','roles.id','=','user_roles.role_id')
                ->where('roles.name','=','Study Admin')
                ->get();
            $sites = Site::all(
            );
            //dd('admin');
        }
        elseif ($user->role->name == 'manager'){
            //dd('manager');
            $studies = Study::with('users')->get();
            $users = User::all();
            $sites = Site::all();
            //dd($sites);
        }
        else{
            //dd('else');
            $studies  =   Study::paginate(3);
            $users = User::all();
            $sites = Site::all();
        }

        return view('admin::studies.index',compact('studies','sites','users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function studyStatus(Request $request)
    {
        $study_id = $request->study_id;

            $study = Study::find($study_id);

            $studyStatus = Study::where('id','=',$study_id)->update(array(
                'study_status' => !empty($request->study_status)?$request->study_status:'Development'
            ));

        $data = [
            'success' => true
        ];
        return \response()->json($data);
//        return view('admin::studies.index',compact('studies'))->json_encode($data);
    }

    public function create()
    {
        if (Auth::user()->can('users.create')) {
            $users = User::all();
            $sites = Site::get();
            return view('admin::studies.create', compact('users','sites'));//->with(compact('permissions'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $studyID = $request->study_id;
        $study   =   Study::updateOrCreate([
            'id' => $studyID],
            [
                'id'    => \Illuminate\Support\Str::uuid(),
                'study_short_name'  =>  $request->study_short_name,
                'study_title' => $request->study_title,
                'study_status'  => 'Development',
                'study_code' => $request->study_code,
                'protocol_number'=> $request->protocol_number,
                'study_phase'=>$request->study_phase,
                'trial_registry_id'=>$request->trial_registry_id,
                'study_sponsor'=>$request->study_sponsor,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description'   =>  $request->description,
                'user_id'       => $request->user()->id
            ]);

      if(!empty($request->users)){
            foreach ($request->users as $user) {
                StudyUser::create([
                    'id'    => \Illuminate\Support\Str::uuid(),
                    'user_id' => $user,
                    'study_id' =>$study->id
                ]);
            }
        }
/*
        if (!empty($request->sites)) {
            foreach ($request->sites as $site) {
                StudySite::create([
                    'id'    => \Illuminate\Support\Str::uuid(),
                    'study_id' => $study->id,
                    'site_id' => $site
                ]);
            }
        }*/

        if(!empty($request->disease_cohort)){
            foreach ($request->disease_cohort as $disease_cohort){
                $diseaseCohort = DiseaseCohort::create([
                    'id'    => \Illuminate\Support\Str::uuid(),
                    'study_id'  => $study->id,
                    'name'      => $disease_cohort
                ]);
            }
            }

        return \response()->json($study);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Study $study)
    {
        session(['current_study'=>$study->id,'study_short_name'=> $study->study_short_name]);
        $id = $study->id;
        $currentStudy = Study::find($id);
        $subjects = Subject::where('subjects.study_id','=',$id)
            ->join('sites','sites.id','=','subjects.site_id')
            ->get();
        $site_study = StudySite::where('study_id','=',$id)
            ->join('sites', 'sites.id','=','site_study.site_id')
            ->select('sites.site_name','sites.id')
            ->get();

        $diseaseCohort = DiseaseCohort::where('study_id','=',$id)->get();
        return view('admin::studies.show',compact('study','subjects','currentStudy','site_study','diseaseCohort'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $study  = Study::with('diseaseCohort')->where($where)->first();

        return \response()->json($study);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Study $study)
    {
        $study = Study::with('diseaseCohort','users')->find($study->id);
        dd($study);
        dd($request->all());
        $validatedData = $request->validate([
        'study_short_name' => 'required|max:25',
        'protocol_number' => 'required|max:25',
        'study_phase' => 'required',
        'study_title' => 'required|max:255',
        'study_code' => 'required|max:255',
        'description' => 'required|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'trial_registry_id' => 'required',
         'study_sponsor' => 'required'
    ]);
        $study->update($request->all());

        return redirect()->route('studies.index')->with('success','Study updated successfully');
    }

    /** get clone of the study */
    public function cloneStudy(Request $request){
        $mystudy = Study::with('users','subjects','diseaseCohort','sites')
            ->find($request->id);
        $id = \Illuminate\Support\Str::uuid();
        $study_subjects = Subject::where('study_id','=',$request->id)->get();

        if(!empty($mystudy)){
            $replica = Study::create([
                'id'    => $id,
                'study_short_name'  =>  $mystudy->study_short_name.' Cloned ',
                'study_title' => $mystudy->study_title,
                'study_status'  => 'Development',
                'study_code' => $mystudy->study_code,
                'protocol_number'=> $mystudy->protocol_number,
                'study_phase'=>$mystudy->study_phase,
                'trial_registry_id'=>$mystudy->trial_registry_id,
                'study_sponsor'=>$mystudy->study_sponsor,
                'start_date' => $mystudy->start_date,
                'end_date' => $mystudy->end_date,
                'description'   =>  $mystudy->description,
                'user_id'       => auth()->user()->id
            ]);
            $replica_id = Study::select('id')->latest()->first();
            if($mystudy->users){
                foreach ($mystudy->users as $user) {
                    $id = \Illuminate\Support\Str::uuid();
                    $user = StudyUser::create([
                        'id'    => $id,
                        'user_id' => $user->id,
                        'study_id' =>$replica_id->id
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
            if($mystudy->diseaseCohort){
                foreach ($mystudy->diseaseCohort as $disease_cohort){
                    $id = \Illuminate\Support\Str::uuid();
                    $diseaseCohort = DiseaseCohort::create([
                        'id'    => $id,
                        'study_id'  => $replica_id->id,
                        'name'      => $disease_cohort->name
                    ]);
                }
            }
            if ($mystudy->subjects){
                foreach ($study_subjects as $subject){
                    $disease_id =$subject->disease_cohort_id;
                    $id = \Illuminate\Support\Str::uuid();
                    $subject = Subject::create([
                        'id'    => $id,
                        'study_id' => $replica_id->id,
                        'subject_id'    => $subject->subject_id.' cloned',
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
        $study = Study::where('id',$id)->delete();
        //dd($study);
        return \response()->json($study);
    }
}
