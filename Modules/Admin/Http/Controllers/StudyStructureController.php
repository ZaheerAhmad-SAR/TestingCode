<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;

class StudyStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $phases = StudyStructure::select('*')
                    ->with('phases')
                   ->orderBy('position', 'asc')
                   ->get();
        $steps = PhaseSteps::all();
        return view('admin::structure.index',compact('phases','steps'));
    }
    public function getallphases(Request $request)
    {
       $phases = StudyStructure::select('*')
                    ->with('phases')
                   ->orderBy('position', 'asc')
                   ->get();
        $phasesData['data'] = $phases;
        echo json_encode($phasesData);            
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
        $id    = Str::uuid();
        $phase = StudyStructure::create([
            'id'    => $id,
            'position'  =>  $request->position,
            'name' =>  $request->name,
            'duration' =>  $request->duration
        ]);
        $data = [
          'success' => true,
          'message'=> 'Recode added successfully'
        ] ;

        return response()->json($data);
    }

    public function store_steps(Request $request)
    {
        $id    = Str::uuid();
        $phase = PhaseSteps::create([
            'step_id'    => $id,
            'phase_id'    => $request->phase_id,
            'step_position'  =>  '1',
            'form_type' =>  $request->form_type,
            'step_name' =>  $request->step_name,
            'step_description' =>  $request->step_description,
            'graders_number' =>  $request->graders_number,
            'q_c' =>  $request->q_c,
            'eligibility' =>  $request->eligibility
        ]);
        $data = [
          'success' => true,
          'message'=> 'Recode added successfully'
        ] ;
    }
    public function update_steps(Request $request, $id='')
    {

        $phase = PhaseSteps::find($request->step_id);
        $phase->phase_id  =  $request->phase_id;
        $phase->step_position  =  '1';
        $phase->form_type  =  $request->form_type;
        $phase->step_name  =  $request->step_name;
        $phase->step_description  =  $request->step_description;
        $phase->graders_number  =  $request->graders_number;
        $phase->q_c  =  $request->q_c;
        $phase->eligibility  =  $request->eligibility;
        $phase->save();
        $data = [
          'success' => true,
          'message'=> 'Recode updated successfully'
        ] ;
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
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id='')
    {
        $phase = StudyStructure::find($request->id);
        $phase->position  =  $request->position;
        $phase->name  =  $request->name;
        $phase->duration  =  $request->duration;
        $phase->save();
    }

    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $phase = StudyStructure::find($id);
        $phase->delete($id);
    }
    public function destroySteps($step_id)
    {
        $steps = PhaseSteps::find($step_id);
        $steps->delete($step_id);
    }
}
