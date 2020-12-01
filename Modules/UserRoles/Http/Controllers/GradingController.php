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

use Modules\Admin\Entities\TrailLog;

use Carbon\Carbon;
use App\Exports\GradingFromView;
use App\Exports\GradingFromView2;
use App\Exports\GradingStatusFromView;
use App\Exports\GradingStatusFromView2;

use Session;
use Auth;

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

            // modility/form type array
            $modalitySteps = [];

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

                            // assign order color
                            $getAssignWork = AssignWork::where('subject_id', $subject->id)
                                                    ->where('phase_id', $subject->phase_id)
                                                    ->where('modility_id', $type['modility_id'])
                                                    ->where('form_type_id', $type['form_type_id'])
                                                    ->first();

                            if ($getAssignWork != null) {

                                $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(76, 175, 80, 0.5)';

                                // check if form is not initialize and assign date is passed
                                $getFormStatus = FormStatus::where('subject_id', $subject->id)
                                                        ->where('study_structures_id', $subject->phase_id)
                                                        ->where('modility_id', $type['modility_id'])
                                                        ->where('form_type_id', $type['form_type_id'])
                                                        ->first();

                                if($getFormStatus == null) {

                                    // check date
                                    $diffInDays = Carbon::now()->diffInDays(Carbon::parse($getAssignWork->assign_date), false);


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

                                        $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(179, 183, 187, 0.5)';

                                    }
                                } // form status null check ends

                            } else {

                                $formStatus[$key.'_'.$type['form_type']]['color'] = '';

                            } // assignwork check ends

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

                                    $formStatus[$key.'_'.$type['form_type']]['status'] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);
                                } else {

                                    $formStatus[$key.'_'.$type['form_type']]['status'] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);
                                }

                            } else {

                                $formStatus[$key.'_'.$type['form_type']]['status'] = '<img src="' . url('images/no_status.png') . '"/>';
                            } // step null check ends

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

        // get modilities according to study
        $getModilities = PhaseSteps::select('modilities.id', 'modilities.modility_name')
            ->leftJoin('study_structures','study_structures.id', '=', 'phase_steps.phase_id')
            ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
            ->where('study_structures.study_id', \Session::get('current_study'))
            ->groupBy('phase_steps.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

        // get form types according to study
        $getFormType = PhaseSteps::select('form_types.id', 'form_types.form_type')
            ->leftJoin('study_structures','study_structures.id', '=', 'phase_steps.phase_id')
            ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
            ->where('study_structures.study_id', \Session::get('current_study'))
            ->groupBy('phase_steps.form_type_id')
            ->orderBy('form_types.sort_order')
            ->get();

        return view('userroles::users.assign-work', compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getModilities', 'getFormType'));
    }

    public function checkAssignWork(Request $request) {

        $input = $request->all();

        $count = 0;
        // loop dubject
        foreach($input['subject_id'] as $key => $subject) {
            // check if check box is checked
            if(isset($input['check_subject'][$subject.'_'.$input['phase_id'][$key]])) {

                $checkSubjectPhase = AssignWork::where('subject_id', $subject)
                                              ->where('phase_id', $input['phase_id'][$key])
                                              ->where('modility_id', $input['modility_id'])
                                              ->where('form_type_id', $input['form_type_id'])
                                              ->first();


                if ($checkSubjectPhase != null) {

                    $count++;
                }
                
            } // check subject ends

        } // subject ends

        // return response
        if ($count == 0) {

            return response()->json(['success' => $count]);

        } else {

            return response()->json(['success' => $count]);

        }
    }

    public function saveAssignWork(Request $request) {

        $input = $request->all();

        //dd($input);

        // loop dubject
        foreach($input['subject_id'] as $key => $subject) {

            // check if check box is checked
            if(isset($input['check_subject'][$subject.'_'.$input['phase_id'][$key]])) {
                
                // delete old subject/phase on the basis of this modility and form type
                $deleteSubjectPhase = AssignWork::where('subject_id', $subject)
                                              ->where('phase_id', $input['phase_id'][$key])
                                              ->where('modility_id', $input['modility_id'])
                                              ->where('form_type_id', $input['form_type_id'])
                                              ->delete();

                if (isset($input['users_id'])) {
                    // loop user ids
                    foreach($input['users_id'] as $userId) {
                        // assign work object
                        $assignWork = new AssignWork;
                        $assignWork->study_id = \Session::get('current_study');
                        $assignWork->subject_id = $subject;
                        $assignWork->phase_id = $input['phase_id'][$key];
                        $assignWork->modility_id = $input['modility_id'];
                        $assignWork->form_type_id = $input['form_type_id'];
                        $assignWork->user_id = $userId;
                        $assignWork->assign_date = $input['assign_date'];
                        $assignWork->save();

                    } // user ends

                    // get all users name
                    $userName = User::whereIn('id', $input['users_id'])->pluck('name')->toArray();
                    // get subject name
                    $getSubjectName = Subject::where('id', $subject)->first();
                    //get phase name
                    $getPhaseName = StudyStructure::where('id', $input['phase_id'][$key])->first();
                    //get modality name
                    $getModilityName = Modility::where('id', $input['modility_id'])->first();
                    //get Form name
                    $getFormName = FormType::where('id', $input['form_type_id'])->first();

                    $oldData = [];

                    $newData = array(
                        'study_name' => \Session::get('study_short_name'),
                        'subject_name' => $getSubjectName->subject_id,
                        'phase_name' => $getPhaseName->name,
                        'modility_name' => $getModilityName->modility_name,
                        'form_type' => $getFormName->form_type,
                        'users' => $userName != null ? implode(',', $userName) : '',
                        'due_date' => date('d-M-Y', strtotime($input['assign_date']))
                    );

                    // Log the event
                    $trailLog = new TrailLog;
                    $trailLog->event_id = $assignWork->id;
                    $trailLog->event_section = 'Assign Work';
                    $trailLog->event_type = 'Add';
                    $trailLog->event_message = \Auth::user()->name.' assigned work for study '.Session::get('study_short_name');
                    $trailLog->user_id = Auth::user()->id;
                    $trailLog->user_name = Auth::user()->name;
                    $trailLog->role_id = Auth::user()->role_id;
                    $trailLog->ip_address = $request->ip();
                    $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
                    $trailLog->event_url = route('assign-work');
                    $trailLog->event_details = json_encode($newData);
                    $trailLog->event_old_details = json_encode($oldData);
                    $trailLog->save();

                } // user null ends


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

            // get user on the basis of the role ids per current session study
            $getUsers = User::select('users.id', 'users.name')
                              ->leftJoin('study_role_users', 'study_role_users.user_id', '=', 'users.id')
                              ->leftJoin('roles', 'roles.id', '=', 'study_role_users.role_id')
                              ->where('study_role_users.study_id', \Session::get('current_study'))
                              ->whereIn('study_role_users.role_id', $roleIds)
                              ->groupBy('users.id')
                              ->orderBy('users.name', 'asc')
                              ->get();

            return response()->json(['success' => 'Users find.', 'getUsers' => $getUsers]);

        } // ajax ends
    }

    public function editAssignWork(Request $request) {
        // get data 
        $editAssignWork = AssignWork::where('subject_id', $request->subject_id)
                                    ->where('phase_id', $request->phase_id)
                                    ->first();

        // get users for this subject and phase
        $getUsers = AssignWork::select('users.id as user_id', 'users.name')
                                ->leftJoin('users', 'users.id', '=', 'assign_work.user_id')
                                ->where('assign_work.subject_id', $request->subject_id)
                                ->where('assign_work.phase_id', $request->phase_id)
                                ->get();                           

        return response()->json(['editAssignWork' => $editAssignWork, 'getUsers' => $getUsers]);
    }

    public function updateAssignWork(Request $request) {
        
        $input = $request->all();

        // delete old record for this subject and phase
        $deleteSubjectPhase = AssignWork::where('subject_id', $input['edit_subject_id'])
                                          ->where('phase_id', $input['edit_phase_id'])
                                          ->delete();


        // loop user ids
        foreach($input['edit_users_id'] as $userId) {

            // assign work object
            $assignWork = new AssignWork;
            $assignWork->study_id = \Session::get('current_study');
            $assignWork->subject_id     = $input['edit_subject_id'];
            $assignWork->phase_id       = $input['edit_phase_id'];
            $assignWork->modility_id    = $input['edit_modility_id'];
            $assignWork->form_type_id   = $input['edit_form_type_id'];
            $assignWork->user_id        = $userId;
            $assignWork->assign_date    = $input['edit_assign_date'];
            $assignWork->save();

        } // user ends

         // success msg
        \Session::flash('success', 'Work assigned updated successfully.');

        //redirect
        return redirect(route('assign-work'));
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

            // if array is not null assign it to modalitySteps
            if ($adjudicationArray != null) {
                $modalitySteps['Adjudication'] = $adjudicationArray;
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

    public function gradingWorkList(Request $request) {

        $modalitySteps = [];
        
        // get subjects
            $subjects = AssignWork::query();
            $subjects = $subjects->select('assign_work.subject_id as subj_id', 'assign_work.study_id', 'assign_work.phase_id', 'assign_work.form_type_id', 'assign_work.modility_id', 'assign_work.assign_date', 'subjects.subject_id', 'study_structures.name as phase_name', 'study_structures.position', 'sites.site_name')
                ->leftJoin('subjects', 'subjects.id', '=', 'assign_work.subject_id')
                ->leftJoin('study_structures', 'study_structures.id', '=', 'assign_work.phase_id')
                ->leftJoin('sites', 'sites.id', '=', 'subjects.site_id')
                ->where('assign_work.user_id', \Auth::user()->id)
                ->where('assign_work.form_type_id', 2)
                ->where('assign_work.study_id', \Session::get('current_study'));

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
            ->where('assign_work.form_type_id', 2)
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
                                        ->where('form_types.form_type', 'Grading')
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
                        // comparing assign modality with the array modality
                        //if($subject->modility_id == $type['modility_id']) {


                            $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(76, 175, 80, 0.5)';

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

                                    $formStatus[$key.'_'.$type['form_type']]['color'] = 'background: rgba(179, 183, 187, 0.5)';

                                }

                            }// form status null check ends

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

        return view('userroles::users.grading-work-list',  compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getFilterModilities'));
    }
}
