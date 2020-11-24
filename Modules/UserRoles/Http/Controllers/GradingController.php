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
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use DB;
use Excel;

use Modules\Admin\Entities\AssignWork;
use App\User;
use Modules\UserRoles\Entities\Role;

use Carbon\Carbon;
use App\Exports\GradingFromView;
use App\Exports\GradingFromView2;
use App\Exports\GradingStatusFromView;
use App\Exports\GradingStatusFromView2;

class GradingController extends Controller
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
                            } // step null check ends

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

                if ($request->form_type != '') {

                    $subjects = $subjects->where('form_submit_status.form_type_id', $request->form_type);
                }

                if ($request->form_status != '') {

                    $subjects = $subjects->where('form_submit_status.form_status', $request->form_status);
                }

                if ($request->graders_number != '') {

                    $subjects = $subjects->where('phase_steps.graders_number', $request->graders_number);
                }

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
                    ->where('form_submit_status.modility_id', $modility->modility_id);

                    if ($request->form_type != '') {

                        $getSteps = $getSteps->where('form_submit_status.form_type_id', $request->form_type);
                    }

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
                            } // step null check ends

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

    public function assignWork(Request $request) {

        $subjects = Subject::query();
            $subjects = $subjects->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'subjects_phases.assign_work', 'sites.site_name')
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
                            } // step null check ends

                        } // step lopp ends

                    } // modality loop ends
                    // assign the array to the key
                    $subject->form_status = $formStatus;
                }// subject loop ends
            } // modality step null check

        // get modilities
        $getModilities = Modility::select('id', 'modility_name')
                                        ->get();
        // get form types
        $getFormType = FormType::select('id', 'form_type')
                                ->get();

        return view('userroles::users.assign-work', compact('subjects', 'modalitySteps', 'getModilities', 'getFormType'));
    }

    public function saveAssignWork(Request $request) {

        $input = $request->all();
        // loop dubject
        foreach($input['subject_id'] as $key => $subject) {
            // check if check box is checked
            if(isset($input['check_subject'][$subject.'_'.$input['phase_id'][$key]])) {
                // find this phase assign work status
                $updatePhaseAssignStatus = SubjectsPhases::where('subject_id', $subject)
                                                          ->where('phase_id', ($input['phase_id'][$key]))
                                                          ->first();

                if ($updatePhaseAssignStatus->assign_work == '0') {
                    // loop user ids
                    foreach($input['users_id'] as $userId) {
                        // assign work object
                        $assignWork = new AssignWork;
                        $assignWork->subject_id = $subject;
                        $assignWork->phase_id = $input['phase_id'][$key];
                        $assignWork->modility_id = $input['modility_id'];
                        $assignWork->form_type_id = $input['form_type_id'];
                        $assignWork->user_id = $userId;
                        $assignWork->assign_date = $input['assign_date'];
                        $assignWork->save();

                        // update phase table for being assigned
                        $updatePhaseAssignStatus->assign_work = '1';
                        $updatePhaseAssignStatus->save();

                    } // user ends

                } // assign work ends

            } // check subject ends

        } // subject ends

        // success msg
        \Session::flash('success', 'Work assigned successfully.');

        //redirect
        return redirect(route('assign-work'));
    }

    public function getFormTypeUsers(Request $request) {
        //$request->form_type_id;
        if($request->ajax()) {

            // get form type
            $getFormType = FormType::find($request->form_type_id);

            $roleIds = [];

            if($getFormType->form_type == 'QC') {

                // get QC Role IDs
                $roleIds = Role::leftJoin('permission_role','permission_role.role_id', '=', 'roles.id')
                                    ->leftJoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                                    ->where('permissions.name', '=', 'qualitycontrol.index')
                                    ->where('roles.role_type', '!=', 'super_admin')
                                    ->groupBy('permission_role.role_id')
                                    ->pluck('permission_role.role_id')
                                    ->toArray();
                
            } elseif ($getFormType->form_type == 'Grading') {
                // get GRADING Role IDs
                $roleIds = Role::leftJoin('permission_role','permission_role.role_id', '=', 'roles.id')
                                    ->leftJoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                                    ->where('roles.role_type', '!=', 'super_admin')
                                    ->where(function ($query) {
                                        $query->where('permissions.name', '=', 'grading.index')
                                              ->orWhere('permissions.name', '=', 'adjudication.index');
                                    })
                                    ->groupBy('permission_role.role_id')
                                    ->pluck('permission_role.role_id')
                                    ->toArray();
                
            } elseif ($getFormType->form_type == 'Eligibility') {
                // get Eligibility Role IDs
                $roleIds = Role::leftJoin('permission_role','permission_role.role_id', '=', 'roles.id')
                                    ->leftJoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                                    ->where('permissions.name','=','eligibility.index')
                                    ->where('roles.role_type', '!=', 'super_admin')
                                    ->groupBy('permission_role.role_id')
                                    ->pluck('permission_role.role_id')
                                    ->toArray();

            }

            // get user on the basis of the role ids
            $getUsers = User::select('users.id', 'users.name')
                              ->leftJoin('user_roles', 'user_roles.user_id', '=', 'users.id')
                              ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
                              ->whereIn('user_roles.role_id', $roleIds)
                              ->groupBy('users.id')
                              ->orderBy('users.name', 'asc')
                              ->get();

            return response()->json(['success' => 'Users find.', 'getUsers' => $getUsers]);


        } // ajax ends
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

    public function excelGrading(Request $request) {
        
        return Excel::download(new GradingFromView(), 'gradings.xlsx');

    }

    public function excelGrading2(Request $request) {
        
        return Excel::download(new GradingFromView2(), 'gradings.xlsx');

    }

     public function excelGradingStatus(Request $request) {
        
        return Excel::download(new GradingStatusFromView(), 'gradings-status.xlsx');

    }

    public function excelGradingStatus2(Request $request) {
        
        return Excel::download(new GradingStatusFromView2(), 'gradings-status.xlsx');

    }

    public function gradingStatus(Request $request) {
    // get adjudication table data
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

            // adjudication array
            $adjudicationArray = [];
            // get form types for modality
            foreach($getModilities as $key => $modility) {

                $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                                        ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                                        ->where('modility_id', $modility->modility_id)
                                        ->where('phase_steps.form_type_id', '!=', 3)
                                        ->orderBy('form_types.sort_order')
                                        ->groupBy('phase_steps.form_type_id')
                                        ->get()->toArray();

                $modalitySteps[$modility->modility_name] = $getSteps;

                // get modalities as per adjudication
                $adjudicationArray[] = array(
                    "step_id" => $modility->step_id,
                    "step_name" => $modility->step_name,
                    "modility_id" => $modility->modility_id,
                    "form_type" => $modility->modility_name,
                );

            }

            $modalitySteps['Adjudication'] = $adjudicationArray;

            //get form status depending upon subject, phase and modality
            if ($modalitySteps != null) {
                foreach($subjects as $subject) {
                    //get status
                    $formStatus = [];

                    // modality loop
                    foreach($modalitySteps as $key => $formType) {

                        // form type loop
                        foreach($formType as $type) {

                                if ($key != 'Adjudication') {

                                    $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                                ->where('modility_id', $type['modility_id'])
                                                ->where('form_type_id', $type['form_type_id'])
                                                ->first();

                                    if ($step != null) {

                                        $getFormStatusArray = [
                                            'subject_id' => $subject->id,
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
                                    } // step null check ends

                                } else {

                                    $step = PhaseSteps::where('phase_id', $subject->phase_id)
                                                ->where('modility_id', $type['modility_id'])
                                                ->where('form_type_id', 2)
                                                ->first();

                                    if ($step != null) {

                                        // for ajudictaion
                                        $getAdjudicationFormStatusArray = [
                                            'subject_id' => $subject->id,
                                            'study_structures_id' => $subject->phase_id,
                                            'modility_id'=> $type['modility_id'],
                                        ];

                                        $formStatus[$key.'_'.$type['form_type']] = \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, true);

                                    } else {

                                        $formStatus[$key.'_'.$type['form_type']] = '<img src="' . url('images/no_status.png') . '"/>';

                                    }

                                } // ADJUDICATION CHECK ENDS

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
            $subjects = AdjudicationFormStatus::query();
            $subjects = $subjects->select('adjudication_form_status.subject_id as subj_id', 'adjudication_form_status.study_id', 'adjudication_form_status.study_structures_id', 'adjudication_form_status.phase_steps_id', 'adjudication_form_status.adjudication_status', 'adjudication_form_status.modility_id','subjects.subject_id', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'phase_steps.graders_number', 'subjects_phases.visit_date', 'sites.site_name')
                ->leftJoin('subjects', 'subjects.id', '=', 'adjudication_form_status.subject_id')
                ->leftJoin('study_structures', 'study_structures.id', '=', 'adjudication_form_status.study_structures_id')
                ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
                ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'adjudication_form_status.phase_steps_id')
                ->leftJoin('subjects_phases', 'subjects_phases.phase_id', 'adjudication_form_status.study_structures_id')
                ->where('adjudication_form_status.study_id', \Session::get('current_study'));

                if ($request->subject != '') {
                    $subjects = $subjects->where('adjudication_form_status.subject_id', $request->subject);
                }

                if ($request->phase != '') {
                    $subjects = $subjects->where('adjudication_form_status.study_structures_id', $request->phase);
                }

                if ($request->modility != '') {

                    $subjects = $subjects->where('adjudication_form_status.modility_id', $request->modility);
                }

                // if ($request->form_type != '') {

                //     $subjects = $subjects->where('adjudication_form_status.form_type_id', $request->form_type);
                // }

                if ($request->form_status != '') {

                    $subjects = $subjects->where('adjudication_form_status.adjudication_status', $request->form_status);
                }

                if ($request->graders_number != '') {

                    $subjects = $subjects->where('phase_steps.graders_number', $request->graders_number);
                }

                 $subjects = $subjects->groupBy(['adjudication_form_status.subject_id', 'adjudication_form_status.study_structures_id'])
                ->paginate(15);

            if (!$subjects->isEmpty()) {
            
                // get modalities
                $getModilities = AdjudicationFormStatus::query();
                $getModilities = $getModilities->select('adjudication_form_status.modility_id', 'phase_steps.step_id', 'phase_steps.step_name', 'modilities.modility_name')
                ->leftJoin('modilities', 'modilities.id', '=', 'adjudication_form_status.modility_id')
                ->leftJoin('phase_steps', 'phase_steps.step_id', '=', 'adjudication_form_status.phase_steps_id');
                
                if ($request->modility != '') {

                    $getModilities = $getModilities->where('adjudication_form_status.modility_id', $request->modility);
                }

                $getModilities = $getModilities->groupBy('adjudication_form_status.modility_id')
                                                ->orderBy('modilities.modility_name')
                                                ->get();

                // get form types for modality
                foreach($getModilities as $modility) {
                    
                    // get modalities as per adjudication
                    $modalitySteps['Adjudication'][] = array(
                        "step_id" => $modility->step_id,
                        "step_name" => $modility->step_name,
                        "modility_id" => $modility->modility_id,
                        "form_type" => $modility->modility_name,
                    );


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
                                                ->where('form_type_id', 2)
                                                ->first();

                            if ($step != null) {

                                 // for ajudictaion
                                $getAdjudicationFormStatusArray = [
                                    'subject_id' => $subject->subj_id,
                                    'study_structures_id' => $subject->phase_id,
                                    'modility_id'=> $type['modility_id'],
                                ];


                                $formStatus[$key.'_'.$type['form_type']] =  \Modules\FormSubmission\Entities\AdjudicationFormStatus::getAdjudicationFormStatus($step, $getAdjudicationFormStatusArray, $wrap = true);


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

        // get form status
        $getFilterFormStatus = array(
            'incomplete' => 'Initiated',
            'complete' => 'Complete',
            'resumable' => 'Editing'
        );

        return view('userroles::users.grading-status', compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getFilterModilities', 'getFilterFormStatus'));
    }
}
