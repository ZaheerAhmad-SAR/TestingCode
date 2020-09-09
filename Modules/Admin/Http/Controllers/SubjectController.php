<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Admin\Entities\DiseaseCohort;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        dd('here');
        $subjects = Subject::all();
        $sites = Study::with('sites')->get();
        return view('admin::studies.show',compact('subjects','sites'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
       //dd($request->all());
        $id = Str::uuid();
        $subject = Subject::create([
            'id'    => $id,
            'study_id' => $request->study_id,
            'subject_id'    => $request->subject_id,
            'user_id'       => $request->user()->id,
            'enrollment_date'   => $request->enrollment_date,
            'study_eye'         => $request->study_eye,
            'site_id'           => $request->site_id,
            'disease_cohort_id' => $request->disease_cohort
        ]);

        $currentStudy = session('current_study');
        $currentStudy = Study::find($currentStudy);
        $study = $currentStudy;

        $subjects = Subject::join('sites','sites.id','=','subjects.site_id')->get();
        $site_study = StudySite::where('study_id','=',$id)
            ->join('sites', 'sites.id','=','site_study.site_id')
            ->select('sites.site_name','sites.id')
            ->get();

        $diseaseCohort = DiseaseCohort::where('study_id','=',$id)->get();


        return view('admin::studies.show',compact('currentStudy','subjects','site_study','diseaseCohort','study'));

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
