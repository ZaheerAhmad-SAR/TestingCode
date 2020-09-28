<?php

namespace Modules\UserRoles\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;

class StudyRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (hasPermission(auth()->user(),'studytools.index')){
            $study_roles  =  Role::where('role_type','=','study_role')->orderBY('name','asc')->get();
        }
//        $permissions = Permission::all();
        $permissions = Permission::where('controller_name','=','grading')
            ->orwhere('controller_name','=','qualitycontrol')
            ->orwhere('controller_name','=','studytools')
            ->orwhere('controller_name','=','systemtools')
            ->get();

        return view('userroles::roles.studyroles_index',compact('study_roles','permissions'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('userroles::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('userroles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('userroles::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
