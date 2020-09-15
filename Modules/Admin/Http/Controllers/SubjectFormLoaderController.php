<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;

class SubjectFormLoaderController extends Controller
{
    public function showSubjectForm($subjectId)
    {
        $studyId = session('current_study');
        $visitPhases = StudyStructure::where('study_id', $studyId)->get();
        
        return view('admin::subjectFormLoader.subject_form')
        ->with('subjectId', $subjectId)
        ->with('studyId', $studyId)
        ->with('visitPhases', $visitPhases);
    }
}
