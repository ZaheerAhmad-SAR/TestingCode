<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\DeviceSite;
use Modules\Admin\Entities\Site;

class DeviceSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('admin::index');
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

        $id = (string)Str::uuid();
        $deviceSite = DeviceSite::create([

            'id'                      => $id,
            'site_id'                 => $request->site_id,
            'device_name'             => $request->device_name,
            'device_id'               => $request->masterListDeviceId,
            'device_serial_no'        => empty($request->device_serial_no) ? Null : $request->device_serial_no

            'device_software_version' => empty($request->device_software_version) ? Null : $request->device_software_version
        ]);
        return response()->json([$deviceSite,'success'=>'Device data is added successfully']);
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
        if ($id)
        {
            $record = DeviceSite::find($id);
            return response()->json([$record]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {

        $data = array(
            'device_name' => $request->device_name,
            'device_serial' => $request->device_serial,
            'device_software_version' => $request->device_software_version
        );
        DeviceSite::where('id', $request->device_id)->update($data);
        $result  = DeviceSite::where('site_id', $request->site_id)->get();
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request,$id)
    {
        if ($request->ajax())
        {
            $delete = DeviceSite::find($id);
            $delete->delete();
            return response()->json(['success'=>'Device Site is deleted successfully.']);
        }
    }

    // Show Devices by Site ID
    public function showDeviceBySiteId(Request $request,$id)
    {
        if ($request->ajax()) {
            $results    = DeviceSite::where('site_id',$id)->get();
            return view('admin::sites.device-sites',compact('results'));
        }
    }

    /// Check the Device serial and name if it exists
    public function deviceSerialValueIsExist(Request $request)
    {
        $data = array(
            'device_serial'=>$request->post('deviceSerial'),
            'device_name'=>$request->post('device_name')
        );
        $check = DeviceSite::where($data)->first();
        if ($check !== null)
        {
            return response()->json(['success'=>'Device Serial number already Exist']);
        }
    }
}
