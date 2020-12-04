<?php

namespace Modules\UserRoles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests as Requests;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\RolePermission;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Http\Requests\RoleRequest;
use Datatables;
use Psy\Util\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        /* if (hasPermission(auth()->user(),'systemtools.index')){*/
        $system_roles  =  Role::where('role_type', '=', 'system_role')->orderBY('name', 'asc')->get();
        $study_roles  =  Role::where('role_type', '=', 'study_role')->orderBY('name', 'asc')->get();
        /*}*/

        //
        $permissions = Permission::where('controller_name', '=', 'grading')
            ->orwhere('controller_name', '=', 'qualitycontrol')
            ->orwhere('controller_name', '=', 'studytools')
            ->orwhere('controller_name', '=', 'systemtools')
            ->get();

        return view('userroles::roles.index', compact('study_roles', 'system_roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //        $permissions = Permission::get();
        $permissions = Permission::where('controller_name', '=', 'grading')->get();

        return view('userroles::roles.create')->with(compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(RoleRequest $request)
    {
        $role =  Role::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'name'  =>  $request->name,
            'description'   =>  $request->description,
            'role_type' => $request->role_type_name,
            'created_by'    => auth()->user()->id,
        ]);

        $this->updateRolePermissions($request, $role);

        $oldRole = [];

        // log event details
        $logEventDetails = eventDetails($role->id, 'Role', 'Add', $request->ip(), $oldRole);

        return redirect()->route('roles.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('userroles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $role   =   Role::find(decrypt($id));
        $permissions = RolePermission::where('role_id', '=', $role->id)
            ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
            ->get();


        return view('userroles::roles.new_edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     * @param RoleRequest $request
     * @param int $id
     * @return Response
     */
    public function update(RoleRequest $request, $id)
    {
        RolePermission::where('role_id', 'like', $id)->delete();
        // get old roles data for trail log
        $oldRole = Role::where('id', $id)->first();
        $role   =   Role::find($id);
        $role->update([
            'id'    => $id,
            'name'  =>  $request->name,
            'description'   =>  $request->description,
            'role_type'     => $request->role_type
        ]);

        $this->updateRolePermissions($request, $role);

        // log event details
        $logEventDetails = eventDetails($role->id, 'Role', 'Update', $request->ip(), $oldRole);

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function updateRolePermissions($request, $role)
    {
        /*--Basic Role Permission */
        if ($request->dashboard_add == 'on') {
            $permissions = Permission::where('name', '=', 'dashboard.create')
                ->orwhere('name', '=', 'dashboard.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->dashboard_edit == 'on') {
            $permissions = Permission::where('name', '=', 'dashboard.edit')
                ->orwhere('name', '=', 'dashboard.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->dashboard_view == 'on') {
            $permissions = Permission::where('name', '=', 'dashboard.index')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->dashboard_delete == 'on') {
            $permissions = Permission::where('name', '=', 'dashboard.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Studies Permissions */
        if ($request->study_add == 'on') {
            $permissions = Permission::where('name', '=', 'studies.create')
                ->orwhere('name', '=', 'studies.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->study_edit == 'on') {
            $permissions = Permission::where('name', '=', 'studies.edit')
                ->orwhere('name', '=', 'studies.update')
                ->orwhere('name', '=', 'diseaseCohort.index')
                ->orwhere('name', '=', 'diseaseCohort.create')
                ->orwhere('name', '=', 'diseaseCohort.save')
                ->orwhere('name', '=', 'diseaseCohort.edit')
                ->orwhere('name', '=', 'diseaseCohort.update')
                ->orwhere('name', '=', 'diseaseCohort.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->study_view == 'on') {
            //dd('log store');
            $permissions = Permission::where('name', '=', 'studies.index')
                                        ->get();

            $this->createRolePermissions($role, $permissions);
        }
        if ($request->study_delete == 'on') {
            $permissions = Permission::where('name', '=', 'studies.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Subjects Permissions */
        if ($request->subjects_add == 'on') {
            $permissions = Permission::where('name', '=', 'subjects.create')
                ->orwhere('name', '=', 'subjects.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->subjects_edit == 'on') {
            $permissions = Permission::where('name', '=', 'subjects.edit')
                ->orwhere('name', '=', 'subjects.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->subjects_view == 'on') {
            $permissions = Permission::where('name', '=', 'subjects.index')
                ->orwhere('name', '=', 'studies.show')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->subjects_delete == 'on') {
            $permissions = Permission::where('name', '=', 'subjects.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Grading Permissions */
        if ($request->grading_add == 'on') {
            $permissions = Permission::where('name', '=', 'grading.create')
                ->orwhere('name', '=', 'grading.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->grading_edit == 'on') {
            $permissions = Permission::where('name', '=', 'grading.edit')
                ->orwhere('name', '=', 'grading.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        // if add/edit
        if ($request->grading_add == 'on' && $request->grading_edit == 'on') {
            $permissions = Permission::where('name', '=', 'gradingcontrol.grading-work-list')
                                        ->get();
            $this->createRolePermissions($role, $permissions);
        }
        //ends add/edit check

        if ($request->grading_view == 'on') {
            $permissions = Permission::where('name', '=', 'grading.index')
                ->orwhere('name', 'excel-grading')
                ->orwhere('name', 'excel-grading2')
                ->orwhere('name', 'excel.grading-status')
                ->orwhere('name', 'excel.grading-status2')
                ->orwhere('name', 'grading.status')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        // if study tool and grading view permission is assign only them allow grading status
        if ($request->study_tools == 'on' && $request->grading_view == 'on') {

            $permissions = Permission::where('name', 'grading.status')
                                       ->get();
            $this->createRolePermissions($role, $permissions);
        }

        if ($request->grading_delete == 'on') {
            $permissions = Permission::where('name', '=', 'grading.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Quality Control Permissions */
        if ($request->qualityControl_add == 'on') {
            $permissions = Permission::where('name', '=', 'qualitycontrol.create')
                ->orwhere('name', '=', 'qualitycontrol.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->qualityControl_edit == 'on') {
            $permissions = Permission::where('name', '=', 'qualitycontrol.edit')
                ->orwhere('name', '=', 'qualitycontrol.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        // if add/edit are assigned
        if ($request->qualityControl_add == 'on' && $request->qualityControl_edit == 'on') {

            $permissions = Permission::where('name', '=', 'transmissions.study-transmissions')
                ->orwhere('name', '=', 'transmissions-study-edit')
                ->orwhere('name', '=', 'qualitycontrol.qc-work-list')
                ->get();

            $this->createRolePermissions($role, $permissions);

        } // check for add/edit ends

        if ($request->qualityControl_view == 'on') {
            $permissions = Permission::where('name', '=', 'qualitycontrol.index')
                ->orwhere('name', 'excel-qc')
                ->orwhere('name', 'excel-qc2')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->qualityControl_delete == 'on') {
            $permissions = Permission::where('name', '=', 'qualitycontrol.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Adjudication Permissions --*/
        if ($request->adjudication_add == 'on') {
            $permissions = Permission::where('name', '=', 'adjudication.create')
                ->orwhere('name', '=', 'adjudication.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->adjudication_edit == 'on') {
            $permissions = Permission::where('name', '=', 'adjudication.edit')
                ->orwhere('name', '=', 'adjudication.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        if ($request->adjudication_view == 'on') {
            $permissions = Permission::where('name', '=', 'adjudication.index')
                ->orwhere('name', '=', 'excel-adjudication')
                ->orwhere('name', '=', 'excel-adjudication2')
                ->orwhere('name', '=', 'adjudicationcontroller.adjudication-work-list')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->adjudication_delete == 'on') {
            $permissions = Permission::where('name', '=', 'adjudication.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Eligibility Permissions --*/
        if ($request->eligibility_add == 'on') {
            $permissions = Permission::where('name', '=', 'eligibility.create')
                ->orwhere('name', '=', 'eligibility.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->eligibility_edit == 'on') {
            $permissions = Permission::where('name', '=', 'eligibility.edit')
                ->orwhere('name', '=', 'eligibility.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->eligibility_view == 'on') {
            $permissions = Permission::where('name', '=', 'eligibility.index')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->eligibility_delete == 'on') {
            $permissions = Permission::where('name', '=', 'eligibility.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Queries Permissions --*/
        if ($request->queries_add == 'on') {
            $permissions = Permission::where('name', '=', 'queries.create')
                ->orwhere('name', '=', 'queries.store')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->queries_edit == 'on') {
            $permissions = Permission::where('name', '=', 'queries.edit')
                ->orwhere('name', '=', 'queries.update')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->queries_view == 'on') {
            $permissions = Permission::where('name', '=', 'queries.index')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
        if ($request->queries_delete == 'on') {
            $permissions = Permission::where('name', '=', 'queries.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- System Tools Permissions */
        if ($request->system_tools == 'on') {
            $permissions = Permission::where('name', '=', 'systemtools.index')
                ->orwhere('name', '=', 'users.create')
                ->orwhere('name', '=', 'users.index')
                ->orwhere('name', '=', 'users.assignUsers')
                ->orwhere('name', '=', 'users.update_user')
                ->orwhere('name', '=', 'users.store')
                ->orwhere('name', '=', 'users.edit')
                ->orwhere('name', '=', 'users.update')
                ->orwhere('name', '=', 'users.destroy')
                ->orwhere('name', '=', 'users.show')
                ->orwhere('name', '=', 'studyusers.create')
                ->orwhere('name', '=', 'studyusers.index')
                ->orwhere('name', '=', 'studyusers.assignUsers')
                ->orwhere('name', '=', 'studyusers.update_user')
                ->orwhere('name', '=', 'studyusers.store')
                ->orwhere('name', '=', 'studyusers.edit')
                ->orwhere('name', '=', 'studyusers.update')
                ->orwhere('name', '=', 'studyusers.destroy')
                ->orwhere('name', '=', 'studyusers.show')
                ->orwhere('name', '=', 'devices.index')
                ->orwhere('name', '=', 'devices.create')
                ->orwhere('name', '=', 'devices.store')
                ->orwhere('name', '=', 'devices.edit')
                ->orwhere('name', '=', 'devices.update')
                ->orwhere('name', '=', 'devices.destroy')
                ->orwhere('name', '=', 'roles.index')
                ->orwhere('name', '=', 'roles.create')
                ->orwhere('name', '=', 'roles.store')
                ->orwhere('name', '=', 'roles.edit')
                ->orwhere('name', '=', 'roles.update')
                ->orwhere('name', '=', 'roles.destroy')
                ->orwhere('name', '=', 'sites.index')
                ->orwhere('name', '=', 'sites.create')
                ->orwhere('name', '=', 'sites.store')
                ->orwhere('name', '=', 'sites.edit')
                ->orwhere('name', '=', 'sites.update')
                ->orwhere('name', '=', 'sites.destroy')
                ->orwhere('name', '=', 'modalities.index')
                ->orwhere('name', '=', 'modalities.create')
                ->orwhere('name', '=', 'modalities.store')
                ->orwhere('name', '=', 'modalities.edit')
                ->orwhere('name', '=', 'modalities.update')
                ->orwhere('name', '=', 'modalities.destroy')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Study Tools Permissions */
        if ($request->study_tools == 'on') {
            $permissions = Permission::where('name', '=', 'studytools.index')
                ->orwhere('name', '=', 'users.create')
                ->orwhere('name', '=', 'users.index')
                ->orwhere('name', '=', 'users.assignUsers')
                ->orwhere('name', '=', 'users.update_user')
                ->orwhere('name', '=', 'users.store')
                ->orwhere('name', '=', 'users.edit')
                ->orwhere('name', '=', 'users.update')
                ->orwhere('name', '=', 'users.destroy')
                ->orwhere('name', '=', 'users.show')
                ->orwhere('name', '=', 'studyusers.create')
                ->orwhere('name', '=', 'studyusers.index')
                ->orwhere('name', '=', 'studyusers.assignUsers')
                ->orwhere('name', '=', 'studyusers.update_user')
                ->orwhere('name', '=', 'studyusers.store')
                ->orwhere('name', '=', 'studyusers.edit')
                ->orwhere('name', '=', 'studyusers.update')
                ->orwhere('name', '=', 'studyusers.destroy')
                ->orwhere('name', '=', 'studyusers.show')
                ->orwhere('name', '=', 'studyRoles.index')
                ->orwhere('name', '=', 'studySite.index')
                ->orwhere('name', '=', 'studySite.create')
                ->orwhere('name', '=', 'studySite.store')
                ->orwhere('name', '=', 'studySite.edit')
                ->orwhere('name', '=', 'studySite.update')
                ->orwhere('name', '=', 'studySite.destroy')
                ->orwhere('name', '=', 'studySite.updateStudySite')
                ->orwhere('name', '=', 'studySite.updatePrimaryInvestigator')
                ->orwhere('name', '=', 'studySite.insertCoordinators')
                ->orwhere('name', '=', 'studySite.deleteSiteCoordinator')
                ->orwhere('name', '=', 'studydesign.index')
                ->orwhere('name', '=', 'studydesign.create')
                ->orwhere('name', '=', 'studydesign.store')
                ->orwhere('name', '=', 'studydesign.edit')
                ->orwhere('name', '=', 'studydesign.update')
                ->orwhere('name', '=', 'studydesign.destory')
                ->orwhere('name', '=', 'study.index')
                ->orwhere('name', '=', 'study.create')
                ->orwhere('name', '=', 'study.store')
                ->orwhere('name', '=', 'study.edit')
                ->orwhere('name', '=', 'study.update')
                ->orwhere('name', '=', 'study.destroy')
                ->orwhere('name', '=', 'steps.update')
                ->orwhere('name', '=', 'steps.store')
                ->orwhere('name', '=', 'sections.index')
                ->orwhere('name', '=', 'sections.create')
                ->orwhere('name', '=', 'sections.store')
                ->orwhere('name', '=', 'sections.edit')
                ->orwhere('name', '=', 'sections.update')
                ->orwhere('name', '=', 'sections.destroy')
                ->orwhere('name', '=', 'steps.save')
                ->orwhere('name', '=', 'steps.update')
                ->orwhere('name', '=', 'forms.index')
                ->orwhere('name', '=', 'forms.create')
                ->orwhere('name', '=', 'forms.store')
                ->orwhere('name', '=', 'forms.edit')
                ->orwhere('name', '=', 'forms.update')
                ->orwhere('name', '=', 'optionsGroup.index')
                ->orwhere('name', '=', 'optionsGroup.create')
                ->orwhere('name', '=', 'optionsGroup.store')
                ->orwhere('name', '=', 'optionsGroup.edit')
                ->orwhere('name', '=', 'optionsGroup.update')
                ->orwhere('name', '=', 'diseaseCohort.index')
                ->orwhere('name', '=', 'diseaseCohort.create')
                ->orwhere('name', '=', 'diseaseCohort.save')
                ->orwhere('name', '=', 'diseaseCohort.edit')
                ->orwhere('name', '=', 'diseaseCohort.update')
                ->orwhere('name', '=', 'diseaseCohort.destroy')
                // ASSIGN WORK PERMISSIONS
                ->orwhere('name', '=', 'assign-work')
                ->orwhere('name', '=', 'save-assign-work')
                ->orwhere('name', '=', 'get-form-type-users')
                ->orwhere('name', '=', 'check-assign-work')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Data management Permissions */
        if ($request->management == 'on') {
            $permissions = Permission::where('name', '=', 'data_management.index')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Activity Log Permissions */
        if ($request->activity_log == 'on') {
            $permissions = Permission::where('name', '=', 'trail_logs.list')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Certification Permissions */
        if ($request->certification == 'on') {
            $permissions = Permission::where('name', '=', 'certification.index')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }

        /*-- Finance Permissions */
        if ($request->finance == 'on') {
            $permissions = Permission::where('name', '=', 'finance.index')
                ->get();
            $this->createRolePermissions($role, $permissions);
        }
    }
    private function createRolePermissions($role, $permissions)
    {
        foreach ($permissions as $permission) {
            RolePermission::create([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ]);
        }
    }
}
