<?php

namespace Modules\Queries\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Queries\Entities\Query;
use Modules\Queries\Entities\QueryUser;
use Modules\Queries\Entities\RoleQuery;
use phpDocumentor\Reflection\Types\Null_;

class QueriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $users  =   User::where('id','!=',\auth()->user()->id)->get();
        //dd($users);
        return view('queries::queries.index',compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('queries::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

     $remarks         = $request->post('assignedRemarks');
     $queryAssignedTo = $request->post('queryAssignedTo');
     $roles           = $request->post('assignedRoles');
     $users           = $request->post('assignedUsers');

     if ($queryAssignedTo == 'users'){
         $roles = Null;
         $id = Str::uuid();
         foreach ($users as $user)
         {
            QueryUser::create(['id'=>$id, 'user_id'=>$user]);
         }
     }
     if ($queryAssignedTo == 'roles'){
         $users = Null;
         $id = Str::uuid();
         foreach ($roles as $role)
         {
            RoleQuery::create(['id'=>$id, 'roles_id'=>$role]);
         }

         Query::create([
          'id'=>Str::uuid(),
          'parent_query_id'=> '0',
          'messages'=>$remarks
         ]);
     }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('queries::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('queries::edit');
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
