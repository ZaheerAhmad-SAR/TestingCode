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
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;
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
        // get all completed forms
        $getCompletedForms = FormStatus::where('study_id', Session::get('current_study'))
        ->where('form_status', 'complete')
        ->groupBy(['subject_id', 'study_structures_id', 'modility_id', 'form_type_id'])
        ->paginate(20);

        return view('formsubmission::subjectFormLoader.form_lock', ['getCompletedForms' => $getCompletedForms]);
    }
}
