<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\RolePermission;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Http\Requests\UserRequest;

use Illuminate\Support\Str;



class StudyusersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

            $roles  =   Role::where('role_type','=','study_role')->get();
            $currentStudy = session('current_study');

        $enrolledusers = UserRole::where('study_id','=',session('current_study'))->pluck('user_id')->toArray();
        $studyusers = UserRole::select('users.*','user_roles.study_id','roles.role_type')
            ->join('users','users.id','=','user_roles.user_id')
            ->join('roles','roles.id','=','user_roles.role_id')
            ->where('roles.role_type','!=','system_role')
            ->whereNotIn('user_roles.user_id',$enrolledusers)
            ->where('user_roles.study_id','!=',session('current_study'))->distinct()
            ->get();
        $users =  UserRole::select('users.*','user_roles.study_id','roles.role_type', 'roles.name as role_name')
            ->join('users','users.id','=','user_roles.user_id')
            ->join('roles','roles.id','=','user_roles.role_id')
            ->where('roles.role_type','!=','system_role')
            ->where('user_roles.study_id','=',session('current_study'))
            ->get();

        return view('userroles::users.studyUsers',compact('users','roles','studyusers','enrolledusers'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (Auth::user()->can('users.create')) {
            $roles  =   Role::where('created_by','=',\auth()->user()->id)->get();

            return view('userroles::users.create',compact('roles'));
        }

        return redirect('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
       //dd(session('current_study'));
        $id = Str::uuid();
        $user = User::create([
            'id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_by'    => \auth()->user()->id,
            'role_id'   =>  !empty($request->roles)?$request->roles[0]:2
        ]);
        if (!empty($request->roles))
        {
            foreach ($request->roles as $role){
                $roles =UserRole::create([
                    'id'    => Str::uuid(),
                    'user_id'     => $user->id,
                    'role_id'   => $role,
                    'study_id'  => session('current_study')
                ]);

            }
        }
        $oldUser = [];
        // log event details
        $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser);
        return redirect()->route('studyusers.index')->with('message','StudyUser added');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.index')->with('success','User deleted');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        dd('study');
        $where = array('id' => $id);
        $user  = User::with('user_roles')->where($where)->first();
        dd($user);

        return \response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UserRequest $request, $id)
    {


        $user   =   User::find($id);
        $user->update([
            'name'  =>  $request->name,
            'email' =>  $request->email,
            'password'  =>  Hash::make($request->password),
            'role_id'   =>  !empty($request->roles)?$request->roles[0]:2
        ]);
        $userroles  = UserRole::where('user_id',$user->id)->get();
        foreach ($userroles as $role_id){
            $role_id->delete();
        }
        foreach ($request->roles as $role){
            UserRole::create([
                'user_id'    =>  $user->id,
                'role_id'    =>  $role
            ]);
        }

        return redirect()->route('users.index');

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        dd('delete');
        $user = User::find($id);
    }
}
