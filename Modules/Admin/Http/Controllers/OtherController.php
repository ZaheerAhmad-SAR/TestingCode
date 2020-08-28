<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\Site;

class OtherController extends Controller
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
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $site = Site::select('id')->latest()->first();

        $others = Other::create([
            'id'    => Str::uuid(),
            'site_id'=> $site->id,
            'first_name' => $request->others_first_name,
            'mid_name' => empty($request->others_mid_name) ? Null : $request->others_mid_name,
            'last_name' => empty($request->others_last_name) ? Null : $request->others_last_name,
            'phone'=> empty($request->others_phone) ? Null : $request->others_phone,
            'email'=>empty($request->others_email)? Null : $request->others_email
        ]);

        return response()->json([$others,'success'=>'others data is added successfully']);
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

            $record = Other::find($id);

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
            'first_name' => $request->others_first_name,
            'mid_name' => $request->others_mid_name,
            'last_name' => $request->others_last_name,
            'email' => $request->others_email,
            'phone' => $request->others_phone
        );
        Other::where('id', $request->others_id)->update($data);

        $others_site_id  = $request->others_site_id;

        $allOthers    = Other::where('site_id',$others_site_id)->get();

        return response()->json($allOthers);


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
            $delete = Other::find($id);

            $delete->delete();
            return response()->json(['success'=>'Others is deleted successfully.']);
        }
    }
}
