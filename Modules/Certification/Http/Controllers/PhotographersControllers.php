<?php

namespace Modules\Certification\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Certification\Entities\Photographers;
class PhotographersControllers extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // Photographers array
        $imaging_modality = DB::connection('mysql2')->table('imaging_modality')->get();
        $photographers = [];
        $photographers = DB::connection('mysql2')->table('photographer_data')->select('photographer_data.*', DB::Raw('CONCAT(first_name, " ", last_name) as photographer_name'), DB::Raw('GROUP_CONCAT(transmission_number SEPARATOR ",") as transmissions'),DB::Raw('GROUP_CONCAT(id SEPARATOR ",") as IDs'), DB::Raw('GROUP_CONCAT(status SEPARATOR ",") as statuses'),DB::Raw('GROUP_CONCAT(certification_officerName SEPARATOR ",") as certification_officerNames'));
        $photographers = $photographers->where('checked_by',0);
        if(isset($request->site) && $request->site !=''){
            $photographers = $photographers->where('photographer_data.site_id', $request->site);
        }
        if(isset($request->photographer_name) && $request->photographer_name !=''){
            $photographers = $photographers->where('photographer_data.first_name','like', $request->photographer_name);
        }
        if(isset($request->transmission_number) && $request->transmission_number !=''){
            $photographers = $photographers->where('photographer_data.transmission_number','like', $request->transmission_number);
        }
        if(isset($request->status) && $request->status !=''){
            $photographers = $photographers->where('photographer_data.certificate_status','like', $request->status);
        }
        $photographers = $photographers->groupBy('photographer_name')->groupBy('imaging_modality_req')->groupBy('study_name')->paginate(15);
        return view('certification::photographer.index', ['photographers' => $photographers,'imaging_modality' =>$imaging_modality]);
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
