<?php

namespace Modules\UserRoles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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
        $roles  =  Role::orderBY('name','asc')->get();
//        $permissions = Permission::all();
        $permissions = Permission::where('controller_name','=','grading')
            ->orwhere('controller_name','=','qualitycontrol')
            ->orwhere('controller_name','=','studytools')
            ->orwhere('controller_name','=','systemtools')
            ->get();

        return view('userroles::roles.index',compact('roles','permissions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
//        $permissions = Permission::get();
         $permissions = Permission::where('controller_name','=','grading')->get();

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
            'created_by'    => auth()->user()->id,
        ]);
        /*-- Studies Permissions */
        if ($request->study_add){
          $permissions = Permission::where('name','=','studies.create')
              ->orwhere('name','=','studies.store')
            ->get();
            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->study_edit){
          $permissions = Permission::where('name','=','studies.edit')
              ->orwhere('name','=','studies.update')
            ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->study_view){
          $permissions = Permission::where('name','=','studies.index')
            ->get();
            dd('only this');

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->study_delete){
          $permissions = Permission::where('name','=','studies.destroy')
            ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Subjects Permissions */
        if ($request->subjects_add){
            $permissions = Permission::where('name','=','subjects.create')
                ->orwhere('name','=','subjects.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->subjects_edit){
            $permissions = Permission::where('name','=','subjects.edit')
                ->orwhere('name','=','subjects.update')
                ->get();
            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->subjects_view){
            $permissions = Permission::where('name','=','subjects.index')
                ->orwhere('name','=','studies.show')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->subjects_delete){
            $permissions = Permission::where('name','=','subjects.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Grading Permissions */
        if ($request->grading_add){
            $permissions = Permission::where('name','=','grading.create')
                ->orwhere('name','=','grading.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->grading_edit){
            $permissions = Permission::where('name','=','grading.edit')
                ->orwhere('name','=','grading.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->grading_view){
            $permissions = Permission::where('name','=','grading.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->grading_delete){
            $permissions = Permission::where('name','=','grading.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Quality Control Permissions */
        if ($request->qualityControl_add){
            $permissions = Permission::where('name','=','qualitycontrol.create')
                ->orwhere('name','=','qualitycontrol.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->qualityControl_edit){
            $permissions = Permission::where('name','=','qualitycontrol.edit')
                ->orwhere('name','=','qualitycontrol.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->qualityControl_view){
            $permissions = Permission::where('name','=','qualitycontrol.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->qualityControl_delete){
            $permissions = Permission::where('name','=','qualitycontrol.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Adjudication Permissions --*/
        if ($request->adjudication_add){
            $permissions = Permission::where('name','=','adjudication.create')
                ->orwhere('name','=','adjudication.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->adjudication_edit){
            $permissions = Permission::where('name','=','adjudication.edit')
                ->orwhere('name','=','adjudication.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->adjudication_view){
            $permissions = Permission::where('name','=','adjudication.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->adjudication_delete){
            $permissions = Permission::where('name','=','adjudication.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Eligibility Permissions --*/
        if ($request->eligibility_add){
            $permissions = Permission::where('name','=','eligibility.create')
                ->orwhere('name','=','eligibility.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->eligibility_edit){
            $permissions = Permission::where('name','=','eligibility.edit')
                ->orwhere('name','=','eligibility.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->eligibility_view){
            $permissions = Permission::where('name','=','eligibility.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->eligibility_delete){
            $permissions = Permission::where('name','=','eligibility.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Queries Permissions --*/
        if ($request->queries_add){
            $permissions = Permission::where('name','=','queries.create')
                ->orwhere('name','=','queries.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->queries_edit){
            $permissions = Permission::where('name','=','queries.edit')
                ->orwhere('name','=','queries.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->queries_view){
            $permissions = Permission::where('name','=','queries.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->queries_delete){
            $permissions = Permission::where('name','=','queries.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- System Tools Permissions */
        if ($request->system_tools ) {
            $permissions = Permission::where('name', '=', 'systemtools.index')
                ->orwhere('name', '=', 'devices.index')
                ->orwhere('name', '=', 'users.index')
                ->orwhere('name', '=', 'roles.index')
                ->orwhere('name', '=', 'sites.index')
                ->orwhere('name', '=', 'modalities.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $system_tools = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Study Tools Permissions */
        if ($request->study_tools ) {
            $permissions = Permission::where('name', '=', 'studytools.index')
                ->orwhere('name', '=', 'users.index')
                ->orwhere('name', '=', 'roles.index')
                ->orwhere('name', '=', 'studySite.index')
                ->orwhere('name', '=', 'studydesign.index')
                ->orwhere('name', '=', 'study.index')
                ->orwhere('name', '=', 'forms.index')
                ->orwhere('name', '=', 'optionsGroup.index')
                ->get();

            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $study_tools = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Data management Permissions */
        if ($request->management ) {
            $permissions = Permission::where('name', '=', 'data_management.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $management = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Activity Log Permissions */
        if ($request->activity_log ) {
            $permissions = Permission::where('name', '=', 'activitylog.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $activity_log = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Certification Permissions */
        if ($request->certification ) {
            $permissions = Permission::where('name', '=', 'certification.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $certification = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Finance Permissions */
        if ($request->finance ) {
            $permissions = Permission::where('name', '=', 'finance.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $finance = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

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
        $permissions = RolePermission::where('role_id','=',$role->id)
            ->join('permissions','permissions.id','=','permission_role.permission_id')
            ->get();


        return view('userroles::roles.new_edit',compact('role','permissions','study_add'));
    }

    /**
     * Update the specified resource in storage.
     * @param RoleRequest $request
     * @param int $id
     * @return Response
     */
    public function update(RoleRequest $request, $id)
    {
            $role   =   Role::find($id);
            $role_permissions   =   RolePermission::where('role_id','=',$id)->get();
            foreach ($role_permissions as $role_permission) {
                $role_permission->delete();
            }
            $role->update([
            'id'    => $id,
            'name'  =>  $request->name,
            'description'   =>  $request->description
        ]);
        /*-- Studies Permissions */
        if ($request->study_add){
            $permissions = Permission::where('name','=','studies.create')
                ->orwhere('name','=','studies.store')
                ->get();
            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->study_edit){
            $permissions = Permission::where('name','=','studies.edit')
                ->orwhere('name','=','studies.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->study_view){
            $permissions = Permission::where('name','=','studies.index')
                ->get();
            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->study_delete){
            $permissions = Permission::where('name','=','studies.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $study_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Subjects Permissions */
        if ($request->subjects_add){
            $permissions = Permission::where('name','=','subjects.create')
                ->orwhere('name','=','subjects.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->subjects_edit){
            $permissions = Permission::where('name','=','subjects.edit')
                ->orwhere('name','=','subjects.update')
                ->get();
            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->subjects_view){
            $permissions = Permission::where('name','=','subjects.index')
                ->orwhere('name','=','studies.show')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->subjects_delete){
            $permissions = Permission::where('name','=','subjects.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $subjects_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Grading Permissions */
        if ($request->grading_add){
            $permissions = Permission::where('name','=','grading.create')
                ->orwhere('name','=','grading.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->grading_edit){
            $permissions = Permission::where('name','=','grading.edit')
                ->orwhere('name','=','grading.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->grading_view){
            $permissions = Permission::where('name','=','grading.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->grading_delete){
            $permissions = Permission::where('name','=','grading.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Quality Control Permissions */
        if ($request->qualityControl_add){
            $permissions = Permission::where('name','=','qualitycontrol.create')
                ->orwhere('name','=','qualitycontrol.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->qualityControl_edit){
            $permissions = Permission::where('name','=','qualitycontrol.edit')
                ->orwhere('name','=','qualitycontrol.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->qualityControl_view){
            $permissions = Permission::where('name','=','qualitycontrol.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->qualityControl_delete){
            $permissions = Permission::where('name','=','qualitycontrol.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Adjudication Permissions --*/
        if ($request->adjudication_add){
            $permissions = Permission::where('name','=','adjudication.create')
                ->orwhere('name','=','adjudication.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->adjudication_edit){
            $permissions = Permission::where('name','=','adjudication.edit')
                ->orwhere('name','=','adjudication.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->adjudication_view){
            $permissions = Permission::where('name','=','adjudication.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->adjudication_delete){
            $permissions = Permission::where('name','=','adjudication.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Eligibility Permissions --*/
        if ($request->eligibility_add){
            $permissions = Permission::where('name','=','eligibility.create')
                ->orwhere('name','=','eligibility.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->eligibility_edit){
            $permissions = Permission::where('name','=','eligibility.edit')
                ->orwhere('name','=','eligibility.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->eligibility_view){
            $permissions = Permission::where('name','=','eligibility.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->eligibility_delete){
            $permissions = Permission::where('name','=','eligibility.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- Queries Permissions --*/
        if ($request->queries_add){
            $permissions = Permission::where('name','=','queries.create')
                ->orwhere('name','=','queries.store')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_add = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->queries_edit){
            $permissions = Permission::where('name','=','queries.edit')
                ->orwhere('name','=','queries.update')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_edit = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->queries_view){
            $permissions = Permission::where('name','=','queries.index')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_view = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }
        if ($request->queries_delete){
            $permissions = Permission::where('name','=','queries.destroy')
                ->get();

            foreach ($permissions as $permission){
                $permission_id = $permission->id;
                $grading_delete = RolePermission::create([
                    'role_id'   => $role->id,
                    'permission_id'   => $permission->id,
                ]);
            }
        }

        /*-- System Tools Permissions */
        if ($request->system_tools ) {
            $permissions = Permission::where('name', '=', 'systemtools.index')
                ->orwhere('name', '=', 'devices.index')
                ->orwhere('name', '=', 'users.index')
                ->orwhere('name', '=', 'roles.index')
                ->orwhere('name', '=', 'sites.index')
                ->orwhere('name', '=', 'modalities.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $system_tools = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Study Tools Permissions */
        if ($request->study_tools ) {
            $permissions = Permission::where('name', '=', 'studytools.index')
                ->orwhere('name', '=', 'users.index')
                ->orwhere('name', '=', 'roles.index')
                ->orwhere('name', '=', 'studySite.index')
                ->orwhere('name', '=', 'studydesign.index')
                ->orwhere('name', '=', 'study.index')
                ->orwhere('name', '=', 'forms.index')
                ->orwhere('name', '=', 'optionsGroup.index')
                ->get();

            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $study_tools = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Data management Permissions */
        if ($request->management ) {
            $permissions = Permission::where('name', '=', 'data_management.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $management = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Activity Log Permissions */
        if ($request->activity_log ) {
            $permissions = Permission::where('name', '=', 'activitylog.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $activity_log = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Certification Permissions */
        if ($request->certification ) {
            $permissions = Permission::where('name', '=', 'certification.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $certification = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }

        /*-- Finance Permissions */
        if ($request->finance ) {
            $permissions = Permission::where('name', '=', 'finance.index')
                ->get();
            foreach ($permissions as $permission) {
                $permission_id = $permission->id;
                $finance = RolePermission::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);

            }
        }


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
}
