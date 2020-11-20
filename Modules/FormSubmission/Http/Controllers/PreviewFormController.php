<?php

namespace Modules\FormSubmission\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;

class PreviewFormController extends Controller
{
    public function show($phase_id, $step_id)
    {
        $phase = StudyStructure::find($phase_id);
        $step = PhaseSteps::find($step_id);

        return view('formsubmission::forms.preview_form')
            ->with('isPreview', true)
            ->with('studyId', '')
            ->with('studyClsStr', '')
            ->with('subjectId', '')
            ->with('stepClsStr', '')
            ->with('stepIdStr', '')
            ->with('skipLogicStepIdStr', '')
            ->with('phase', $phase)
            ->with('step', $step);
    }
}
