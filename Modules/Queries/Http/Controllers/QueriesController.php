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
use App\Traits\UploadTrait;

class QueriesController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $queries = Query::where('parent_query_id','like',0)->get();
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
        $records = Query::where('query_status','=','open')->where('module_id','like',$study_id)->where('parent_query_id','like',0)->get();
        echo  view('queries::queries.queries_table_view',compact('records'));
    }
    }

    public function queryReply(Request $request)
    {


        //dd($find->id,$query_status);
        $parentQueryId    = $request->post('parent_query_id'); // return 0
        $query_status     = $request->post('query_status'); // return the status value
        $query_id         = $request->post('query_id');
        $find             = Query::find($query_id);
        $queryStatusArray = array('query_status'=>$query_status);
        Query::where('id',$find['id'])->update($queryStatusArray);
        $reply            = $request->post('reply');
        $query_subject    = $request->post('query_subject');
        $module_id        = $request->post('module_id');
        $query_url        = $request->post('query_url');
        $query_type       = $request->post('query_type');
        $id               = Str::uuid();
        $filePath = '';
        if ($request->has('query_file'))
        {
            if (!empty($request->file('query_file'))) {
                $image = $request->file('query_file');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/query_attachments/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }
        $query            = Query::create([
            'id'=>$id,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> $query_id,
            'messages'=>$reply,
            'module_id'=>$module_id,
            'query_status'=> $query_status,
            'query_type' =>$query_type,
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'query_attachments'=>$filePath
        ]);
        return response()->json([$query,'success'=>'Queries response is successfully save!!!!','reply_id'=>$id]);

    }

    public function showCommentsById(Request $request)
    {
    $query_id = $request->query_id;
    $query    = Query::where('id',$query_id)->orderBy('created_at','asc')->first();
    $answers  = Query::where('parent_query_id',$query_id)->orderBy('created_at','asc')->get();
    echo  view('queries::queries.queries_reply_view',compact('answers','query'));
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
        $roles            = $request->post('assignedRoles');
        $rolesArray       = explode(',',$roles);
        $users            = $request->post('assignedUsers');
        $usersArray       = explode(',',$users);
        $remarks          = $request->post('assignedRemarks');
        $query_subject    = $request->post('query_subject');
        $module_id        = $request->post('module_id');
        $query_url        = $request->post('query_url');
        $queryAssignedTo  = $request->post('queryAssignedTo');
        $filePath = '';
        if ($request->has('query_file'))
        {
            if (!empty($request->file('query_file'))) {
                $image = $request->file('query_file');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/query_attachments/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }

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
            'query_subject'=>$query_subject,
            'query_attachments'=>$filePath
        ]);
        if ($queryAssignedTo == 'user')
        {
            foreach ($usersArray as $user)
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
            foreach ($rolesArray as $role)
            {
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

}
