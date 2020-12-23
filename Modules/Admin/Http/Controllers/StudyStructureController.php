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
                $formVersion = PhaseSteps::getFormVersion($step_value->step_id);
                if ($keys === 0) {
                    $active = 'display:block';
                } else {
                    $active = '';
                }

                $activateHtml = '<div id="activeStatusDiv_' . $step_value->step_id . '">';
                if ($step_value->is_active == 0) {
                    $activateHtml .= '<span class="dropdown-item activateStep" onclick="activateStep(\'' . $step_value->step_id . '\');"><i class="far fa-play-circle"></i>&nbsp; Put In Production Mode</span>';
                } else {
                    $activateHtml .= '<span class="dropdown-item inActivateStep" onclick="deActivateStep(\'' . $step_value->step_id . '\');"><i class="far fa-pause-circle"></i>&nbsp; Put In Draft Mode</span>';
                }
                $activateHtml .= '</div>';


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
                            <p class="mail-subject">' . $step_value->step_description . ' - Form version:<span id="formVersionSpan_' . $step_value->step_id . '">' . $formVersion . '</span>.</p>
                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                <div class="ml-md-auto mr-3 dot primary"></div>
                                <p class="ml-auto mail-date mb-0">' . $step_value->created_at . '</p>
                                <a href="#" class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-cog"></i></a>
                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                    <span class="dropdown-item edit_steps"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                    <span class="dropdown-item addsection"><i class="far fa-file-code"></i>&nbsp; Add Section</span>
                                    <span class="dropdown-item cloneStep"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                    ' . $activateHtml . '
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
                                <span class="dropdown-item clonePhase"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                <span class="dropdown-item deletePhase"><i class="far fa-trash-alt"></i>&nbsp; Delete</span>
                            </div>
                        </div>
                      </div>
                    </div>
                </li>';
        }
        return Response($html);
    }
    // Store phases here
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

        $oldPhase = [];

        // log event details
        $logEventDetails = eventDetails($id, 'Phase', 'Add', $request->ip(), $oldPhase);

        $data = [
            'success' => true,
            'message' => 'Recode added successfully'
        ];

        return response()->json($data);
    }
    // store steps here
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

        $oldStep = [];

        // log event details
        $logEventDetails = eventDetails($id, 'Step', 'Add', $request->ip(), $oldStep);

        /************************* */
        $step = PhaseSteps::find($id);
        $this->addStepToReplicatedVisits($step, true);

        $data = [
            'success' => true,
            'message' => 'Recode added successfully'
        ];
    }
    // Update steps here
    public function update_steps(Request $request, $id = '')
    {
        $oldStep = PhaseSteps::where('step_id', $request->step_id)->first();


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

        // log event details
        $logEventDetails = eventDetails($request->step_id, 'Step', 'Update', $request->ip(), $oldStep);


        $this->updateStepToReplicatedVisits($step);

        $data = [
            'success' => true,
            'message' => 'Recode updated successfully'
        ];
    }
    // Update Phase here
    public function update(Request $request, $id = '')
    {
        // old phase
        $oldPhase = StudyStructure::where('id', $request->id)->first();

        $phase = StudyStructure::find($request->id);
        $phase->position  =  $request->position;
        $phase->name  =  $request->name;
        $phase->duration  =  $request->duration;
        $phase->is_repeatable  =  $request->is_repeatable;
        $phase->save();

        // log event details
        $logEventDetails = eventDetails($phase->id, 'Phase', 'Update', $request->ip(), $oldPhase);

        $this->updatePhaseToReplicatedVisits($phase);
    }
    // Delete Phase here
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

    public function activateStep(Request $request, $step_id)
    {
        $default_data_option = $request->default_data_option;
        $step = PhaseSteps::find($step_id);
        $this->activateStepToReplicatedVisits($step, $default_data_option);
    }

    public function deActivateStep($step_id)
    {
        $step = PhaseSteps::find($step_id);
        $this->deActivateStepToReplicatedVisits($step);
    }
}
