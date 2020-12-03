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

class QualityControlController extends Controller
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
            ->where('subjects.study_id', \Session::get('current_study'));
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

            $subjects = $subjects->orderBy('subjects.subject_id')
            ->orderBy('study_structures.position')
            ->paginate(15);

            // get modalities
            $getModilities = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name','modilities.id as modility_id', 'modilities.modility_name')
            ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
            ->groupBy('phase_steps.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

            // get form types for modality
            foreach($getModilities as $key => $modility) {

                $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                                        ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                                        ->where('modility_id', $modility->modility_id)
                                        ->where('form_types.form_type', 'QC')
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

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                                ->where('modility_id', $type['modility_id'])
                                                ->where('form_type_id', $type['form_type_id'])
                                                ->first();
                            
                            if ($step != null) {

                                $getFormStatusArray = array(
                                    'subject_id' => $subject->id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                );

                                if ($step->form_type_id == 2) {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);
                                } else {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);


                                }

                            } else {

                                $formStatus[$key.'_'.$type['form_type']] = '<img src="' . url('images/no_status.png') . '"/>';
                            } // step check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key
                    $subject->form_status = $formStatus;
                }// subject loop ends
            } // modality step null check

        }
        // form One ends

        // if it is form 2
        if ($request->has('form_2')) {

            // get subjects
            $subjects = FormStatus::query();
            $subjects = $subjects->select('form_submit_status.subject_id as subj_id', 'form_submit_status.study_id', 'form_submit_status.study_structures_id', 'form_submit_status.phase_steps_id', 'form_submit_status.form_type_id', 'form_submit_status.form_status', 'form_submit_status.modility_id','subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
                ->leftJoin('subjects', 'subjects.id', '=', 'form_submit_status.subject_id')
                ->leftJoin('study_structures', 'study_structures.id', '=', 'form_submit_status.study_structures_id')
                ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
                ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'form_submit_status.study_structures_id')
                ->where('form_submit_status.form_type_id', 1)
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
                ->paginate(15);


            if (!$subjects->isEmpty()) {
            // get modalities
            $getModilities = FormStatus::query();
            $getModilities = $getModilities->select('form_submit_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
            ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
            ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id');

            if ($request->modility != '') {

                $getModilities = $getModilities->where('form_submit_status.modility_id', $request->modility);
            }

            $getModilities = $getModilities->groupBy('form_submit_status.modility_id')
                                        ->orderBy('modilities.modility_name')
                                        ->get();

            // get form types for modality
            foreach($getModilities as $modility) {

                $getSteps = FormStatus::query();

                $getSteps = $getSteps->select('form_submit_status.form_type_id', 'form_submit_status.modility_id','phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name', 'form_types.form_type', 'form_types.sort_order')
                    ->leftJoin('modilities', 'modilities.id', '=', 'form_submit_status.modility_id')
                    ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'form_submit_status.phase_steps_id')
                    ->leftJoin('form_types', 'form_types.id', '=', 'form_submit_status.form_type_id')
                    ->where('form_submit_status.modility_id', $modility->modility_id)
                    ->where('form_submit_status.form_type_id', 1);

                    // if ($request->form_type != '') {

                    //     $getSteps = $getSteps->where('form_submit_status.form_type_id', $request->form_type);
                    // }

                   $getSteps = $getSteps->orderBy('form_types.sort_order')
                    ->groupBy('form_submit_status.form_type_id')
                    ->get()->toArray();

                $modalitySteps[$modility->modility_name] = $getSteps;
            } // loop ends modility

            }// subject empty check

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach($formType as $type) {

                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                                ->where('modility_id', $type['modility_id'])
                                                ->where('form_type_id', $type['form_type_id'])
                                                ->first();

                            if ($step != null) {

                                $getFormStatusArray = [
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                ];

                                if ($step->form_type_id == 2) {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);

                                } else {

                                    $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);
                                }

                            } else {

                                $formStatus[$key.'_'.$type['form_type']] = '<img src="' . url('images/no_status.png') . '"/>';
                            } // step check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key

                    $subject->form_status = $formStatus;
                }// subject loop ends

            } // modality step null check

        } // form 2 if ends

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
            'incomplete' => 'Initiated',
            'complete' => 'Complete',
            'resumable' => 'Editing'
        );

        return view('userroles::users.qc-list', compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getFilterModilities', 'getFilterFormType', 'getFilterFormStatus'));
    }

    public function qcWorkList(Request $request) {

        $modalitySteps = [];

        $subjects = AssignWork::query();
        // get subjects for assign work
        $subjects = $subjects->select('assign_work.subject_id as subj_id', 'assign_work.study_id', 'assign_work.phase_id', 'assign_work.form_type_id', 'assign_work.modility_id', 'assign_work.assign_date', 'subjects.subject_id', 'study_structures.name as phase_name', 'study_structures.position', 'sites.site_name')
        ->leftJoin('subjects', 'subjects.id', '=', 'assign_work.subject_id')
        ->leftJoin('study_structures', 'study_structures.id', '=', 'assign_work.phase_id')
        ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
        ->where('assign_work.user_id', \Auth::user()->id)
        ->where('assign_work.form_type_id', 1)
        ->where('assign_work.study_id', \Session::get('current_study'));
        // apply filters
        if ($request->subject != '') {
            $subjects = $subjects->where('assign_work.subject_id', $request->subject);
        }

        if ($request->phase != '') {
            $subjects = $subjects->where('assign_work.phase_id', $request->phase);
        }

        if ($request->site != '') {
            $subjects = $subjects->where('sites.id', $request->site);
        }

        if ($request->assign_date != '') {
            $visitDate = explode('-', $request->assign_date);
                $from   = Carbon::parse($visitDate[0])
                                    ->startOfDay()        // 2018-09-29 00:00:00.000000
                                    ->toDateTimeString(); // 2018-09-29 00:00:00

                $to     = Carbon::parse($visitDate[1])
                                    ->endOfDay()          // 2018-09-29 23:59:59.000000
                                    ->toDateTimeString(); // 2018-09-29 23:59:59

            $subjects =  $subjects->whereBetween('assign_work.assign_date', [$from, $to]);
        }

        if ($request->modility != '') {

            $subjects = $subjects->where('assign_work.modility_id', $request->modility);
        }

        $subjects = $subjects->groupBy(['assign_work.subject_id', 'assign_work.phase_id'])
                             ->orderBy('subjects.subject_id')
                             ->orderBy('study_structures.position')
                             ->paginate(15);


        // get modalities
        $getModilities = AssignWork::query();
        $getModilities = $getModilities->select('assign_work.modility_id', 'modilities.modility_name')
        ->leftJoin('modilities', 'modilities.id', '=', 'assign_work.modility_id')
        ->where('assign_work.user_id', \Auth::user()->id)
        ->where('assign_work.form_type_id', 1)
        ->where('assign_work.study_id', \Session::get('current_study'));

        if ($request->modility != '') {

            $getModilities = $getModilities->where('assign_work.modility_id', $request->modility);
        }

        $getModilities = $getModilities
        ->groupBy('assign_work.modility_id')
        ->orderBy('modilities.modility_name')
        ->get();

        // get form types for modality
        foreach($getModilities as $key => $modility) {

            $getSteps = AssignWork::select('assign_work.modility_id','form_types.id as form_type_id', 'form_types.form_type')
                                    ->leftJoin('form_types', 'form_types.id', '=', 'assign_work.form_type_id')
                                    ->where('modility_id', $modility->modility_id)
                                    ->where('form_types.form_type', 'QC')
                                    ->orderBy('form_types.sort_order')
                                    ->groupBy('assign_work.form_type_id')
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

                        // comparing assign modality with the array modality
                        $checkModality = AssignWork::where('subject_id', $subject->subj_id)
                                                    ->where('phase_id', $subject->phase_id)
                                                    ->where('modility_id', $type['modility_id'])
                                                    ->where('form_type_id', $type['form_type_id'])
                                                    ->where('user_id', \Auth::user()->id)
                                                    ->first();

                        if($checkModality != null) {
                        //if ($subject->modility_id == $type['modility_id']) {

                            $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(179, 183, 187, 0.5)';

                            // check if form is not initialize and assign date is passed
                            $getFormStatus = FormStatus::where('subject_id', $subject->subj_id)
                                                    ->where('study_structures_id', $subject->phase_id)
                                                    ->where('modility_id', $type['modility_id'])
                                                    ->where('form_type_id', $type['form_type_id'])
                                                    ->first();

                            if($getFormStatus == null) {

                                $diffInDays = Carbon::now()->diffInDays(Carbon::parse($checkModality->assign_date), false);

                                
                                // check week difference (past date)
                                if ($diffInDays < 0) {

                                    $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(255, 0, 0, 0.5)';
                                    
                                }

                                // check due date (in week)
                                if ($diffInDays >= 0 && $diffInDays <= 7) {
                                    $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(241, 245, 15, 0.5)';
                                }

                            } else {

                                // if it is graded
                                if($getFormStatus->form_status == 'complete') {

                                    $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(76, 175, 80, 0.5)';

                                }
                            } // form status null check ends

                            // check step
                            $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                                ->where('modility_id', $type['modility_id'])
                                                ->where('form_type_id', $type['form_type_id'])
                                                ->first();
                            
                            if ($step != null) {

                                $getFormStatusArray = array(
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                    'form_type_id' => $type['form_type_id']
                                );

                                if ($step->form_type_id == 2) {

                                    $formStatus[$key.'_'.$type['form_type']]['status'] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);
                                } else {

                                    $formStatus[$key.'_'.$type['form_type']]['status'] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);

                                }

                            } else {

                                $formStatus[$key.'_'.$type['form_type']]['status'] = '<img src="' . url('images/no_status.png') . '"/>';
                            } // step check ends

                        } else {

                            $formStatus[$key.'_'.$type['form_type']]['status'] = '';
                            $formStatus[$key.'_'.$type['form_type']]['color'] = '';

                        } // modility check ends

                    } // step lopp ends

                } // modality loop ends
                // assign the array to the key
                $subject->form_status = $formStatus;
            }// subject loop ends
        } // modality step null check

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


       return view('userroles::users.qc-work-list', compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getFilterModilities'));
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

    public function excelQC(Request $request) {
        
        return Excel::download(new QCFromView(), 'qc.xlsx');

    }

    public function excelQC2(Request $request) {
        
        return Excel::download(new QCFromView2(), 'qc.xlsx');

    }
}
