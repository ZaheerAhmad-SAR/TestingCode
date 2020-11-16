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
use Session;
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
        /*$studyusers = UserRole::select('user_roles.user_id','user_roles.study_id','users.*','roles.role_type')
            ->join('users','users.id','=','user_roles.user_id')
            ->join('roles','roles.id','=','user_roles.role_id')
            ->where('user_roles.user_id','!=',$enrolledusers)
            ->where('study_id','!=',session('current_study'))->get();
        dd($studyusers, session('current_study'));*/

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
    public function store(Request $request)
    {
        dd($request->all());
        if($request->ajax()) {
            // make validator
            $validator = \Validator::make($request->all(), [
                'name'      => 'required',
                'email'     => 'required|email',
                'password'  => 'required|string|min:8|nullable|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'roles'    => "required|array|min:1",
                'roles.*'  => "required|min:1",
            ]);

            if ($validator->fails()) {

                return response()->json(['errors'=> $validator->errors()->first()]);

            } else {

                //CHECK FOR DUPLICATE EMAIL
                $checkEmail = User::where('email', $request->email)
                                    ->where('deleted_at', NULL)
                                    ->first();

                if ($checkEmail != null) {

                    return response()->json(['errors'=> 'Email already exists.']);

                } else {

                    // unique ID
                    $id = Str::uuid();

                    $user = User::create([
                        'id' => $id,
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'created_by'    => \auth()->user()->id,
                        'role_id'   =>  !empty($request->roles)?$request->roles[0]:2
                    ]);

                        if (!empty($request->roles)) {
                            foreach ($request->roles as $role){
                                $roles =UserRole::create([
                                    'id'        => Str::uuid(),
                                    'user_id'   => $user->id,
                                    'role_id'   => $role,
                                    'study_id'  => session('current_study'),
                                    'user_type' => '0'
                                ]);

                            }
                        } // roles

                    $oldUser = [];
                    // log event details
                    $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser);

                    return response()->json(['success'=> 'User created successfully.']);

                } // check email ends

            } // validator check edns

        } // ajax ends

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

    public function edit($id) {
        $user  = User::with('user_roles')->find($id);

        $currentRoles = UserRole::select('user_roles.*','roles.*')
            ->join('roles','roles.id','user_roles.role_id')
            ->where('user_roles.user_id','=', $user->id)
            ->where('user_roles.study_id','=', session('current_study'))
            ->get();

        $unassignedRoles = Role::select('roles.*')
            ->join('user_roles','user_roles.role_id','roles.id')
            ->where('user_roles.user_id','=',$user->id)
            ->get();

        foreach ($currentRoles as $currentRole){
            $roleArray[] = $currentRole->role_id;
        }

        if (!empty($roleArray)) {
            $unassignedRoles = Role::select('roles.*')
            ->whereNotIn('roles.id', $roleArray)->get();
        }
        else {
            $unassignedRoles = Role::where('role_type','=','system_role' )->get();
        }


        return view('userroles::users.edit-study-user',compact('user','unassignedRoles','currentRoles'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        dd($request->all());
        $user   =  User::find($id);
        $user->update([
            'name'  =>  $request->name,
            'email' =>  $request->email,
            'password'  =>  Hash::make($request->password),
            'role_id'   =>  !empty($request->roles) ? $request->roles[0]: 2
        ]);

        if($request->roles != null) {

            $userroles  = UserRole::where('study_id', session('current_study'))
                                    ->where('user_id', $user->id)
                                    ->where('user_type', 0)
                                    ->delete();

            foreach ($request->roles as $role) {
                UserRole::create([
                    'id'         => Str::uuid(),
                    'user_id'    =>  $user->id,
                    'role_id'    =>  $role,
                    'study_id'   => session('current_study'),
                    'user_type'  => '0'
                ]);
            }
        }

        return redirect(route('studyusers.index'));

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
