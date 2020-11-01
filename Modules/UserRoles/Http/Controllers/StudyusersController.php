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


        if (hasPermission(auth()->user(),'studytools.index') && empty(session('current_study'))){
            $permissionsIdsArray = Permission::where(function($query){
                $query->where('permissions.name','!=','studytools.create')
                    ->orwhere('permissions.name','!=','studytools.store')
                    ->orWhere('permissions.name','!=','studytools.edit')
                    ->orwhere('permissions.name','!=','studytools.update');
            })->distinct('id')->pluck('id')->toArray();

            $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
            $userIdsArrayFromUserRole = UserRole::whereIn('role_id', $roleIdsArrayFromRolePermission)
                ->where('study_id',$currentStudy)
                ->distinct()->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIdsArrayFromUserRole)->distinct()->where('id','!=',\auth()->user()->id)->get();
            $studyusers = UserRole::select('users.*','user_roles.study_id','roles.role_type')
                ->join('users','users.id','=','user_roles.user_id')
                ->join('roles','roles.id','=','user_roles.role_id')
                ->where('roles.role_type','!=','system_role')
                ->where('user_roles.study_id','!=',session('current_study'))->distinct()
                ->get();
        }
        elseif (hasPermission(auth()->user(),'studytools.index') && !empty(session('current_study'))){
            $users =  UserRole::select('users.*','user_roles.study_id','roles.role_type', 'roles.name as role_name')
                ->join('users','users.id','=','user_roles.user_id')
                ->join('roles','roles.id','=','user_roles.role_id')
                ->where('roles.role_type','!=','system_role')
                ->where('user_roles.study_id','=',session('current_study'))
                ->get();
            $enrolledusers = UserRole::where('study_id','=',session('current_study'))->pluck('user_id')->toArray();
           $studyusers = UserRole::select('users.*','user_roles.study_id','roles.role_type')
                ->join('users','users.id','=','user_roles.user_id')
                ->join('roles','roles.id','=','user_roles.role_id')
                ->where('roles.role_type','!=','system_role')
                ->whereNotIn('user_roles.user_id',$enrolledusers)
                ->where('user_roles.study_id','!=',session('current_study'))->distinct()
                ->get();
        }
        $selectStudyUsers =  $selectStudyUsers = UserRole::select('users.*','user_roles.study_id')
            ->join('users','users.id','=','user_roles.user_id')
            ->where('user_roles.study_id','!=',session('current_study'))->distinct()
            ->get();


        return view('userroles::users.index',compact('users','roles','studyusers'));

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
        dd('study user');
        if ($request->ajax()) {
            $userID = $request->user_id;
            $id = Str::uuid();
            $user = User::updateOrCreate([
                'id' => $id],
                ['name' => $request->name,
                    'email' => $request->email,
                    'password' => encrypt($request->password),
                    'created_by'    => \auth()->user()->id
                ]);
            if ($request->roles)
            {
                foreach ($request->roles as $role){
                    UserRole::updateOrCreate([
                        'id'    => Str::uuid(),
                        'user_id'     => $user->id,
                        'role_id'   => $role
                    ]);

                    RoleStudyUser::updateOrCreate([
                        'id'    => Str::uuid(),
                        'user_id'     => $user->id,
                        'role_id'   => $role,
                        'study_id' => ''
                    ]);
                }
            }
        }
        return \response()->json($user);

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
