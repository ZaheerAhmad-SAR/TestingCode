<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Device;
use Modules\Admin\Entities\DeviceModility;
use Modules\Admin\Entities\DeviceSite;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\StudySite;


class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $devices = Device::orderBy('id','desc')->paginate(8);
        $modilities = Modility::all();

        return view('admin::devices.index',compact('devices','modilities'));

        /*        $devices = Device::paginate(20);
                $modilities = Modility::all();
                return view('admin::devices.index',compact('devices','modilities'));*/
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $sites = Site::all();
        $modilities = Modility::all();
        return view('admin::devices.create',compact('sites','modilities'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {

            $deviceID = $request->device_id;
            
            if($deviceID  != '') {

                // get old device data
                $oldDevice = Device::where('id', $deviceID)->first();

                $device = Device::find($deviceID);
                $device->device_name = $request->device_name;
                $device->device_model = $request->device_model;
                $device->device_manufacturer = $request->device_manufacturer;
                $device->save();

                if ($request->modalities != null) {
                    // delete old modalities
                    $deleteModalities = DeviceModility::where('device_id', $deviceID)->delete();

                    foreach ($request->modalities as $modality) {
                        DeviceModility::create([
                            'id'    => Str::uuid(),
                            'device_id'     => $deviceID,
                            'modility_id'   => $modality

                        ]);
                    } // foreach ends
                } // modalities null check

                 // log event details
                $logEventDetails = eventDetails($deviceID, 'Device', 'Update', $request->ip(), $oldDevice);

            } else {

                $id = Str::uuid();

                $device = new Device;
                $device->id = $id;
                $device->device_name = $request->device_name;
                $device->device_model = $request->device_model;
                $device->device_manufacturer = $request->device_manufacturer;
                $device->save();

                if ($request->modalities != null) {
                    foreach ($request->modalities as $modality) {
                        DeviceModility::create([
                            'id'    => Str::uuid(),
                            'device_id'     => $device->id,
                            'modility_id'   => $modality

                        ]);
                    } // foreach ends
                } // modalities array check

                $oldDevice = [];

                // log event details
                $logEventDetails = eventDetails($id, 'Device', 'Add', $request->ip(), $oldDevice);

            } // get device check ends
            return \response()->json($device);
        } // ajax ends
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Device $device)
    {
        $device->delete();

        return response()->json(['success'=>'Device deleted successfully.']);
       /* return view('admin::devices.index');*/
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $device  = Device::with('modalities')->where($where)->first();

        return \response()->json($device);

        /*$sites = Site::all();
        $modilities = Modility::all();
        return view('admin::devices.edit',compact('device','sites','modilities'));*/
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Device $device)
    {
        $device->update($request->all());

        return redirect()->route('devices.index');

    }

    public function getModal($id)
    {
        $device = Device::where('id',$id)->first();
        return view('devices.modal',['device'=>$device]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        dd('delete');
        $device = Device::where('id',$id)->delete();

        return redirect()->route('devices.index');
    }
}
