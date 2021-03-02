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
use Modules\Admin\Entities\StudySite;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
use Modules\Admin\Entities\Modility;
use Session;

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
         // get modalities for filter
        $filetrModalities = Modility::orderBy('modility_abbreviation')->get();
        // get study->phases for filter
        $filterPhases = StudyStructure::where('study_id', Session::get('current_study'))
                                        ->orderBy('position')
                                        ->get();
        //get all phases and modalities for study
        $getPhaseModalities = StudyStructure::query();
        $getPhaseModalities = $getPhaseModalities->select('study_structures.id as phase_id', 'study_structures.name as phase_name', 'modilities.id as modility_id', 'modilities.modility_name', 'modilities.modility_abbreviation')
        ->crossJoin('modilities')
        ->where('study_structures.study_id', Session::get('current_study'))
        ->whereNULL('study_structures.deleted_at')
        ->whereNULL('modilities.deleted_at');
        if($request->modality != '') {
            $getPhaseModalities = $getPhaseModalities->where('modilities.id', $request->modality);
        }
        if($request->phase != '') {
            $getPhaseModalities = $getPhaseModalities->where('study_structures.id', $request->phase);
        }
        // paginate data
        $getPhaseModalities = $getPhaseModalities->orderBy('study_structures.position')
                                                 ->paginate(20);

        return view('formsubmission::subjectFormLoader.form_lock', ['getPhaseModalities' => $getPhaseModalities, 'filterPhases' => $filterPhases, 'filetrModalities' => $filetrModalities]);
    }

    public function lockFormData(Request $request) {
        $input = $request->all();
        // loop the checked checkboxes
        foreach($input['check_modality'] as $key => $value) {
            // explode phase and modality
            $explodedPhaseModality = explode('__/__', $value);
            // lock all the form for this study, phase and modality
            $lockForms = FormStatus::where('study_structures_id', $explodedPhaseModality[0])
                                    ->where('modility_id', $explodedPhaseModality[1])
                                    ->where('study_id', Session::get('current_study'))
                                    ->update(['is_data_locked' => 1]);
            // lock all forms for the adjudication based on study, phase and modality
            $lockAdjudictaionForms = AdjudicationFormStatus::where('study_structures_id', $explodedPhaseModality[0])
                                                            ->where('modility_id', $explodedPhaseModality[1])
                                                            ->where('study_id', Session::get('current_study'))
                                                            ->update(['is_data_locked' => 1]);
        } // modiality check loop ends
        // success msg
        Session::flash('success', 'Forms data locked successfully.');
        // return back
        return redirect()->back();
    }

    public function unlockFormData(Request $request) {
        $input = $request->all();
        // loop the checked checkboxes
        foreach($input['check_modality'] as $key => $value) {
            // explode phase and modality
            $explodedPhaseModality = explode('__/__', $value);
            // unlock all the form for this study, phase and modality
            $lockForms = FormStatus::where('study_structures_id', $explodedPhaseModality[0])
                                    ->where('modility_id', $explodedPhaseModality[1])
                                    ->where('study_id', Session::get('current_study'))
                                    ->update(['is_data_locked' => 0]);
            // unlock all forms for the adjudication based on study, phase and modality
            $lockAdjudictaionForms = AdjudicationFormStatus::where('study_structures_id', $explodedPhaseModality[0])
                                                            ->where('modility_id', $explodedPhaseModality[1])
                                                            ->where('study_id', Session::get('current_study'))
                                                            ->update(['is_data_locked' => 0]);
        } // modiality check loop ends
        // success msg
        Session::flash('success', 'Forms data unlocked successfully.');
        // return back
        return redirect()->back();
    }
}
