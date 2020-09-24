<?php

namespace Modules\Admin\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\UserRoles\Entities\Role;

class StudyRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $records = array(
        [
        'name'=>'Chris',
        'email'=>'chris@oirrc.net',
         'roles'=>'Quality Control'
        ],
       [
        'name'=>'Edwin',
        'email'=>'edwin@oirrc.net',
         'roles'=>'Project Manager'
        ],
       [
        'name'=>'Angus',
        'email'=>'angus@oirrc.net',
         'roles'=>'Grader'
        ],
         [
        'name'=>'Eric',
        'email'=>'eric@oirrc.net',
         'roles'=>'Guest'
        ],
       );

        return view('admin::studyrole.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
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
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
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
