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
use Modules\UserRoles\Entities\UserRole;
use phpDocumentor\Reflection\Types\Null_;

class QueriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $queries = Query::all();
        return view('queries::queries.index',compact('queries'));

    }
    public function loadHtml(Request $request)
    {
        $studyusers =  UserRole::select('users.*','user_roles.study_id','roles.role_type', 'roles.name as role_name')
            ->join('users','users.id','=','user_roles.user_id')
            ->join('roles','roles.id','=','user_roles.role_id')
            ->where('roles.role_type','!=','system_role')
            ->where('user_roles.study_id','=',$request->study_id)
            ->get();
        echo  view('queries::queries.usersdropdown',compact('studyusers'));
    }

    public function loadAllQueriesByStudyId(Request $request)
    {
    if ($request->ajax())
    {
        $study_id = $request->study_id;
        $records = Query::where('module_id','=',$study_id)->get();
        echo  view('queries::queries.queries_table_view',compact('records'));
    }
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

        $roles           = $request->post('assignedRoles');
        $users           = $request->post('assignedUsers');
        $remarks         = $request->post('assignedRemarks');
        $query_subject   = $request->post('query_subject');
        $module_id       = $request->post('module_id');
        $query_url       = $request->post('query_url');
        $queryAssignedTo = $request->post('queryAssignedTo');
        $id              = Str::uuid();
        $query           = Query::create([
            'id'=>$id,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> 0,
            'messages'=>$remarks,
            'module_id'=>$module_id,
            'query_status'=> 'open',
            'query_type' =>$queryAssignedTo,
            'query_url'=>$query_url,
            'query_subject'=>$query_subject
        ]);

        if ($queryAssignedTo == 'user')
        {
            foreach ($users as $user)
            {
                $roles = (array)null;
                QueryUser::create([
                    'id' => Str::uuid(),
                    'user_id' => $user,
                    'query_id' => $id
                ]);
            }
        }
        if ($queryAssignedTo == 'role')
        {
            foreach ($roles as $role)
            {
                $users = (array)null;
                RoleQuery::create([
                    'id' => Str::uuid(),
                    'roles_id' => $role,
                    'query_id' => $id
                ]);
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

        $users  =   User::where('id','!=',\auth()->user()->id)->get();
        $queries = Query::all();
        return view('queries::queries.chat',compact('users','queries'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
//        if ($request->ajax()) {
//            $records = Query::where('module_id','=',$id)->get();
//            $output = '';
//            foreach ($records as $record)
//            {
//                $output .= "<p>$record->messages</p>";
//            }
//            return Response($output);
//        }
        $queries = Query::where('module_id','=',$id)->get();
        return view('queries::queries.index',compact('queries'));
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

    public function queriesList()
    {
        return view('queries::queries.index');
    }
}
