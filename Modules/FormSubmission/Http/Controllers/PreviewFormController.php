<?php

namespace Modules\FormSubmission\Http\Controllers;

use App\User;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;
use Modules\Admin\Entities\Site;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudySite;
use Modules\Admin\Entities\Subject;
use Modules\FormSubmission\Entities\FormStatus;

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

    public function printForm($studyId, $subjectId, $phaseId, $stepId, $formFilledByUserId)
    {
        $studyId = isset($studyId) ? $studyId : 0;
        $study = Study::find($studyId);

        $subjectId = isset($subjectId) ? $subjectId : 0;
        $subject = Subject::find($subjectId);

        $site = Site::find($subject->site_id);
        $studySite = StudySite::where('study_id', $study->id)->where('site_id', $site->id)->firstOrNew();

        $current_user_id = auth()->user()->id;
        $formFilledByUser = User::find($formFilledByUserId);

        /*****************/
        $phase = StudyStructure::find($phaseId);
        $phaseIdStr = buildSafeStr($phaseId, 'phase_cls_');
        $step = PhaseSteps::find($stepId);

        /********************************** */
        $getFormStatusArray = [
            'subject_id' => $subjectId,
            'study_id' => $studyId,
            'study_structures_id' => $phase->id,
            'phase_steps_id' => $step->step_id,
            'form_type_id' => $step->form_type_id,
            'modility_id' => $step->modility_id,
            'form_filled_by_user_id' => $formFilledByUserId,
        ];

        $formStatusObj = FormStatus::getFormStatusObj($getFormStatusArray);
        /********************************** */

        return view('formsubmission::print.print_form')
            ->with('isPreview', true)
            ->with('subjectId', $subjectId)
            ->with('studyId', $studyId)
            ->with('study', $study)
            ->with('subject', $subject)
            ->with('site', $site)
            ->with('studySite', $studySite)
            ->with('phase', $phase)
            ->with('phaseIdStr', $phaseIdStr)
            ->with('step', $step)
            ->with('current_user_id', $current_user_id)
            ->with('formFilledByUserId', $formFilledByUserId)
            ->with('formFilledByUser', $formFilledByUser)
            ->with('formStatusObj', $formStatusObj)
            ->with('activeStep', '');
    }
}
