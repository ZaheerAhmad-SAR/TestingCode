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
            //dd($request->all());
            $deviceID = $request->post_id;
            $id = Str::uuid();

            $device = Device::updateOrCreate([
                'id' => $id],
                ['device_name' => $request->device_name,
                    'device_model' => $request->device_model,
                    'device_manufacturer' => $request->device_manufacturer
                ]);
            foreach ($request->modalities as $modality){
                DeviceModility::updateOrCreate([
                    'id'    => Str::uuid(),
                    'device_id'     => $device->id,
                    'modility_id'   => $modality

                ]);
            }
        }
        return \response()->json($device);

        dd($request->all());
        $request->validate([
            'device_name:required',
            'device_manufacturer:required',
            'device_model:required'
        ]);
        $id = Str::uuid();

        $device = Device::create([
            'id'    => $id,
            'device_name' => $request->device_name,
            'device_manufacturer' => $request->device_manufacturer,
            'device_model'=> $request->device_model
        ]);

        foreach ($request->modalities as $modility){
            DeviceModility::create([
                'id'    => Str::uuid(),
                'device_id'     => $device->id,
                'modility_id'   => $modility
            ]);
        }

        return redirect()->route('devices.index')->with('success','Device created');
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
