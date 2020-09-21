<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\TrailLog;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $sites = Site::paginate(20);
        $site = Site::latest('created_at')->first();
        $photographers = Photographer::all();
        $coordinators = Coordinator::all();
        $pinvestigators = PrimaryInvestigator::all();
        return view('admin::sites.index',compact('sites','photographers','pinvestigators','coordinators','site'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::sites.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'site_code:required|unique',
            'site_name:required',
            'site_country:required',
            'site_address:required',
            'site_city:required',
            'site_state:required',
            'site_phone:required|numeric|max:15',
            'site_email:required|email',
        ]);
        $id = Str::uuid();
        if (Site::where('site_code', $request->site_code)->exists()) {

            return response()->json(['code'=>'Code must be unique']);
        }
        else
        {
            $site = Site::create([
                'id'    => $id,
                'site_code'=> empty($request->site_code) ? Null : $request->site_code,
                'site_name' => empty($request->site_name) ? Null : $request->site_name,
                'site_country'=> empty($request->country) ? Null : $request->country,
                'site_address'=>empty($request->fullAddr)? Null : $request->fullAddr,
                'site_city'=> empty($request->locality) ? Null : $request->locality,
                'site_state'=> empty($request->administrative_area_level_1)? Null : $request->administrative_area_level_1,
                'site_phone'=> empty($request->site_phone) ? Null : $request->site_phone,
                'site_email'=>empty($request->site_email)? Null : $request->site_email
            ]);
           
            $new_site = Site::select('id')->latest()->first();

        // get event details
        $getEventDetails = eventDetails($id, 'Site');

        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $id;
        $trailLog->event_type = 'Add';
        $trailLog->event_message = \Auth::user()->name.' added site '.$request->site_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $request->ip();
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($getEventDetails);
        $trailLog->save();

        return response()->json(['success'=>'Site Info is added successfully']);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Site $site)
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
            $record = Site::find($id);
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
        $data = array(
            'site_code'=> empty($request->site_code) ? Null : $request->site_code,
            'site_name' => empty($request->site_name) ? Null : $request->site_name,
            'site_country'=> empty($request->country) ? Null : $request->country,
            'site_address'=>empty($request->fullAddr)? Null : $request->fullAddr,
            'site_city'=> empty($request->locality) ? Null : $request->locality,
            'site_state'=> empty($request->administrative_area_level_1)? Null : $request->administrative_area_level_1,
            'site_phone'=> empty($request->site_phone) ? Null : $request->site_phone
        );
        
        Site::where('id', $request->site_id)->update($data);

         // get event details
        $getEventDetails = eventDetails($request->site_id, 'Site');

        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $request->site_id;
        $trailLog->event_type = 'Update';
        $trailLog->event_message = \Auth::user()->name.' updtaed site '.$request->site_name.'.';
        $trailLog->user_id = \Auth::user()->id;
        $trailLog->user_name = \Auth::user()->name;
        $trailLog->role_id = \Auth::user()->role_id;
        $trailLog->ip_address = $request->ip();
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = url('sites');
        $trailLog->event_details = json_encode($getEventDetails);
        $trailLog->save();
        return response()->json(['success'=>'Site Info is updated successfully']);

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
            $delete = Site::find($id);

            $delete->delete();
            return response()->json(['success'=>'Site is deleted successfully.']);
        }
    }
}
