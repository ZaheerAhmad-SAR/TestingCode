<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Http\Requests\UserRequest;

use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (Auth::user()->can('users.create')) {
            $roles  =   Role::where('role_type','=','system_role')->get();
        }

        if (hasPermission(auth()->user(),'studytools.index')){
            $users  =   User::all();
            $roles  =   Role::all();
        }
        else{
            $users = User::where('deleted_at','=',Null)
                ->where('user_type','=','study_user')
                ->get();
        }
        return view('userroles::users.index',compact('users','roles'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (hasPermission(auth()->user(),'studytools.index')){
            $roles  =   Role::all();
        }

            return view('userroles::users.create',compact('roles'));

        return redirect('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(UserRequest $request)
    {

        $id = Str::uuid();
        $user = User::create([
            'id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_by'    => \auth()->user()->id
            ]);
        if ($request->roles)
        {
            foreach ($request->roles as $role){
                $roles =UserRole::create([
                    'id'    => Str::uuid(),
                    'user_id'     => $user->id,
                    'role_id'   => $role
                ]);

                $userrole = RoleStudyUser::create([
                    'id'    => Str::uuid(),
                    'user_id'     => $user->id,
                    'role_id'   => $role,
                    'study_id' => ''
                ]);
            }
        }

        return redirect()->route('users.index');
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
        $user  = User::with('user_roles')
        ->find($id);
        $currentRoles = UserRole::select('user_roles.*','roles.*')
            ->join('roles','roles.id','user_roles.role_id')
            ->where('user_roles.user_id','=',$user->id)
            ->get();

        $unassignedRoles = Role::select('roles.*')
            ->join('user_roles','user_roles.role_id','roles.id')
            ->where('user_roles.user_id','=',$user->id)
            ->get();
        foreach ($currentRoles as $currentRole){
            $roleArray[] = $currentRole->role_id;
        }
        $unassignedRoles = Role::select('roles.*')
            ->whereNotIn('roles.id', $roleArray)->get();

        $roles = Role::all();

        return view('userroles::users.edit',compact('user','unassignedRoles','currentRoles'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
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
            $new = UserRole::create([
                'id'    => Str::uuid(),
                'user_id'    =>  $user->id,
                'role_id'    =>  $role,
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
        $user = User::find($id);
    }
}
