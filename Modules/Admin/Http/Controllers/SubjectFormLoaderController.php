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
        $userRoleIds = auth()->user()->user_roles()->pluck('role_id')->toArray();
        //$loginUser = \App\User::find('49021e35-8e14-4cbf-b4e2-0dd590a4cff4');
        //$userRoleIds = $loginUser->user_roles()->pluck('role_id')->toArray();
        
        $studyId = session('current_study');
        $visitPhases = StudyStructure::phasesbyRoles($studyId, $userRoleIds);
        
        return view('admin::subjectFormLoader.subject_form')        
        ->with('userRoleIds', $userRoleIds)
        ->with('subjectId', $subjectId)
        ->with('studyId', $studyId)
        ->with('visitPhases', $visitPhases);
    }
}
