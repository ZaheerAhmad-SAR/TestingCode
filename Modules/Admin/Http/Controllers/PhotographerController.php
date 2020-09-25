<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Other;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;
use Psy\Util\Str;

class PhotographerController extends Controller
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
        // return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = \Illuminate\Support\Str::uuid();
        $photographer = Photographer::create([
            'id'    => $id,
            'site_id'=> $request->site_id,
            'first_name' => $request->photographer_first_name,
            'mid_name' => empty($request->photographer_mid_name) ? Null : $request->photographer_mid_name,
            'last_name' => empty($request->photographer_last_name) ? Null : $request->photographer_last_name,
            'phone'=> empty($request->photographer_phone) ? Null : $request->photographer_phone,
            'email'=>empty($request->photographer_email)? Null : $request->photographer_email
        ]);

        $oldPhotographer = [];
        // log event details
        $logEventDetails = eventDetails($id, 'Photographer', 'Add', $request->ip(), $oldPhotographer);

        return response()->json([$photographer,'success'=>'Photographer is added successfully']);
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

            $record = Photographer::find($id);

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
        // get old data for logs
        $oldPhotographer = Photographer::find($request->photo_id);
        $data = array (
            'first_name' => $request->photographer_first_name,
            'mid_name' => $request->photographer_mid_name,
            'last_name' => $request->photographer_last_name,
            'email' => $request->photographer_email,
            'phone' => $request->photographer_phone
        );
        Photographer::where('id', $request->photo_id)->update($data);

        $photographer_site_id  = $request->photographer_site_id;

        $allphotographer    = Photographer::where('site_id',$photographer_site_id)->get();

         // log event details
        $logEventDetails = eventDetails($request->photo_id, 'Photographer', 'Update', $request->ip(), $oldPhotographer);

        return response()->json($allphotographer);


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
            $delete = Photographer::find($id);

            $delete->delete();
            return response()->json(['success'=>'Photographer is deleted successfully.']);
        }
    }

    public function showPhotographerBySiteId(Request $request,$id)
    {
        if ($request->ajax()) {
            $result    = Photographer::where('site_id',$id)->get();
            return response()->json([$result]);
        }
    }
}
