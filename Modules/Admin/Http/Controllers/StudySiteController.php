<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\OptionsGroup;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\SiteStudyCoordinator;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Study;
use Modules\UserRoles\Http\Requests\RoleRequest;
use MongoDB\Driver\Query;
use MongoDB\Driver\Session;

class StudySiteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $siteArray = array();
        $sites = StudySite::select('site_study.*'
            ,'sites.site_name'
            ,'sites.site_address'
            ,'sites.site_city'
            ,'sites.site_state'
            ,'sites.site_country'
            ,'sites.site_code'
            ,'sites.site_phone'
        )->join('sites','sites.id','=','site_study.site_id')
            ->where('site_study.study_id','=',session('current_study'))->get();
        foreach ($sites as $site)
        {
            $siteArray[] = $site->site_id;
            $primaryInvestigator  = PrimaryInvestigator::where('site_id',$site->site_id)->get();
            $primaryArray = array();
            foreach ($primaryInvestigator as $primary)
            {
                $primaryArray[] = $primary->id.'/'. $primary->first_name.' '.$primary->last_name;
                //$primaryArray[] = $primary->id.'/'. $primary->first_name;
            }
            $site->pi=$primaryArray;
        }
        $unassignSites = Site::select('sites.*')
            ->whereNotIn('sites.id', $siteArray)->get();

        return view('admin::studies.studySiteNew',compact('sites','unassignSites'));
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
        dd($request->all());
//        where('table_name.id','=',$request->id)
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        dd('hitting');
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

    public function update(Request $request)
    {
            //dd($request->all());
            $others = '';

            $sites = $request->check_sites != null ? $request->check_sites : [];
            $current_study =  \Session::get('current_study');

        foreach($sites as $key => $item)
            {
                $row = StudySite::where('site_id',$key)->delete();
                $result = StudySite::create([
                    'id'    => (string)Str::uuid(),
                    'site_id' =>$key,
                    'study_id'=>$current_study,
                ]);
            }




            // get event data
            $oldStudySite = StudySite::select(\DB::raw('CONCAT(sites.site_name, " - ", sites.site_code) AS site_name_code'))
            ->leftjoin('sites', 'sites.id', '=', 'site_study.site_id')
            ->where('site_study.study_id', $current_study)
            ->pluck('site_name_code')
            ->toArray();

            //$study = Study::find($current_study);

            //$syncSites = $study != null ? $study->studySites()->sync($sites) : [];

            // log event details
            $logEventDetails = eventDetails($current_study, 'Study Site', 'Update', $request->ip(), $oldStudySite);
          \Illuminate\Support\Facades\Session::flash('message', 'This is a message!');

        return back();
    }

    public function removeAssignedSites(Request $request)
    {
        $sites = $request->check_sites != null ? $request->check_sites : [];
        $current_study =  \Session::get('current_study');

        foreach($sites as $key => $item)
        {
            $row = StudySite::where('site_id',$key)->where('study_id',$current_study)->delete();
        }
        return back();
    }

    public function checkSiteExist(Request $request)
    {
        if (Site::where('site_code', $request->post('siteCode'))->first()) {
            return response()->json(['success'=>'Site Code already Exist']);
        }
    }

    public function assignedSites(Request $request)
    {

        $total_sites = Site::all();
        $sites = Site::query();
        // For Sorting purpose
        if(isset($request->sort_by_field_name) && $request->sort_by_field_name !=''){
            $field_name = $request->sort_by_field_name;
        }else{
            $field_name = 'site_code';
        }

        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $asc_or_decs = $request->sort_by_field;
        }else{
            $asc_or_decs = 'ASC';
        }

        if ($request->site_code != '') {
            $sites = $sites->where('site_code','like', '%'.$request->site_code.'%');
        }
        if ($request->site_name != '') {
            $sites = $sites->where('site_name','like', '%'.$request->site_name.'%');
        }
        if ($request->site_city != '') {
            $sites = $sites->where('site_city','like', '%'.$request->site_city.'%');
        }
        if ($request->site_state != '') {
            $sites = $sites->where('site_state','like', '%'.$request->site_state.'%');
        }
        if ($request->site_country != '') {
            $sites = $sites->where('site_country','like', '%'.$request->site_country.'%');
        }
        if ($request->site_phone != '')
        {
            $sites = $sites->where('site_phone','like', '%'.$request->site_phone.'%');
        }
        if ($request->status =='yes')
        {
            $asignedSites = StudySite::where('study_id', '=',session('current_study'))->pluck('site_id')->toArray();
            $yes = is_array($asignedSites) ? $asignedSites : [$asignedSites];
            $sites = $sites->whereIn('id', $yes);
        }
        if ($request->status =='no')
        {
            $asignedSites = StudySite::where('study_id', '=',session('current_study'))->pluck('site_id')->toArray();
            $no = is_array($asignedSites) ? $asignedSites : [$asignedSites];
            $sites = $sites->whereNotIn('id', $no);
        }

        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $sites = $sites->orderBy($field_name , $request->sort_by_field);
        }
        $sites = $sites->paginate(20)->withPath('?sort_by_field_name='.$field_name.'&sort_by_field='.$asc_or_decs);
        return view('admin::studies.assign_sites',compact('sites','total_sites'));
    }

    public function updateStudySite(Request $request)
    {
        $textValue = trim($_POST['text_val']);
        $siteId    = $_POST['site_id'];
        $data      = array('study_site_id' => $textValue);
        StudySite::where('id',$siteId)->update($data);
        return response()->json(['success'=>'Study site is updated successfully!!!!']);
    }

    public function updatePrimaryInvestigator(Request $request)
    {
        $pi_id_value = $_POST['pi_id_value'];
        $id = (string)Str::uuid();
        $table_site_study_id   = $_POST['table_site_study_id'];
        $data      = array('primaryInvestigator_id' => $pi_id_value, 'id'   => $id);
        StudySite::where('id',$table_site_study_id)->update($data);
        return response()->json(['success'=>'Primary Investigator is updated successfully!']);
    }

    public function insertCoordinators(Request $request)
    {
        $table_site_study_id    = $_POST['table_site_study_id'];
        $coordinators = $_POST['coordinators_id'];
        foreach ($coordinators as $coordinator)
        {
            $row = SiteStudyCoordinator::where('coordinator_id',$coordinator)->delete();
            $result = SiteStudyCoordinator::create([
                'id'    => (string)Str::uuid(),
                'site_study_id' =>$table_site_study_id,
                'coordinator_id'=>$coordinator,
            ]);
        }
        return response()->json([$result,'success'=>'Coordinator is updated successfully!!!!']);
    }

    public function deleteSiteCoordinator(Request $request)
    {
        $coordinators = $_POST['coordinator_id'];
        $studySiteId  = trim($_POST['studySiteId']);
        $records  = SiteStudyCoordinator::where('site_study_id',$studySiteId)->get();

        foreach ($records as $record)
        {
            $coordinator = SiteStudyCoordinator::find($record->id);
            $coordinator->delete();
        }
        return response()->json(['success'=>'Coordinator is Deleted successfully!!!!']);
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
