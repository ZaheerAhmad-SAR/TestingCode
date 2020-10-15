<?php

namespace Modules\UserRoles\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\PhaseSteps;
use DB;

class GradingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $subjects = DB::table('subjects')
                        ->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'sites.site_name')
                        ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
                        ->crossJoin('study_structures')
                        ->orderBy('subjects.subject_id')
                        ->orderBy('study_structures.position')
                        ->paginate(15);

        // get modalities
        $getModalities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name','modilities.id as modility_id', 'modilities.modility_name')
        ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
        ->groupBy('phase_steps.modility_id')
        ->orderBy('modilities.modility_name')
        ->get();

        // modility/steps array
        $modalitySteps = [];

        // get steps for modality
        foreach($getModalities as $key => $modality) {
            $getSteps = PhaseSteps::where('modility_id', $modality->modility_id)->get()->toArray();
            dd($getSteps);
        }

        dd($getModalities);

        return view('userroles::users.grading-list', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('userroles::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('userroles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('userroles::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
