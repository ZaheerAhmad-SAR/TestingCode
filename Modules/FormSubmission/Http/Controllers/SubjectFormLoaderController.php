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
use Modules\Admin\Scopes\StudyStructureWithoutRepeatedScope;

class SubjectFormLoaderController extends Controller
{
    public function showSubjectForm($studyId, $subjectId)
    {
        /***************/
        $studyId = isset($studyId) ? $studyId : 0;
        $studyClsStr = buildSafeStr($studyId, 'study_cls_');
        $study = Study::find($studyId);

        $subjectId = isset($subjectId) ? $subjectId : 0;
        $subject = Subject::find($subjectId);

        $site = Site::find($subject->site_id);
        $studySite = StudySite::where('study_id', $study->id)->where('site_id', $site->id)->firstOrNew();

        $form_filled_by_user_id = auth()->user()->id;

        $subjectPhasesIdsArray = $subject->subjectPhasesArray();
        $visitPhases = StudyStructure::where('study_id', $studyId)
            ->whereIn('id', $subjectPhasesIdsArray)
            ->withoutGlobalScope(StudyStructureWithoutRepeatedScope::class)
            ->get();
        /*****************/

        $visitActivationPreference = Preference::find(1);

        session(['stepToActivateStr' => '']);
        return view('formsubmission::subjectFormLoader.subject_form')
            ->with('subjectId', $subjectId)
            ->with('studyId', $studyId)
            ->with('visitPhases', $visitPhases)
            ->with('studyClsStr', $studyClsStr)
            ->with('study', $study)
            ->with('subject', $subject)
            ->with('site', $site)
            ->with('studySite', $studySite)
            ->with('visitActivationPreference', $visitActivationPreference)
            ->with('form_filled_by_user_id', $form_filled_by_user_id);
    }
}
