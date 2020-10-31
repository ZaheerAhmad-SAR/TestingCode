<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\StudyStructure;
use Modules\FormSubmission\Entities\SubjectsPhases;
use Modules\Admin\Entities\Subject;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class AssignPhaseToSubjectController extends Controller
{
    use ReplicatePhaseStructure;

    public function loadAssignPhaseToSubjectForm(Request $request)
    {
        $subject = Subject::find($request->subjectId);
        $subjectAssignedPhasesIdsArray = $subject->subjectPhasesArray();
        $repeatablePhasesIdsArray = StudyStructure::where('is_repeatable', 1)->pluck('id')->toArray();
        $notToShowPhasesIdsArray = array_diff($subjectAssignedPhasesIdsArray, $repeatablePhasesIdsArray);

        $visitPhases = StudyStructure::where('study_id', $request->studyId)->whereNotIn('id', $notToShowPhasesIdsArray)->get();

        echo view('formsubmission::subjectFormLoader.assignPhasesToSubjectPopupForm')
            ->with('visitPhases', $visitPhases)
            ->with('subject', $subject);
    }

    public function submitAssignPhaseToSubjectForm(Request $request)
    {
        $subjectPhase = SubjectsPhases::where('subject_id', $request->subject_id)->where('phase_id', $request->phase_id)->first();
        if (null !== $subjectPhase) {
            $request->phase_id = $this->replicatePhaseStructure($request->phase_id);
        }
        SubjectsPhases::createSubjectPhase($request);
        echo 'success';
    }
}
