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

        if (hasPermission(auth()->user(),'studytools.index')){
            $users  =   User::all();
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
        $where = array('id' => $id);
        $user  = User::with('user_roles')->where($where)->first();

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
