<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\StudySite;
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
        //dd($sites);

        foreach ($sites as $site)
        {
            $siteArray[] = $site->site_id;

        }

        $unassignSites = Site::select('sites.*')
            ->whereNotIn('sites.id', $siteArray)->get();
        return view('admin::studies.study_site',compact('sites','unassignSites'));
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
        $inputIdValues = $_POST['InputIdValues'];
        $inputValues   = $_POST['InputValues'];
        $i=0;
       foreach($inputIdValues as $idValue )
        {
            $data = array('study_site_id' => $inputValues[$i]);
            StudySite::where('id',$idValue)->update($data);
            $lastRecord = StudySite::get();
            $i++;

        }
        return response()->json([$lastRecord,'success'=>'Study site is updated successfully!!!!']);
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
