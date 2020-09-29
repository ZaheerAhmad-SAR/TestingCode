<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\OptionsGroup;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\SiteStudyCoordinator;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Study;
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
                //$primaryArray[] = $primary->id.'/'. $primary->first_name.' '.$primary->last_name;
                $primaryArray[] = $primary->id.'/'. $primary->first_name;
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
//    public function update(Request $request)
//    {
//
//        $others = '';
//        $sites = $request->sites;
//        $current_study = session('current_study');
//        $result = StudySite::where('study_id', $current_study)->delete();
//        if (!empty($sites))
//        {
//            foreach ($sites as $site)
//            {
//                $exploadRecord = explode("_",$site);
//
//                if($exploadRecord[1]){
//
//                    $others = StudySite::create([
//                        'id'    => Str::uuid(),
//                        'site_id' =>$exploadRecord[0],
//                        'study_site_id'=>$exploadRecord[1],
//                        'study_id'=>session('current_study')
//
//                    ]);
//                }
//                else
//                {
//                    $others = StudySite::create([
//                        'id'    => Str::uuid(),
//                        'site_id' =>$exploadRecord[0],
//                        'study_id'=>session('current_study')
//                    ]);
//                }
//            }
//        }
//
//        return response()->json([$others]);
//        //return response()->json(['success'=>'Study site is updated successfully!!!!']);
//    }

    public function update(Request $request)
    {
        $others = '';
        $sites = $request->sites;
        $current_study = session('current_study');
        $study = Study::find($current_study);
        $study->studySites()->sync($sites);

        return response()->json([$sites]);
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
        $table_site_study_id   = $_POST['table_site_study_id'];
        $data      = array('primaryInvestigator_id' => $pi_id_value);
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
                'id'    => Str::uuid(),
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
