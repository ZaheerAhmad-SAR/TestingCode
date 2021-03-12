<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Preference;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Subject;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\AssignWork;
use Modules\Admin\Entities\StudySite;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\Admin\Entities\TrailLog;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Modules\Admin\Entities\Modility;
use Session;
use Carbon\Carbon;
use Auth;

class SubjectFormLoaderController extends Controller
{
    public function showSubjectForm($studyId, $subjectId)
    {
        /***************/
        $studyId = isset($studyId) ? $studyId : 0;
        $study = Study::find($studyId);

        $subjectId = isset($subjectId) ? $subjectId : 0;
        $subject = Subject::find($subjectId);

        $site = Site::find($subject->site_id);
        $studySite = StudySite::where('study_id', $study->id)->where('site_id', $site->id)->firstOrNew();

        $current_user_id = auth()->user()->id;

        $subjectPhasesIdsArray = $subject->subjectPhasesArray();
        
        $visitPhases = StudyStructure::where('study_id', $studyId)
            ->whereIn('id', $subjectPhasesIdsArray)
            ->get();
        
        /*****************/

        session(['subject_id' => $subjectId]);
        session(['stepToActivateStr' => '']);
        return view('formsubmission::subjectFormLoader.subject_form')
            ->with('isPreview', false)
            ->with('subjectId', $subjectId)
            ->with('studyId', $studyId)
            ->with('visitPhases', $visitPhases)
            ->with('study', $study)
            ->with('subject', $subject)
            ->with('site', $site)
            ->with('studySite', $studySite)
            ->with('current_user_id', $current_user_id);
    }

    public function lockData(Request $request) {
        //get all phases and modalities for study
        // $getPhaseModalities = Subject::query();
        // $getPhaseModalities = $getPhaseModalities->select('subjects.id as sub_id', 'subjects.subject_id','study_structures.id as phase_id', 'study_structures.name as phase_name', 'modilities.id as modility_id', 'modilities.modility_name', 'modilities.modility_abbreviation')
        // ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
        // ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
        // ->crossJoin('modilities')
        // ->where('subjects.study_id', Session::get('current_study'))
        // ->whereNULL('subjects_phases.deleted_at')
        // ->whereNULL('study_structures.deleted_at')
        // ->whereNULL('modilities.deleted_at');
        // if($request->modality != '') {
        //     $getPhaseModalities = $getPhaseModalities->where('modilities.id', $request->modality);
        // }
        // if($request->phase != '') {
        //     $getPhaseModalities = $getPhaseModalities->where('study_structures.id', $request->phase);
        // }
        // // paginate data
        // $getPhaseModalities = $getPhaseModalities->groupBy(['subjects.id', 'study_structures.id'])
        //                                         ->orderBy('subjects.subject_id')
        //                                         ->orderBy('study_structures.position')
        //                                         ->paginate(20);

        // // get modalities for filter
        // $filetrModalities = Modility::orderBy('modility_abbreviation')->get();
        // // get study->phases for filter
        // $filterPhases = StudyStructure::where('study_id', Session::get('current_study'))
        //                                 ->orderBy('position')
        //                                 ->get();


        // modility/form type array
        $modalitySteps = [];

        $subjects = Subject::query();
        $subjects = $subjects->select('subjects.*', 'study_structures.id as phase_id', 'study_structures.name as phase_name', 'study_structures.position', 'subjects_phases.visit_date', 'subjects_phases.assign_work', 'sites.site_name')
            ->rightJoin('subjects_phases', 'subjects_phases.subject_id', '=', 'subjects.id')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'subjects_phases.phase_id')
            ->leftJoin('sites', 'sites.id', 'subjects.site_id')
            ->where('subjects.study_id', \Session::get('current_study'))
            ->whereNULL('subjects_phases.deleted_at')
            ->whereNULL('study_structures.deleted_at')
            ->whereNULL('sites.deleted_at');

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

