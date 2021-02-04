<?php

namespace Modules\Dashboards\Http\Controllers;
use App\User;
use App\Mail\TransmissonQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\CrushFtpTransmission;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Subject;
use Modules\FormSubmission\Entities\SubjectsPhases;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\TransmissionUpdateDetail;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\DeviceModility;
use Modules\Admin\Entities\ModalityPhase;
use Modules\Admin\Entities\PhaseSteps;
use Modules\UserRoles\Entities\StudyRoleUsers;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $getVisits = FormStatus::paginate(50);
        // get modality
        $getModalities = Modility::get();
        // get users for filter
        $users = User::get();
        // get Users for gride with condition
        $study_users = StudyRoleUsers::with('study','user','role');
        if($request->study_id !=''){
            $study_users = $study_users->where('study_id', 'like', '%' . $request->study_id . '%');
        }
        if($request->user_id !=''){
            $study_users = $study_users->where('user_id', 'like', '%' . $request->user_id . '%');
        }
        $study_users = $study_users->paginate(50);
        // get studies
        $getStudies = Study::get();
        return view('dashboards::finance.index', compact('getVisits', 'getModalities', 'getStudies','users','study_users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dashboards::create');
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
        return view('dashboards::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dashboards::edit');
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
