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
use Modules\FormSubmission\Entities\SubjectsPhases;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\FormType;
use Modules\Admin\Entities\AssignWork;
use DB;
use Carbon\Carbon;
use Excel;
use App\Exports\QCFromView;
use App\Exports\QCFromView2;

class OtherFormsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
       
        $subjects = collect();
        // modility/form type array
        $modalitySteps = [];

        // if it is form 1
        if ($request->has('form_1')) {

            $subjects = Subject::query();
            $subjects = $subjects->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'sites.site_name')
                ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
                ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
                ->leftJoin('sites', 'sites.id', 'subjects.site_id')
                ->where('subjects.study_id', \Session::get('current_study'))
                ->whereNULL('subjects_phases.deleted_at')
                ->whereNULL('study_structures.deleted_at')
                ->whereNULL('sites.deleted_at');
            //->leftJoin('form_submit_status', 'form_submit_status.subject_id', 'subjects.id');

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

            $subjects = $subjects->groupBy(['subjects.id', 'study_structures.id'])
                ->orderBy('subjects.subject_id')
                ->orderBy('study_structures.position')
                ->paginate(\Auth::user()->user_prefrences->default_pagination);

            // get modalities
            $getModilities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'modilities.id as modility_id', 'modilities.modility_name')
                ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
                ->whereNULL('modilities.deleted_at')
                ->groupBy('phase_steps.modility_id')
                ->orderBy('modilities.modility_name')
                ->get();

            // get form types for modality
            foreach ($getModilities as $key => $modility) {

                $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                    ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                    ->where('modility_id', $modility->modility_id)
                    ->where('form_types.form_type', 'QC')
                    ->whereNULL('form_types.deleted_at')
                    ->orderBy('form_types.sort_order')
                    ->groupBy('phase_steps.form_type_id')
                    ->get()->toArray();

                $modalitySteps[$modility->modility_name] = $getSteps;
            }

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach ($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach ($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach ($formType as $type) {

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                ->where('modility_id', $type['modility_id'])
                                ->where('form_type_id', $type['form_type_id'])
                                ->first();

                            //$formStatus[$key . '_' . $type['form_type']] = '';

                            if ($step != null) {

                                $getFormStatusArray = array(
                                    'subject_id' => $subject->id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id' => $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                );

                                if ($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') {

                                    $formStatus[$key . '_' . $type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);
                                } else {

                                    $formStatus[$key . '_' . $type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);
                                }
                            } 
                            else {

                                $formStatus[$key . '_' . $type['form_type']] = '';
                            } // step check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key
                    $subject->form_status = $formStatus;
                } // subject loop ends
            } // modality step null check

        }
        // form One ends

        // if it is form 2
        if ($request->has('form_2')) {

            // get subjects
            $subjects = FormStatus::query();
            $subjects = $subjects->select('form_submit_status.subject_id as subj_id', 'form_submit_status.study_id', 'form_submit_status.study_structures_id', 'form_submit_status.phase_steps_id', 'form_submit_status.form_type_id', 'form_submit_status.form_status', 'form_submit_status.modility_id', 'subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
                ->leftJoin('subjects', 'subjects.id', '=', 'form_submit_status.subject_id')
                ->leftJoin('study_structures', 'study_structures.id', '=', 'form_submit_status.study_structures_id')
                ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
                ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'form_submit_status.study_structures_id')
                ->whereNULL('subjects.deleted_at')
                ->whereNULL('study_structures.deleted_at')
                ->whereNULL('sites.deleted_at')
                ->whereNULL('phase_steps.deleted_at')
                ->whereNULL('subjects_phases.deleted_at')
                ->where('form_submit_status.form_type_id', 4)
                ->where('form_submit_status.study_id', \Session::get('current_study'));

            if ($request->subject != '') {
                $subjects = $subjects->where('form_submit_status.subject_id', $request->subject);
            }

            if ($request->phase != '') {
                $subjects = $subjects->where('form_submit_status.study_structures_id', $request->phase);
            }

            if ($request->modility != '') {

                $subjects = $subjects->where('form_submit_status.modility_id', $request->modility);
            }

            // if ($request->form_type != '') {

            //     $subjects = $subjects->where('form_submit_status.form_type_id', $request->form_type);
            // }

            if ($request->form_status != '') {

                $subjects = $subjects->where('form_submit_status.form_status', $request->form_status);
            }

            // if ($request->graders_number != '') {

            //     $subjects = $subjects->where('phase_steps.graders_number', $request->graders_number);
            // }

            $subjects = $subjects->groupBy(['form_submit_status.subject_id', 'form_submit_status.study_structures_id'])
                ->paginate(\Auth::user()->user_prefrences->default_pagination);


            if (!$subjects->isEmpty()) {
                // get modalities
                $getModilities = FormStatus::query();
                $getModilities = $getModilities->select('form_submit_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
                    ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
                    ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                    ->whereNULL('modilities.deleted_at')
                    ->whereNULL('phase_steps.deleted_at');

                if ($request->modility != '') {

                    $getModilities = $getModilities->where('form_submit_status.modility_id', $request->modility);
                }

                $getModilities = $getModilities->groupBy('form_submit_status.modility_id')
                    ->orderBy('modilities.modility_name')
                    ->get();

                // get form types for modality
                foreach ($getModilities as $modility) {

                    $getSteps = FormStatus::query();

                    $getSteps = $getSteps->select('form_submit_status.form_type_id', 'form_submit_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name', 'form_types.form_type', 'form_types.sort_order')
                        ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
                        ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                        ->leftJoin('form_types', 'form_types.id', '=', 'form_submit_status.form_type_id')
                        ->whereNULL('modilities.deleted_at')
                        ->whereNULL('phase_steps.deleted_at')
                        ->whereNULL('form_types.deleted_at')
                        ->where('form_submit_status.modility_id', $modility->modility_id)
                        ->where('form_submit_status.form_type_id', 4);

                    // if ($request->form_type != '') {

                    //     $getSteps = $getSteps->where('form_submit_status.form_type_id', $request->form_type);
                    // }

                    $getSteps = $getSteps->orderBy('form_types.sort_order')
                        ->groupBy('form_submit_status.form_type_id')
                        ->get()->toArray();

                    $modalitySteps[$modility->modility_name] = $getSteps;
                } // loop ends modility

            } // subject empty check

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach ($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach ($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach ($formType as $type) {

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                ->where('modility_id', $type['modility_id'])
                                ->where('form_type_id', $type['form_type_id'])
                                ->first();

                            //$formStatus[$key . '_' . $type['form_type']] = '';

                            if ($step != null) {

                                $getFormStatusArray = [
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id' => $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                ];

                                if ($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') {

                                    $formStatus[$key . '_' . $type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);
                                } else {

                                    $formStatus[$key . '_' . $type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);
                                }
                            } 
                            else {

                                $formStatus[$key . '_' . $type['form_type']] = '';
                            } // step check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key

                    $subject->form_status = $formStatus;
                } // subject loop ends

            } // modality step null check

        } // form 2 if ends

        /////////////////////////////// get filters ///////////////////////////////////////

        // get subjects
        $getFilterSubjects = Subject::select('id', 'subject_id')
            ->get();
        //get phases
        $getFilterPhases = StudyStructure::select('id', 'name')->withOutRepeated()
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
            'incomplete' => 'Initiated',
            'complete' => 'Complete',
            'resumable' => 'Editing'
        );

        return view('userroles::users.other-form-list', compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getFilterModilities', 'getFilterFormType', 'getFilterFormStatus'));
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
