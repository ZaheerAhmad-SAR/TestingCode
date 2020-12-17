<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\PhaseSteps;

class QcQuestionToShowController extends Controller
{
    public function openShowQuestionsToGraderPopUp(Request $request)
    {
        $step = PhaseSteps::find($request->stepId);
        $qcStep = PhaseSteps::where('phase_id', 'like', $request->phaseId)
            ->where('form_type_id', 'like', '1')
            ->where('modility_id', 'like', $step->modility_id)
            ->first();

        echo view('formsubmission::qc_question_to_show.qcQuestionsToShowListing')
            ->with('qcStep', $qcStep)
            ->with('subjectId', $request->subjectId)
            ->with('studyId', $request->studyId)
            ->with('phaseId', $request->phaseId);
    }
}
