<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;

class CoordinatorController extends Controller
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
        //dd($request->all());
        $coordinator = Coordinator::create([
            'id'    => Str::uuid(),
            'site_id'=> $request->site_id,
            'first_name' => $request->c_first_name,
            'mid_name' => empty($request->c_mid_name) ? Null : $request->c_mid_name,
            'last_name' => empty($request->c_last_name) ? Null : $request->c_last_name,
            'phone'=> empty($request->c_phone) ? Null : $request->c_phone,
            'email'=>empty($request->c_email)? Null : $request->c_email
        ]);




        return response()->json([$coordinator,'success'=>'Coordinator is added successfully!!!!']);
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

        if ($id) {

            $record = Coordinator::find($id);

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
            'first_name' => $request->c_first_name,
            'mid_name' => $request->c_mid_name,
            'last_name' => $request->c_last_name,
            'email' => $request->c_email,
            'phone' => $request->c_phone
        );
        Coordinator::where('id', $request->c_id)->update($data);

        $c_site_id  = $request->c_site_id;

        $allCoordinator    = Coordinator::where('site_id',$c_site_id)->get();

        return response()->json($allCoordinator);


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
            $delete = Coordinator::find($id);

            $delete->delete();
            return response()->json(['success'=>'Coordinator is deleted successfully.']);
        }
    }

    public function showCoordinatorBySiteId(Request $request,$id)
    {

        if ($request->ajax()) {

            $result    = Coordinator::where('site_id',$id)->get();
            return response()->json([$result]);

        }

    }

}
