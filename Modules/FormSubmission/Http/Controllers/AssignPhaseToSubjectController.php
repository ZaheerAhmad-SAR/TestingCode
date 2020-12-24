<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\StudyStructure;
use Modules\FormSubmission\Entities\SubjectsPhases;
use Modules\Admin\Entities\Subject;
use Modules\FormSubmission\Entities\AdjudicationFormStatus;
use Modules\FormSubmission\Entities\Answer;
use Modules\FormSubmission\Entities\FinalAnswer;
use Modules\FormSubmission\Entities\FormStatus;
use Modules\FormSubmission\Traits\Replication\ReplicatePhaseStructure;

class AssignPhaseToSubjectController extends Controller
{
    use ReplicatePhaseStructure;

    public function loadAssignPhaseToSubjectForm(Request $request)
    {
        $subject = Subject::find($request->subjectId);
        $subjectAssignedPhasesIdsArray = $subject->subjectPhasesArray();
        $repeatablePhasesIdsArray = StudyStructure::where('is_repeatable', 1)->withOutRepeated()->pluck('id')->toArray();
        $notToShowPhasesIdsArray = array_diff($subjectAssignedPhasesIdsArray, $repeatablePhasesIdsArray);

        $visitPhases = StudyStructure::where('study_id', $request->studyId)->withOutRepeated()->whereNotIn('id', $notToShowPhasesIdsArray)->get();

        echo view('formsubmission::subjectFormLoader.assignPhasesToSubjectPopupForm')
            ->with('visitPhases', $visitPhases)
            ->with('subject', $subject);
    }

    public function submitAssignPhaseToSubjectForm(Request $request)
    {
        $modalityIdsInSubjectPhasesArray = SubjectsPhases::where('subject_id', $request->subject_id)->where('phase_id', $request->phase_id)->pluck('modility_id')->toArray();
        $modalityIdsInPhaseSteps = PhaseSteps::where('phase_id', 'like', $request->phase_id)->pluck('modility_id')->toArray();

        $modalityIdsInSubjectPhasesArray = array_unique($modalityIdsInSubjectPhasesArray);
        $modalityIdsInPhaseSteps = array_unique($modalityIdsInPhaseSteps);

        if (count($modalityIdsInSubjectPhasesArray) == count($modalityIdsInPhaseSteps)) {
            $request->phase_id = $this->replicatePhaseStructure($request->phase_id);
            $modalityIdsInPhaseSteps = PhaseSteps::where('phase_id', 'like', $request->phase_id)->pluck('modility_id')->toArray();
            $modalityIdsInPhaseSteps = array_unique($modalityIdsInPhaseSteps);
            SubjectsPhases::createSubjectPhase($request, $modalityIdsInPhaseSteps);
        } else {
            $modalityIdsArray = array_diff($modalityIdsInPhaseSteps, $modalityIdsInSubjectPhasesArray);
            SubjectsPhases::createSubjectPhase($request, $modalityIdsArray);
        }
        echo 'success';
    }


    public function unAssignPhaseToSubject(Request $request)
    {
        /** ------------------ Amir code --------------------------**/
        $oldPhase = [];

        // trail log
        $getSubjectPhase = SubjectsPhases::where('subject_id', 'like', $request->subjectId)->where('phase_id', 'like', $request->phaseId)->first();

        // log event details
        $logEventDetails = eventDetails($getSubjectPhase, 'Phase', 'Deactivate', 'N/A', $oldPhase);

        /** ------------- Amir code ends -------------------------**/

        $subjectId = $request->subjectId;
        $phaseId = $request->phaseId;
        $phase = StudyStructure::find($phaseId);
        if ($phase->parent_id != 'no-parent' && $phase->replicating_or_cloning == 'replicating') {
            $this->deletePhase($phase, true);
        }

        SubjectsPhases::where('subject_id', 'like', $subjectId)->where('phase_id', 'like', $phaseId)->delete();

        Answer::where('subject_id', 'like', $subjectId)->where('study_structures_id', 'like', $phaseId)->delete();
        FinalAnswer::where('subject_id', 'like', $subjectId)->where('study_structures_id', 'like', $phaseId)->delete();

        FormStatus::where('subject_id', 'like', $subjectId)->where('study_structures_id', 'like', $phaseId)->delete();
        AdjudicationFormStatus::where('subject_id', 'like', $subjectId)->where('study_structures_id', 'like', $phaseId)->delete();

        echo 'success';
    }
}
