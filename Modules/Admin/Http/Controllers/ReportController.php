<?php

namespace Modules\Admin\Http\Controllers;

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
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // For Sorting purpose
        if(isset($request->sort_by_field_name) && $request->sort_by_field_name !=''){
            $field_name = $request->sort_by_field_name;
        }else{
            $field_name = 'Site_ID';
        }
        
        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $asc_or_decs = $request->sort_by_field;
        }else{
            $asc_or_decs = 'ASC';
        }
        // end
        $getTransmissions = CrushFtpTransmission::query();
        if ($request->trans_id != '') {
            $getTransmissions = $getTransmissions->where('Transmission_Number', 'like', '%' . $request->trans_id . '%');
        }
        if ($request->study_id != '') {
            $getTransmissions = $getTransmissions->where('StudyI_ID', $request->study_id);
        }
        if ($request->subject_id != '') {
            $getTransmissions = $getTransmissions->where('Subject_ID', 'like', '%' . $request->subject_id . '%');
        }
        if ($request->visit_name != '') {
            $getTransmissions = $getTransmissions->where('visit_name', 'like', '%' . $request->visit_name . '%');
        }
        if ($request->visit_date != '') {
            $visitDate = explode('-', $request->visit_date);
            $from   = Carbon::parse($visitDate[0]); // 2018-09-29 00:00:00
            $to     = Carbon::parse($visitDate[1]); // 2018-09-29 23:59:59
            $getTransmissions =  $getTransmissions->whereDate('visit_date', '>=', $from)
                ->whereDate('visit_date', '<=', $to);
        }
        if ($request->imagine_modality != '') {
            $getTransmissions = $getTransmissions->where('ImageModality', $request->imagine_modality);
        }
        if ($request->is_read != '') {
            $getTransmissions = $getTransmissions->where('is_read', $request->is_read);
        }
        if ($request->status != '') {

            $getTransmissions = $getTransmissions->where('status', $request->status);
        }
        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $getTransmissions = $getTransmissions->orderBy($field_name , $request->sort_by_field);
        }
        $getTransmissions = $getTransmissions->where('status','accepted');
        $getTransmissions = $getTransmissions->paginate(\Auth::user()->user_prefrences->default_pagination)->withPath('?sort_by_field_name='.$field_name.'&sort_by_field='.$asc_or_decs);
        // get modality
        $getModalities = Modility::get();
        // get studies
        $getStudies = Study::get();
        return view('admin::reports.index', compact('getTransmissions', 'getModalities', 'getStudies'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
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
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
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
