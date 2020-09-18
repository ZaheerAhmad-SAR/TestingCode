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
use Modules\Admin\Entities\StudySite;
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
            $coordinators          = Coordinator::where('site_id',$site->site_id)->get();
            $primaryArray = array();
            $coordinatorArray = array();
            foreach ($primaryInvestigator as $primary)
            {
                //$primaryArray[] = $primary->id.'/'. $primary->first_name.' '.$primary->last_name;
                $primaryArray[] = $primary->id.'/'. $primary->first_name;
            }
            $site->pi=$primaryArray;
            foreach ($coordinators as $coordinator)
            {
            //$coordinatorArray[] = $coordinator->id.'/'. $coordinator->first_name.' '.$coordinator->last_name;
            $coordinatorArray[] = $coordinator->id.'/'. $coordinator->first_name;
            }
            $site->ci = $coordinatorArray;

        }

        $unassignSites = Site::select('sites.*')
            ->whereNotIn('sites.id', $siteArray)->get();
        return view('admin::studies.studySiteNew',compact('sites','unassignSites','records'));
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

        $sites = $request->sites;
        $current_study = session('current_study');
        $result = StudySite::where('study_id', $current_study)->delete();
        foreach ($sites as $site)
        {
            $exploadRecord = explode("_",$site);
            if($exploadRecord[1]){
                $others = StudySite::create([
                    'id'    => Str::uuid(),
                    'site_id' =>$exploadRecord[0],
                    'study_site_id'=>$exploadRecord[1],
                    'study_id'=>session('current_study')

                ]);
            }
            else
            {
                $others = StudySite::create([
                    'id'    => Str::uuid(),
                    'site_id' =>$exploadRecord[0],
                    'study_id'=>session('current_study')
                ]);
            }
        }
        return response()->json([$others]);
        //return response()->json(['success'=>'Study site is updated successfully!!!!']);
    }

    public function updateStudySite(Request $request)
    {
        $textValue = trim($_POST['text_val']);
        $siteId    = $_POST['site_id'];
        $data      = array('study_site_id' => $textValue);
        StudySite::where('id',$siteId)->update($data);
        return response()->json(['success'=>'Study site is updated successfully!!!!']);
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
