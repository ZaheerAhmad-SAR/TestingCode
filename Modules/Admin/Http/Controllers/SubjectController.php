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
    public function index(Study $study)
    {
        session(['current_study' => $study->id, 'study_short_name' => $study->study_short_name]);
        $id = $study->id;
        $currentStudy = Study::find($id);
        $subjects = Subject::select(['subjects.*', 'sites.site_name', 'sites.site_address', 'sites.site_city', 'sites.site_state', 'sites.site_code', 'sites.site_country', 'sites.site_phone'])
            ->where('subjects.study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'subjects.site_id')
            ->get();
        $site_study = StudySite::where('study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->get();

        $diseaseCohort = DiseaseCohort::where('study_id', '=', $id)->get();
        
        return view('admin::subjects.index', compact('study', 'subjects', 'currentStudy', 'site_study', 'diseaseCohort'));
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
        $subjectID = Str::uuid();
        $subject = Subject::create([
            'id'    => $subjectID,
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

        $id = session('current_study');
        $currentStudy = Study::find($id);
        $subjects = Subject::select(['subjects.*', 'sites.site_name', 'sites.site_address', 'sites.site_city', 'sites.site_state', 'sites.site_code', 'sites.site_country', 'sites.site_phone'])
            ->where('subjects.study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'subjects.site_id')
            ->get();

        $site_study = StudySite::where('study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->get();

        $diseaseCohort = DiseaseCohort::where('study_id', '=', $id)->get();

        $oldSubject = [];

        // log event details
        $logEventDetails = eventDetails($subjectID, 'Subject', 'Add', $request->ip(), $oldSubject);

        return view('admin::studies.show', compact('currentStudy', 'subjects', 'site_study', 'diseaseCohort', 'study'));
        //return \response()->json();

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin::studies.show');
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
        $getDuplicateSubject = Subject::where('id', '!=', $request->edit_id)
                                        ->where('subject_id', $request->subject_id)
                                        ->first();
        if($getDuplicateSubject != null) {
            \Session::flash('error', 'Duplicate subject ID.');
            return redirect()->back();
        }
        // get old subject data for logs
        $oldSubject = Subject::where('id', $request->edit_id)->first();

        $updateSubject = Subject::find($request->edit_id);
        $updateSubject->study_id = \Session::get('current_study');
        $updateSubject->subject_id    = $request->subject_id;
        $updateSubject->user_id       = $request->user()->id;
        $updateSubject->enrollment_date   = $request->enrollment_date;
        $updateSubject->study_eye         = $request->study_eye;
        $updateSubject->site_id           = $request->site_id;
        $updateSubject->disease_cohort_id = $request->disease_cohort;
        $updateSubject->save();

        // log event details
        $logEventDetails = eventDetails($updateSubject->id, 'Subject', 'Update', $request->ip(), $oldSubject);

        $currentStudy = session('current_study');
        $currentStudy = Study::find($currentStudy);
        $study = $currentStudy;

        $id = session('current_study');
        $currentStudy = Study::find($id);
        $subjects = Subject::select(['subjects.*', 'sites.site_name', 'sites.site_address', 'sites.site_city', 'sites.site_state', 'sites.site_code', 'sites.site_country', 'sites.site_phone'])
            ->where('subjects.study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'subjects.site_id')
            ->get();
        $site_study = StudySite::where('study_id', '=', $id)
            ->join('sites', 'sites.id', '=', 'site_study.site_id')
            ->select('sites.site_name', 'sites.id')
            ->get();

        $diseaseCohort = DiseaseCohort::where('study_id', '=', $id)->get();

        return view('admin::studies.show', compact('currentStudy', 'subjects', 'site_study', 'diseaseCohort', 'study'));
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

    public function checkSubject(Request $request) {

        if ($request->ajax()) {
            // if add function
            if($request->type == 'add') {

                $checkSubject = Subject::where('study_id', \Session::get('current_study'))
                                        ->where('subject_id', $request->subject_id)
                                        ->first();
                if($checkSubject != null) {
                    return 'error';
                } else {
                    return 'success';
                }

            } elseif ($request->type == 'update') {

                $checkSubject = Subject::where('study_id', \Session::get('current_study'))
                                        ->where('id', '!=', $request->edit_id)
                                        ->where('subject_id', $request->subject_id)
                                        ->first();
                if($checkSubject != null) {
                    return 'error';
                } else {
                    return 'success';
                }           

            } // type if ends
        } // ajax if ends
    } // function ends
}
