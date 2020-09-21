<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\ChildModilities;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Section;
use Modules\Admin\Entities\Site;

class PrimaryInvestigatorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {


        //$site = Site::select('id')->latest()->first();
        $pi = PrimaryInvestigator::create([
            'id'    => Str::uuid(),
            'site_id'=> $request->site_id,
            'first_name' => $request->pi_first_name,
            'mid_name' => empty($request->pi_mid_name) ? Null : $request->pi_mid_name,
            'last_name' => empty($request->pi_last_name) ? Null : $request->pi_last_name,
            'phone'=> empty($request->pi_phone) ? Null : $request->pi_phone,
            'email'=>empty($request->pi_email)? Null : $request->pi_email
        ]);

        return response()->json([$pi,'success'=>'Primary Investigator is added successfully']);

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
    public function edit(Request $request,$id)
    {
        if ($request->ajax()) {
            $record = PrimaryInvestigator::find($id);
            return response()->json([$record]);
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $data = array (
            'first_name' => $request->pi_first_name,
            'mid_name' => $request->pi_mid_name,
            'last_name' => $request->pi_last_name,
            'email' => $request->pi_email,
            'phone' => $request->pi_phone
        );

        PrimaryInvestigator::where('id', $request->pi_id)->update($data);
        $site_id  = $request->pi_site_id;

        $allPi    = PrimaryInvestigator::where('site_id',$site_id)->get();


        return response()->json($allPi);


    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$id)
    {
        if ($request->ajax())
        {
            $delete = PrimaryInvestigator::find($id);

            $delete->delete();
            return response()->json(['success'=>'Primary Investigator is deleted successfully.']);
        }
    }


    public function showSiteId(Request $request,$id)
    {
        if ($request->ajax()) {
            $allPi    = PrimaryInvestigator::where('site_id',$id)->get();
            return response()->json([$allPi]);
        }
    }
}
