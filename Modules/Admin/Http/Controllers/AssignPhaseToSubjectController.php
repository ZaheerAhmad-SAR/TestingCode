<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\SubjectsPhases;
use Modules\Admin\Entities\Subject;

class AssignPhaseToSubjectController extends Controller
{
    public function loadAssignPhaseToSubjectForm(Request $request)
    {
        $subject = Subject::find($request->subjectId);
        $subjectPhasesIdsArray = $subject->subjectPhasesArray();
        $visitPhases = StudyStructure::where('study_id', $request->studyId)->whereNotIn('id', $subjectPhasesIdsArray)->get();

        echo view('admin::subjectFormLoader.assignPhasesToSubjectPopupForm')
            ->with('visitPhases', $visitPhases)
            ->with('subject', $subject);
    }

    public function submitAssignPhaseToSubjectForm(Request $request)
    {
        $data = [
            'id' => Str::uuid(),
            'subject_id' => $request->subject_id,
            'phase_id' => $request->phase_id,
            'visit_date' => $request->visit_date,
            'is_out_of_window' => $request->is_out_of_window,
        ];
        SubjectsPhases::create($data);
        echo 'success';
    }
}
