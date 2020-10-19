<?php

namespace Modules\UserRoles\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\SubjectsPhases;
use Modules\Admin\Entities\FormStatus;
use Modules\Admin\Entities\FormType;
use DB;
use Carbon\Carbon;

class GradingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        // $subjects = DB::table('subjects')
        //                 ->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'sites.site_name')
        //                 ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
        //                 ->crossJoin('study_structures')
        //                 ->orderBy('subjects.subject_id')
        //                 ->orderBy('study_structures.position')
        //                 ->paginate(15);

        //$subject = SubjectsPhases::get();

        $subjects = Subject::query();
        $subjects = $subjects->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'sites.site_name')
        ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
        ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
        ->leftJoin('sites', 'sites.id', 'subjects.site_id')
        ->leftJoin('form_submit_status', 'form_submit_status.subject_id', 'subjects.id');

        if ($request->subject != '') {
            $subjects = $subjects->where('subjects.id', $request->subject);
        }

        if ($request->phase != '') {
            $subjects = $subjects->where('study_structures.id', $request->phase);
        }

        if ($request->site != '') {
            $subjects = $subjects->where('sites.id', $request->site);
        }

        if ($request->visit_date != '') {
                $visitDate = explode('-', $request->visit_date);
                    $from   = Carbon::parse($visitDate[0])
                                        ->startOfDay()        // 2018-09-29 00:00:00.000000
                                        ->toDateTimeString(); // 2018-09-29 00:00:00

                    $to     = Carbon::parse($visitDate[1])
                                        ->endOfDay()          // 2018-09-29 23:59:59.000000
                                        ->toDateTimeString(); // 2018-09-29 23:59:59

                $subjects =  $subjects->whereBetween('subjects_phases.visit_date', [$from, $to]);
            }

        $subjects = $subjects->orderBy('subjects.subject_id')
        ->orderBy('study_structures.position')
        ->paginate(15);

        //dd($subjects);

        // get modalities
        $getModilities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name','modilities.id as modility_id', 'modilities.modility_name')
        ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
        ->groupBy('phase_steps.modility_id')
        ->orderBy('modilities.modility_name')
        ->get();

        //dd($getModilities);

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

                //dd($getSteps);
            
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
                // assign the array to the key
                $subject->form_status = $formStatus;
            }// subject loop ends
        } // modality step null check

        /////////////////////////////// get filters ///////////////////////////////////////

        // get subjects
        $getFilterSubjects = Subject::select('id', 'subject_id')
                                      ->get();
        //get phases
        $getFilterPhases = StudyStructure::select('id', 'name')
                                           ->orderBy('position')
                                           ->get();
        // get sites
        $getFilterSites = Site::select('id', 'site_name')
                                ->get();
        // get modilities
        $getFilterModilities = Modility::select('id', 'modility_name')
                                        ->get();
        // get form types
        $getFilterFormType = FormType::select('id', 'form_type')
                                ->get();
        // get form status
        $getFilterFormStatus = array(
            'incomplete' => 'Incomplete',
            'complete' => 'Complete',
            'resumable' => 'Resumable'
        );

        return view('userroles::users.grading-list', compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getFilterModilities', 'getFilterFormType', 'getFilterFormStatus'));
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
