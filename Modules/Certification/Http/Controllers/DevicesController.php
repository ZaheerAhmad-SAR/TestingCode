<?php

namespace Modules\Certification\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Certification\Entities\Devices;
class DevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $devices = [];
        $devices = DB::connection('mysql2')->table('certify_device')->select('certify_device.*', DB::Raw('GROUP_CONCAT(trans_no SEPARATOR ",") as transmissions'), DB::Raw('GROUP_CONCAT(c_id SEPARATOR ",") as IDs'),DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'),DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'));
        if(isset($request->status) && $request->status !=''){
            $devices = $devices->where('certify_device.status', $request->status);
        }
        if(isset($request->device_manf) && $request->device_manf !=''){
            $devices = $devices->where('certify_device.device_manf','like', $request->device_manf);
        }
        if(isset($request->device_sn) && $request->device_sn !=''){
            $devices = $devices->where('certify_device.device_sn', $request->device_sn);
        }
        if(isset($request->device_model) && $request->device_model !=''){
            $devices = $devices->where('certify_device.device_model', $request->device_model);
        }
        if(isset($request->cert_issueDate) && $request->cert_issueDate !=''){
            $devices = $devices->where('certify_device.cert_issueDate', $request->cert_issueDate);
        }
        $devices = $devices->groupBy('certify_device.device_categ')->paginate(15);
        return view('certification::devices.index', ['devices' => $devices]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('certification::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('certification::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('certification::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
