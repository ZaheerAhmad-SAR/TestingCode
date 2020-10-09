<?php

namespace Modules\Queries\Http\Controllers;

use App\User;
use http\Env\Response;
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
     $id              = Str::uuid();

            if ($queryAssignedTo == 'users')
            {
                $query           = Query::create([ 'id'=>$id, 'parent_query_id'=> 0,'messages'=>$remarks]);
                foreach ($users as $user)
                {
                    $roles = (array)null;
                    QueryUser::create(['id' => Str::uuid(), 'user_id' => $user, 'query_id' => $id]);
                }
            }

            if ($queryAssignedTo == 'roles')
            {
                $query           = Query::create([ 'id'=>$id, 'parent_query_id'=> 0,'messages'=>$remarks]);
                foreach ($roles as $role) {
                    $users = (array)null;
                    RoleQuery::create(['id' => Str::uuid(), 'roles_id' => $role, 'query_id' => $id]);
                }
            }

        return response()->json([$query,'success'=>'Queries is generate successfully!!!!']);

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