        // get modilities according to study
        $getModilities = PhaseSteps::select('modilities.id as modility_id', 'modilities.modility_name')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'phase_steps.phase_id')
            ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
            ->whereNULL('modilities.deleted_at')
            ->whereNULL('study_structures.deleted_at')
            ->where('study_structures.study_id', \Session::get('current_study'))
            ->groupBy('phase_steps.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

        // get form types for modality
        foreach ($getModilities as $key => $modility) {

            $getSteps = PhaseSteps::select('phase_steps.step_id', 'phase_steps.step_name', 'phase_steps.modility_id', 'form_types.id as form_type_id', 'form_types.form_type')
                ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
                //->whereNULL('phase_steps.deleted_at')
                ->whereNULL('form_types.deleted_at')
                ->where('modility_id', $modility->modility_id)
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

                        if ($step != null) {

                            $getFormStatusArray = array(
                                'subject_id' => $subject->id,
                                'study_structures_id' => $subject->phase_id,
                                'modility_id' => $type['modility_id'],
                                'form_type_id' => $type['form_type_id']
                            );

                            if ($step->formType->form_type == 'Grading' || $step->formType->form_type == 'Eligibility') {

                                $formStatus[$key . '_' . $type['form_type']]['status'] =  \Modules\FormSubmission\Entities\FormStatus::getGradersFormsStatusesSpan($step, $getFormStatusArray, $step->graders_number, false);
                            } else {

                                $formStatus[$key . '_' . $type['form_type']]['status'] =  \Modules\FormSubmission\Entities\FormStatus::getFormStatus($step, $getFormStatusArray, true, false);
                            } // form types check ends


                            /*************** Form Lock *****************/
                            // unset form_type_id from above array
                            unset($getFormStatusArray['form_type_id']);
                            // insert study id
                            $getFormStatusArray['study_id'] = \Session::get('current_study');
                            // query for lock status
                            $lockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getFormStatusArray);
                            if(null !== $lockFormStatusObj) {
                                $formStatus[$key . '_' . $type['form_type']]['data_lock_status'] = $lockFormStatusObj->is_data_locked == 1 ? '<span class="" data-toggle="popover" data-trigger="hover" data-content="'.$lockFormStatusObj->is_data_locked_reason.'">
                                        <i class="fas fa-lock"></i>
                                    </span>' : '';
                            } else {
                                $formStatus[$key . '_' . $type['form_type']]['data_lock_status'] = '';
                            }
                            /*************** Form Lock *****************/
                        } 
                        else {

                            $formStatus[$key . '_' . $type['form_type']]['status'] = '';
                            $formStatus[$key . '_' . $type['form_type']]['data_lock_status'] = '';

                        } // step null check ends

                    } // step lopp ends

                } // modality loop ends
                // assign the array to the key
                $subject->form_status = $formStatus;
            } // subject loop ends
        } // modality step null check

        // get subjects
        $getFilterSubjects = Subject::select('id', 'subject_id')
                                    ->where('study_id', \Session::get('current_study'))
                                    ->get();
        //get phases
        $getFilterPhases = StudyStructure::select('id', 'name')->withOutRepeated()
                                            ->where('study_id', \Session::get('current_study'))
                                            ->orderBy('position')
                                            ->get();
        // get sites
        $getFilterSites = Site::select('id', 'site_name')
                                ->get();

        // get modilities according to study
        $getModilities = PhaseSteps::select('modilities.id', 'modilities.modility_name')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'phase_steps.phase_id')
            ->leftJoin('modilities', 'modilities.id', '=', 'phase_steps.modility_id')
            ->whereNULL('modilities.deleted_at')
            ->whereNULL('study_structures.deleted_at')
            ->where('study_structures.study_id', \Session::get('current_study'))
            ->groupBy('phase_steps.modility_id')
            ->orderBy('modilities.modility_name')
            ->get();

        // get form types according to study
        $getFormType = PhaseSteps::select('form_types.id', 'form_types.form_type')
            ->leftJoin('study_structures', 'study_structures.id', '=', 'phase_steps.phase_id')
            ->leftJoin('form_types', 'form_types.id', '=', 'phase_steps.form_type_id')
            ->where('study_structures.study_id', \Session::get('current_study'))
            ->whereNULL('form_types.deleted_at')
            ->whereNULL('study_structures.deleted_at')
            ->groupBy('phase_steps.form_type_id')
            ->orderBy('form_types.sort_order')
            ->get();

        return view('formsubmission::subjectFormLoader.form_lock',compact('subjects', 'modalitySteps', 'getFilterSubjects', 'getFilterPhases', 'getFilterSites', 'getModilities', 'getFormType'));
    }

    public function lockFormData(Request $request) {
        $input = $request->all();
        // loop the checked checkboxes
        foreach($input['check_subject'] as $key => $value) {
            // explode subject and phase
            $explodedSubjectPhase = explode('__/__', $value);
            // lock all the form for this study, subject, phase and modality
            $lockForms = FormStatus::where('subject_id', $explodedSubjectPhase[0])
                                    ->where('study_structures_id', $explodedSubjectPhase[1])
                                    ->whereIn('modility_id', $input['modility_id'])
                                    ->where('study_id', Session::get('current_study'))
                                    ->update(['is_data_locked' => 1, 'is_data_locked_reason' => $input['reason_for_locking']]);
            // lock all forms for the adjudication based on study, suject, phase and modality
            $lockAdjudictaionForms = AdjudicationFormStatus::where('subject_id', $explodedSubjectPhase[0])
                                                            ->where('study_structures_id', $explodedSubjectPhase[1])
                                                            ->whereIn('modility_id', $input['modility_id'])
                                                            ->where('study_id', Session::get('current_study'))
                                                            ->update(['is_data_locked' => 1, 'is_data_locked_reason' => $input['reason_for_locking']]);

            /********************** Trail Log *********************************/
            if($lockForms || $lockAdjudictaionForms) {
                // call the log function
                $this->trailLogForFormLocking($request, $explodedSubjectPhase[0], $explodedSubjectPhase[1], $input['modility_id'], $input['reason_for_locking'], 'Yes', 'Lock Form');
                
            } // if query executed
            /********************** Trail Log *********************************/
        } // modiality check loop ends
        // success msg
        Session::flash('success', 'Forms data locked successfully.');
        // return back
        return redirect()->back();
    }

    public function unlockFormData(Request $request) {
        $input = $request->all();
        // loop the checked checkboxes
        foreach($input['check_subject'] as $key => $value) {
            // explode subject and phase
            $explodedSubjectPhase = explode('__/__', $value);
            // unlock all the form for this study,subject, phase and modality
            $lockForms = FormStatus::where('subject_id', $explodedSubjectPhase[0])
                                    ->where('study_structures_id', $explodedSubjectPhase[1])
                                    ->whereIn('modility_id', $input['modility_id'])
                                    ->where('study_id', Session::get('current_study'))
                                    ->update(['is_data_locked' => 0, 'is_data_locked_reason' => $input['reason_for_locking']]);
            // unlock all forms for the adjudication based on study,subject, phase and modality
            $lockAdjudictaionForms = AdjudicationFormStatus::where('subject_id', $explodedSubjectPhase[0])
                                                            ->where('study_structures_id', $explodedSubjectPhase[1])
                                                            ->whereIn('modility_id', $input['modility_id'])
                                                            ->where('study_id', Session::get('current_study'))
                                                            ->update(['is_data_locked' => 0, 'is_data_locked_reason' => $input['reason_for_locking']]);
            /********************** Trail Log *********************************/
            if($lockForms || $lockAdjudictaionForms) {
                // call the log function
                $this->trailLogForFormLocking($request, $explodedSubjectPhase[0], $explodedSubjectPhase[1], $input['modility_id'], $input['reason_for_locking'], 'No', 'Unlock Form');
                
            } // if query executed
            /********************** Trail Log *********************************/
        } // modiality check loop ends
        // success msg
        Session::flash('success', 'Forms data unlocked successfully.');
        // return back
        return redirect()->back();
    }

    public function trailLogForFormLocking($request, $subjectId, $phaseId, $modalities, $lockReason, $lockStatus, $eventType) {

        // get modalities names
        $getModalities = Modility::whereIn('id', $modalities)->pluck('modility_name')->toArray();
        $implodeModaltyBynames = implode(', ', $getModalities);
        // get subject name
        $getSubjectName = Subject::where('id', $subjectId)->first();
        //get phase name
        $getPhaseName = StudyStructure::find($phaseId);
        $oldData = [];
        // data array for trai log
        $newData = array(
            'study_name' => \Session::get('study_short_name'),
            'subject_name' => $getSubjectName->subject_id,
            'phase_name' => $getPhaseName->name,
            'modility_name' => $implodeModaltyBynames,
            'lock_reason' => $lockReason,
            'lock_status' => $lockStatus,
        );
        // log message
        $logMessage = $eventType == 'Lock Form' ? \Auth::user()->name . ' lock form for study ' . Session::get('study_short_name') : \Auth::user()->name . ' unlock form for study ' . Session::get('study_short_name');
        // Log the event
        $trailLog = new TrailLog;
        $trailLog->event_id = $getSubjectName->id.'-'.$getPhaseName->id;
        $trailLog->event_section = 'Form';
        $trailLog->event_type = $eventType;
        $trailLog->event_message = $logMessage;
        $trailLog->user_id = Auth::user()->id;
        $trailLog->user_name = Auth::user()->name;
        $trailLog->role_id = Auth::user()->role_id;
        $trailLog->ip_address = $request->ip();
        $trailLog->study_id = \Session::get('current_study') != null ? \Session::get('current_study') : '';
        $trailLog->event_url = route('subjectFormLoader.lock-data');
        $trailLog->event_details = json_encode($newData);
        $trailLog->event_old_details = json_encode($oldData);
        $trailLog->save();
    }
}
