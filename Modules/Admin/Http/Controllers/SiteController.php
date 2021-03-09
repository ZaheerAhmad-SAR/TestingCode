<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Coordinator;
use Modules\Admin\Entities\Photographer;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\PrimaryInvestigator;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\TrailLog;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
       
    
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
        $sites = Site::query();
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
        if ($request->site_phone != '') {
            $sites = $sites->where('site_phone','like', '%'.$request->site_phone.'%');
        }
        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $sites = $sites->orderBy($field_name , $request->sort_by_field);
        }
        $sites                = $sites->paginate(20)->withPath('?sort_by_field_name='.$field_name.'&sort_by_field='.$asc_or_decs);
        $siteForTransmissions = Site::all();
        $photographers        = Photographer::all();
        $devices              = Device::all();
        $coordinators         = Coordinator::all();
        $pinvestigators       = PrimaryInvestigator::all();
        $old_values           = $request->input();
        return view('admin::sites.index',compact('sites','photographers','pinvestigators','coordinators','siteForTransmissions','old_values','devices'));
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
//        $request->validate([
//            'site_code:required|unique',
//            'site_name:required',
//            'site_country:required',
//            'site_address:required',
//            'site_city:required',
//            'site_state:required',
//            'site_phone:required|numeric|max:15',
//            'site_email:required|email',
//        ]);

        $id = (string)Str::uuid();
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

            $oldSite = [];

            // log event details
            $logEventDetails = eventDetails($id, 'Site', 'Add', $request->ip(), $oldSite);

        return response()->json(['site_id' => $id,'success'=>'Site Info is added successfully']);
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

        // get old site data for logs
        $oldSite = Site::find($request->lastSiteId);

        $data = array(
            'site_code'=> empty($request->site_code) ? Null : $request->site_code,
            'site_name' => empty($request->site_name) ? Null : $request->site_name,
            'site_country'=> empty($request->country) ? Null : $request->country,
            'site_address'=>empty($request->fullAddr)? Null : $request->fullAddr,
            'site_city'=> empty($request->locality) ? Null : $request->locality,
            'site_state'=> empty($request->administrative_area_level_1)? Null : $request->administrative_area_level_1,
            'site_phone'=> empty($request->site_phone) ? Null : $request->site_phone,
        );

        Site::where('id', $request->lastSiteId)->update($data);

        // log event details
        $logEventDetails = eventDetails($request->lastSiteId, 'Site', 'Update', $request->ip(), $oldSite);

        return response()->json(['success'=>'Site Info is updated successfully']);

    }

    public function checkIfSiteIsExist(Request $request)
    {
        if (Site::where('site_code', $request->post('siteCode'))->first()) {
            return response()->json(['success'=>'Site Code already Exist']);
        }
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
