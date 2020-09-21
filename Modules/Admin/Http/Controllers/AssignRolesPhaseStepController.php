<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Admin\Entities\StudyStructure;
use Modules\Admin\Entities\PhaseSteps;
use Modules\UserRoles\Entities\Role;

class AssignRolesPhaseStepController extends Controller
{
    public function getAssignRolesToPhaseForm(Request $request)
    {
        $phase = StudyStructure::find($request->phase_id);
        $roles = Role::all();
        $phaseRolesIdsArray = $phase->roles()->pluck('roles.id')->toArray();

        echo view('admin::assignRolesPhaseStep.assign_roles_to_phase_form')
        ->with('phase', $phase)
        ->with('roles', $roles)
        ->with('phaseRolesIdsArray', $phaseRolesIdsArray);
    }

    public function submitAssignRolesToPhaseForm(Request $request)
    {
        $phase = StudyStructure::find($request->phase_id);
        $phase->roles()->sync($request->roles);

        echo view('admin::assignRolesPhaseStep.assign_roles_msg');
    }

    public function getAssignRolesToPhaseStepForm(Request $request)
    {
        $phaseStep = PhaseSteps::find($request->step_id);
        $phase = StudyStructure::find($phaseStep->phase_id);
        $roles = Role::all();
        $phaseStepRolesIdsArray = $phaseStep->roles()->pluck('roles.id')->toArray();

        echo view('admin::assignRolesPhaseStep.assign_roles_to_phase_steps_form')
        ->with('phase', $phase)
        ->with('phaseStep', $phaseStep)
        ->with('roles', $roles)
        ->with('phaseStepRolesIdsArray', $phaseStepRolesIdsArray);
    }

    public function submitAssignRolesToPhaseStepForm(Request $request)
    {
        $phaseStep = PhaseSteps::find($request->step_id);
        $phaseStep->roles()->sync($request->roles);

        echo view('admin::assignRolesPhaseStep.assign_roles_msg');
    }
}
