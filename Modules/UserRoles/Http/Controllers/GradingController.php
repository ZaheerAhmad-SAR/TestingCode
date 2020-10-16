<?php

namespace Modules\UserRoles\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\SubjectsPhases;
use Modules\Admin\Entities\FormStatus;
use DB;

class GradingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // $subjects = DB::table('subjects')
        //                 ->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'sites.site_name')
        //                 ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
        //                 ->crossJoin('study_structures')
        //                 ->orderBy('subjects.subject_id')
        //                 ->orderBy('study_structures.position')
        //                 ->paginate(15);

        //$subject = SubjectsPhases::get();

        $subjects = Subject::select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'sites.site_name')
        ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
        ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
        ->leftJoin('sites', 'sites.id', 'subjects.site_id')
        ->orderBy('subjects.subject_id')
        ->orderBy('study_structures.position')
        ->paginate(15);

        // get modalities
        $getModilities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name','modilities.id as modility_id', 'modilities.modility_name')
        ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
        ->groupBy('phase_steps.modility_id')
        ->orderBy('modilities.modility_name')
        ->get();

        // modility/form type array
        $modalitySteps = [];

        // get form types for modality
        foreach($getModilities as $key => $modility) {
            
            $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                                    ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                                    ->where('modility_id', $modility->modility_id)
                                    ->orderBy('form_types.sort_order')
                                    ->groupBy('phase_steps.form_type_id')
                                    ->get()->toArray();
            
            $modalitySteps[$modility->modility_name] = $getSteps;
        }

        //get form status depending upon subject, phase and modality
        if ($modalitySteps != null) {
            foreach($subjects as $subject) {
                //get status
                $formStatus = [];

                // modality loop
                foreach($modalitySteps as $key => $formType) {

                    // form type loop
                    foreach($formType as $type) {
                        
                        // $getFormStatus = FormStatus::select('form_submit_status.form_status')
                        //                 ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                        //                 ->where('form_submit_status.subject_id', $subject->id)
                        //                 ->where('form_submit_status.study_structures_id', $subject->phase_id)
                        //                 ->where('form_submit_status.form_type_id', $type['form_type_id'])
                        //                 ->where('phase_steps.modility_id', $type['modility_id'])
                        //                 ->first();

                        // $formStatus[$key.'_'.$type['form_type']] = $getFormStatus == null ? 'no_status' : $getFormStatus->form_status;

                        $step = PhaseSteps::where('step_id', $type['step_id'])->first();

                            $getFormStatusArray = [
                                'subject_id' => $subject->id,
                                'study_structures_id' => $subject->phase_id,
                                'modility_id'=> $type['modility_id'],
                                'form_type_id' => $type['form_type_id']
                            ];

                            
                            if ($step->form_type_id == 2) {

                                $formStatus[$key.'_'.$type['form_type']] =  \Modules\Admin\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray);
                            } else {

                                $formStatus[$key.'_'.$type['form_type']] =  \Modules\Admin\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true);
                            }
                        
                    } // step lopp ends

                } // modality loop ends
                // dd($formStatus);
                $subject->form_status = $formStatus;
                // echo '<pre>';
                // print_r($subject->form_status);

            }
        }

        return view('userroles::users.grading-list', compact('subjects', 'modalitySteps'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('userroles::create');
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
        return view('userroles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('userroles::edit');
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
