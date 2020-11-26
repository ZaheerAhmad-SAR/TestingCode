<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Validator;
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
        $currentStudy = session('current_study');
        $studyRoleIds = Role::where('role_type', '=', 'study_role')->pluck('id')->toArray();
        $idsOfUsersWithStudyRole = UserRole::whereIn('role_id', $studyRoleIds)->pluck('user_id')->toArray();
        $roles  =   Role::where('role_type', '=', 'study_role')->get();

        $enrolledUserIds = RoleStudyUser::where('study_id', '=', session('current_study'))->pluck('user_id')->toArray();
        $studyusers = $enrolledusers = User::whereIn('id', $enrolledUserIds)->get();

        $users = User::whereIn('id', $idsOfUsersWithStudyRole)->whereNotIn('id', $enrolledUserIds)->get();

        return view('userroles::users.studyUsers', compact('roles', 'enrolledusers', 'studyusers', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (Auth::user()->can('users.create')) {
            $roles  =   Role::where('created_by', '=', \auth()->user()->id)->get();

            return view('userroles::users.create', compact('roles'));
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
        if ($request->ajax()) {
            // make validator
            $messages = [
                'name.required' => 'Please provide name!',
                'email.required' => 'Please provide e-mail address!',
                'email.email' => 'Please provide valid e-mail address!',
                'password.required' => 'Please provide password!',
                'password.confirmed' => 'Passwords must match...',
                'password.regex' => 'Password must be 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character',
                'roles.required' => 'Please select role!',
            ];
            $rules = [
                'name'      => 'required',
                'email'     => 'required|email',
                'password' => [
                    'required',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                    'confirmed',
                ],
                'roles'    => 'required|array|min:1',
                'roles.*'  => 'required|min:1',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {

                return response()->json(['errors' => $validator->errors()->first()]);
            } else {

                //CHECK FOR DUPLICATE EMAIL
                $checkEmail = User::where('email', $request->email)
                    ->where('deleted_at', NULL)
                    ->first();

                if ($checkEmail != null) {

                    return response()->json(['errors' => 'Email already exists.']);
                } else {

                    // unique ID
                    $id = Str::uuid();

                    $user = User::create([
                        'id' => $id,
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'created_by'    => \auth()->user()->id,
                        'role_id'   =>  !empty($request->roles) ? $request->roles[0] : 2
                    ]);

                    if (!empty($request->roles)) {
                        foreach ($request->roles as $role) {
                            $roles = RoleStudyUser::create([
                                'id' => Str::uuid(),
                                'user_id' => $user->id,
                                'role_id' => $role,
                                'study_id' => session('current_study'),
                            ]);
                            $checkUserRole = UserRole::where('role_id', $role)->where('user_id', $user->id)->first();
                            if (null === $checkUserRole) {
                                UserRole::create([
                                    'id' => Str::uuid(),
                                    'user_id' => $user->id,
                                    'role_id' => $role
                                ]);
                            }
                        }
                    } // roles

                    $oldUser = [];
                    // log event details
                    $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser);

                    return response()->json(['success' => 'User created successfully.']);
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
        if (!empty(session('current_study'))) {
            RoleStudyUser::where('user_id', 'like', $id)->where('study_id', 'like', session('current_study'))->delete();
        }
        return redirect()->route('studyusers.index')->with('success', 'User romoved from study');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */

    public function edit($id)
    {
        $user  = User::with('user_roles')->find($id);

        $currentRoles = RoleStudyUser::select('study_role_users.*', 'roles.*')
            ->join('roles', 'roles.id', 'study_role_users.role_id')
            ->where('study_role_users.user_id', '=', $user->id)
            ->where('study_role_users.study_id', '=', session('current_study'))
            ->get();
        // dd($currentRoles);

        $unassignedRoles = Role::select('roles.*')
            ->join('study_role_users', 'study_role_users.role_id', 'roles.id')
            ->where('study_role_users.user_id', '=', $user->id)
            ->where('roles.role_type', '!=', 'system_role')
            ->get();
        // dd($unassignedRoles);

        foreach ($currentRoles as $currentRole) {
            $roleArray[] = $currentRole->role_id;
        }

        if (!empty($roleArray)) {
            $unassignedRoles = Role::select('roles.*')
                ->whereNotIn('roles.id', $roleArray)
                ->where('roles.role_type', 'study_role')
                ->get();
        } else {
            //  $unassignedRoles = Role::where('role_type','!=','system_role' )->get();
        }


        return view('userroles::users.edit-study-user', compact('user', 'unassignedRoles', 'currentRoles'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user   =  User::find($id);
        $user->update([
            'name'  =>  $request->name,
            'email' =>  $request->email,
            'password'  =>  Hash::make($request->password),
            'role_id'   =>  !empty($request->roles) ? $request->roles[0] : 2
        ]);

        if ($request->roles != null) {
            RoleStudyUser::where('study_id', 'like', session('current_study'))
                ->where('user_id', $user->id)
                ->delete();
            foreach ($request->roles as $role) {
                RoleStudyUser::create([
                    'id'         => Str::uuid(),
                    'user_id'    =>  $user->id,
                    'role_id'    =>  $role,
                    'study_id'   => session('current_study'),
                ]);
                $checkUserRole = UserRole::where('role_id', $role)->where('user_id', $user->id)->first();
                if (null === $checkUserRole) {
                    UserRole::create([
                        'id'    => Str::uuid(),
                        'user_id'   => $user->id,
                        'role_id'   => $role
                    ]);
                }
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
        $user = User::find($id);
        dd($user);
    }
}
