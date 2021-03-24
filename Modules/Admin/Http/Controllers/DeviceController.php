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
    public function index(Request $request)
    {
      // for default orderBy
        if(isset($request->sort_by_field_name) && $request->sort_by_field_name !=''){
            $field_name = $request->sort_by_field_name;
        }else{
            $field_name = 'device_name';
        }

        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $asc_or_decs = $request->sort_by_field;
        }else{
            $asc_or_decs = 'ASC';
        }
        // Devices Query
        $devices = Device::query();
        if($request->device_name != '') {
            $devices = $devices->where('device_name','like', '%'.$request->device_name.'%');
        }
        if($request->device_model != '') {
            $devices = $devices->where('device_model','like', '%'.$request->device_model.'%');
        }
        if($request->device_manufacturer != '') {
            $devices = $devices->where('device_manufacturer','like', '%'.$request->device_manufacturer.'%');
        }
        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $devices = $devices->orderBy($field_name , $request->sort_by_field);
        }
        $devices = $devices->paginate(\Auth::user()->user_prefrences->default_pagination)
                           ->withPath('?sort_by_field_name='.$field_name.'&sort_by_field='.$asc_or_decs); 
        $modilities = Modility::all();
        return view('admin::devices.index',compact('devices','modilities'));
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

            /************************************** Edit Case ***********************************/
            if($deviceID  != '') {
                 // check for device model
                $getDeviceModel = Device::where('id', '!=', $deviceID)
                                        ->where('device_model', $request->device_model)
                                        ->where('device_manufacturer', $request->device_manufacturer)
                                        ->first();
                // check for duplicate device
                if($getDeviceModel != null) {
                    // return response for errors
                    return response()->json(['error' => true]);
                } else {

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
                                'id'    => (string)Str::uuid(),
                                'device_id'     => $deviceID,
                                'modility_id'   => $modality

                            ]);
                        } // foreach ends
                    } // modalities null check

                    // log event details
                    $logEventDetails = eventDetails($deviceID, 'Device', 'Update', $request->ip(), $oldDevice);
                    // return success message
                    return response()->json(['success' => true, 'device' => $device]);
                } // duplicate device check ends
            } else {

                // check for device model
                $getDeviceModel = Device::where('device_model', $request->device_model)
                                        ->where('device_manufacturer', $request->device_manufacturer)
                                        ->first();
                // check for duplicate device
                if($getDeviceModel != null) {
                    // return response for errors
                    return response()->json(['error' => true]);
                } else {
                    $id = (string)Str::uuid();
                    $device = new Device;
                    $device->id = $id;
                    $device->device_name = $request->device_name;
                    $device->device_model = $request->device_model;
                    $device->device_manufacturer = $request->device_manufacturer;
                    $device->save();

                    if ($request->modalities != null) {
                        foreach ($request->modalities as $modality) {
                            DeviceModility::create([
                                'id'    => (string)Str::uuid(),
                                'device_id'     => $device->id,
                                'modility_id'   => $modality

                            ]);
                        } // foreach ends
                    } // modalities array check
                    $oldDevice = [];
                    // log event details
                    $logEventDetails = eventDetails($id, 'Device', 'Add', $request->ip(), $oldDevice);
                    // return success message
                    return response()->json(['success' => true, 'device' => $device]);
                } // duplicate device check ends
            } // Add/Edit check
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

    // public function createTransmissionDevice(Request $request) {
    //     if($request->ajax()) {
    //         // check for device model
    //         $getDeviceModel = Device::where('device_model', $request->device_model)
    //                                 ->orWhere('device_manufacturer', $request->device_manufacturer)
    //                                 ->first();

    //         if($getDeviceModel != null) {
    //             return response()->json(['error' => true]);
    //         } else {
    //             // insert data into data base
    //             $id = (string)Str::uuid();

    //             $device = new Device;
    //             $device->id = $id;
    //             $device->device_name = $request->device_name;
    //             $device->device_model = $request->device_model;
    //             $device->device_manufacturer = $request->device_manufacturer;
    //             $device->save();

    //             $oldDevice = [];

    //             // log event details
    //             $logEventDetails = eventDetails($id, 'Device', 'Add', $request->ip(), $oldDevice);

    //             return response()->json(['success' => true, 'device' => $device]);

    //         }// duplicate device model
    //     } // ajax ends
    // }

}
