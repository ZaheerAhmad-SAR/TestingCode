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
            $roles  =   Role::where('created_by','=',\auth()->user()->id)->get();
        }

        if (Auth::user()->role->name == 'admin'){
            $users = User::all();

            /*$users  =   User::select('users.*','roles.name as role_name','roles.role_type')->with('role')
                ->join('user_roles','user_roles.user_id','=','users.id')
                ->join('roles','roles.id','=','user_roles.role_id')
                ->where('roles.role_type','!=','study_role')
                ->get();*/
        }
        else{
            dd('not here');
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
        if (Auth::user()->can('users.create')) {
            $roles  =   Role::where('created_by','=',\auth()->user()->id)->get();
            dd($roles);

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
        $user_role = Auth::user()->role->name;
        $user_id = Auth::user()->id;
        if ($user_role == 'admin'){
            $user = User::create([
                'id'    => Str::uuid(),
                'name'  =>  $request->name,
                'email' =>  $request->email,
                'password'  =>  Hash::make($request->password),
                'user_type' => 'System User',
                'role_id'   => !empty($request->roles)?$request->roles[0]:2,
                'created_by'   => $user_id
            ]);

            if(!empty($request->roles)){
                foreach ($request->roles as $role){
                    UserRole::create([
                        'id'    => Str::uuid(),
                        'user_id'    =>  $user->id,
                        'role_id'    =>  $role
                    ]);
                    RoleStudyUser::create([
                        'id'    => Str::uuid(),
                        'role_id' =>$role,
                        'user_id' => $user->id,
                        'study_id' => ''
                    ]);
                }
            }
        }
        else{
            $user = User::create([
                'id'    => Str::uuid(),
                'name'  =>  $request->name,
                'email' =>  $request->email,
                'password'  =>  Hash::make($request->password),
                'user_type' => 'Study User',
                'role_id'   => !empty($request->roles)?$request->roles[0]:2,
                'created_by'   => $user_id
            ]);

            if(!empty($request->roles)){
                foreach ($request->roles as $role){
                    UserRole::create([
                        'id'    => Str::uuid(),
                        'user_id'    =>  $user->id,
                        'role_id'    =>  $role
                    ]);
                    RoleStudyUser::create([
                        'id'    => Str::uuid(),
                        'role_id' =>$role,
                        'user_id' => $user->id,
                        'study_id' => ''
                    ]);

                }
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
        if (Auth::user()->can('users.update')) {
            $user = User::find(decrypt($id));
            $roles = Role::get();
            return view('userroles::users.edit', compact('user', 'roles'));
        }
            return  redirect('dashboard');
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
