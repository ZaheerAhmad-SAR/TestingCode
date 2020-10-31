<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\Modility;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class StudyStructureController extends Controller
{
    use ReplicatePhaseStructure;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $query = StudyStructure::select('*');
        $query->with('phases', 'study');
        if (null !== session('current_study') && !empty(session('current_study'))) {
            $query->where('study_id', session('current_study'));
        }
        $query->orderBy('position', 'asc');
        $phases = $query->get();
        $steps = PhaseSteps::all();
        $formTypes = FormType::all();
        $modalities = Modility::all();
        return view('admin::structure.index', compact('phases', 'steps', 'formTypes', 'modalities'));
    }
    public function get_steps()
    {
        $query = StudyStructure::select('*');
        $query->with('phases', 'study');
        if (null !== session('current_study') && !empty(session('current_study'))) {
            $query->where('study_id', session('current_study'));
        }
        $query->orderBy('position', 'asc');
        $phases = $query->get();
        $html = '';
        foreach ($phases as $keys => $phase) {
            foreach ($phase->phases as $key => $step_value) {
                if ($keys === 0) {
                    $active = 'display:block';
                } else {
                    $active = '';
                }
                $html .= '<li class="py-3 px-2 mail-item tab_' . $step_value->phase_id . '" style="' . $active . '">
                    <input type="hidden" class="step_id" value="' . $step_value->step_id . '">
                    <input type="hidden" class="step_phase_id" value="' . $step_value->phase_id . '">
                    <input type="hidden" class="form_type_id" value="' . $step_value->form_type_id . '">
                    <input type="hidden" class="modility_id" value="' . $step_value->modility_id . '">
                    <input type="hidden" class="step_name" value="' . $step_value->step_name . '">
                    <input type="hidden" class="step_position" value="' . $step_value->step_position . '">
                    <input type="hidden" class="step_description" value="' . $step_value->step_description . '">
                    <input type="hidden" class="graders_number" value="' . $step_value->graders_number . '">
                    <input type="hidden" class="q_c" value="' . $step_value->q_c . '">
                    <input type="hidden" class="eligibility" value="' . $step_value->eligibility . '">
                    <div class="d-flex align-self-center align-middle">
                        <div class="mail-content d-md-flex w-100">
                            <span class="mail-user">' . $step_value->step_position . '. ' . $step_value->formType->form_type . ' - ' . $step_value->step_name . '</span>
                            <p class="mail-subject">' . $step_value->step_description . '.</p>
                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                <div class="ml-md-auto mr-3 dot primary"></div>
                                <p class="ml-auto mail-date mb-0">' . $step_value->created_at . '</p>
                                <a href="#" class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                    <span class="dropdown-item edit_steps"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                    <span class="dropdown-item addsection"><i class="far fa-file-code"></i>&nbsp; Add Section</span>
                                    <span class="dropdown-item"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                    <span class="dropdown-item deleteStep"><i class="far fa-trash-alt"></i>&nbsp; Delete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>';
            }
        }

        return Response($html);
    }
    public function getallphases(Request $request)
    {
        $query = StudyStructure::select('*');
        $query->with('phases', 'study');
        if (null !== session('current_study') && !empty(session('current_study'))) {
            $query->where('study_id', session('current_study'));
        }
        $query->orderBy('position', 'asc');
        $phases = $query->get();
        $html = '';
        foreach ($phases as $key => $phase) {
            if ($key == 0) {
                $active_phase = ' active';
            } else {
                $active_phase = '';
            }
            $html .= '<li class="nav-item mail-item" style="border-bottom: 1px solid #F6F6F7;"><div class="d-flex align-self-center align-middle"><div class="mail-content d-md-flex w-100"><a href="#" data-mailtype="tab_' . $phase->id . '" class="nav-link' . $active_phase . '"><span class="mail-user"> ' . $phase->position . ' . ' . $phase->name . ' </span></a><input type="hidden" class="phase_id" value="' . $phase->id . '">
                        <input type="hidden" class="phase_study_id" value="' . $phase->study_id . '">
                        <input type="hidden" class="phase_name" value="' . $phase->name . '">
                        <input type="hidden" class="phase_position" value="' . $phase->position . '">
                        <input type="hidden" class="phase_duration" value="' . $phase->duration . '">
                        <div class="d-flex mt-3 mt-md-0 ml-auto">
                            <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                <span class="dropdown-item edit_phase"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                <span class="dropdown-item"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                <span class="dropdown-item deletePhase"><i class="far fa-trash-alt"></i>&nbsp; Delete</span>
                            </div>
                        </div>
                      </div>
                    </div>
                </li>';
        }
        return Response($html);
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
            'study_id'    => session('current_study'),
            'position'  =>  $request->position,
            'name' =>  $request->name,
            'duration' =>  $request->duration,
            'is_repeatable' =>  $request->is_repeatable,
        ]);
        $data = [
            'success' => true,
            'message' => 'Recode added successfully'
        ];

        return response()->json($data);
    }

    public function store_steps(Request $request)
    {
        $id    = Str::uuid();
        PhaseSteps::create([
            'step_id'    => $id,
            'phase_id'    => $request->phase_id,
            'step_position'  =>  $request->step_position,
            'form_type_id' =>  $request->form_type_id,
            'modility_id' =>  $request->modility_id,
            'step_name' =>  $request->step_name,
            'step_description' =>  $request->step_description,
            'graders_number' =>  $request->graders_number,
            'q_c' =>  $request->q_c,
            'eligibility' =>  $request->eligibility
        ]);
        /************************* */
        $step = PhaseSteps::find($id);
        $this->addStepToReplicatedVisits($step);

        $data = [
            'success' => true,
            'message' => 'Recode added successfully'
        ];
    }
    public function update_steps(Request $request, $id = '')
    {
        $step = PhaseSteps::find($request->step_id);
        $step->phase_id  =  $request->phase_id;
        $step->step_position  =  $request->step_position;
        $step->form_type_id  =  $request->form_type_id;
        $step->modility_id  =  $request->modility_id;
        $step->step_name  =  $request->step_name;
        $step->step_description  =  $request->step_description;
        $step->graders_number  =  $request->graders_number;
        $step->q_c  =  $request->q_c;
        $step->eligibility  =  $request->eligibility;
        $step->save();


        $this->updateStepToReplicatedVisits($step);

        $data = [
            'success' => true,
            'message' => 'Recode updated successfully'
        ];
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
    public function update(Request $request, $id = '')
    {
        $phase = StudyStructure::find($request->id);
        $phase->position  =  $request->position;
        $phase->name  =  $request->name;
        $phase->duration  =  $request->duration;
        $phase->is_repeatable  =  $request->is_repeatable;
        $phase->save();

        $this->updatePhaseToReplicatedVisits($phase);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $phase = StudyStructure::find($id);
        $this->deletePhase($phase);
    }
    public function destroySteps($step_id)
    {
        $step = PhaseSteps::find($step_id);
        $this->deleteStep($step);
    }
}
