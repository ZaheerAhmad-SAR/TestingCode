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
    public function index(Request $request)
    {
        if(isset($request->sort_by_field_name) && $request->sort_by_field_name !=''){
            $field_name = $request->sort_by_field_name;
        }else{
            $field_name = 'name';
        }
        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $asc_or_decs = $request->sort_by_field;
        }else{
            $asc_or_decs = 'ASC';
        }
        $currentStudy = session('current_study');

        // $studyRoleIds = Role::where('role_type', '=', 'study_role')->pluck('id')->toArray();
        $studyRoleIds = Role::pluck('id')->toArray();
        $idsOfUsersWithStudyRole = UserRole::whereIn('role_id', $studyRoleIds)->pluck('user_id')->toArray();

        $roles  =   Role::where('role_type', '=', 'study_role')->get();
        $enrolledUserIds = RoleStudyUser::where('study_id', '=', session('current_study'))->pluck('user_id')->toArray();

        $studyusers = User::whereIn('id', $enrolledUserIds);
        if(isset($request->name) && $request->name !=''){
            $studyusers = $studyusers->where('users.name','like', '%'.$request->name.'%');
        }
        if(isset($request->email) && $request->email !=''){
            $studyusers = $studyusers->where('users.email','like', '%'.$request->email.'%');
        }
        if(isset($request->role_id) && $request->role_id !=''){
            $studyusers = $studyusers->where('users.role_id','like', '%'. $request->role_id.'%');
        }
        if(isset($request->sort_by_field) && $request->sort_by_field !=''){
            $studyusers = $studyusers->orderBy('users.'.$field_name , $request->sort_by_field);
        }
        $studyusers = $studyusers->paginate(10)->withPath('?sort_by_field_name='.$field_name.'&sort_by_field='.$asc_or_decs);
        $old_values = $request->input();

        $remaining_users = User::whereIn('id', $idsOfUsersWithStudyRole)
                                ->whereNotIn('id', $enrolledUserIds)
                                ->get();
        $allroles = Role::all();
        return view('userroles::users.studyUsers', compact('roles', 'studyusers', 'remaining_users','allroles','old_values'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = new User();

        $unassigned_roles  =   Role::where('role_type', '=', 'study_role')->get();

        $assigned_roles = [];
        $add_or_edit = 'Add';
        $readOnly = '';
        $route = route('studyusers.store');
        $method = 'POST';
        $submitFunction = 'submitAddUserForm();';

        return view('userroles::users.popups.userform', compact('user', 'unassigned_roles', 'assigned_roles', 'add_or_edit', 'readOnly', 'route', 'method', 'submitFunction'));
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
                    $id = (string)Str::uuid();

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
                                'id' => (string)Str::uuid(),
                                'user_id' => $user->id,
                                'role_id' => $role,
                                'study_id' => session('current_study'),
                            ]);
                            UserRole::createUserRole($user->id, $role);
                        }
                    } // roles

                    $oldUser = [];
                    // log event details
                    $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser, false);

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
        $user = User::find($id);

        $assigned_roles = [];
        $unassigned_roles = [];

        $currentRoleIds = RoleStudyUser::where('user_id', 'like', $id)->where('study_id', 'like', session('current_study'))->pluck('role_id')->toArray();
        $assigned_roles = Role::whereIn('id', $currentRoleIds)->get();

        if (!empty($currentRoleIds)) {
            $unassigned_roles = Role::where('role_type', '=', 'study_role')->whereNotIn('id', $currentRoleIds)->get();
        } else {
            $unassigned_roles = Role::where('role_type', '=', 'study_role')->get();
        }

        $add_or_edit = 'Edit';
        $readOnly = '';
        if (!hasPermission(\auth()->user(), 'systemtools.index')) {
            $readOnly = 'readonly';
        }

        $route = route('studyusers.update', $id);
        $method = 'PUT';
        $submitFunction = 'submitEditUserForm();';

        return view('userroles::users.popups.userform', compact('user', 'unassigned_roles', 'assigned_roles', 'add_or_edit', 'readOnly', 'route', 'method', 'submitFunction'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        // get old user data for trail log
        $oldUser = User::where('id', $id)->first();

        //get old Roles
        $getUserOldRoles = Role::leftjoin('study_role_users', 'study_role_users.role_id', '=', 'roles.id')
            ->where('study_role_users.study_id', 'like', session('current_study'))
            ->where('study_role_users.user_id', 'like',  $id)
            ->pluck('roles.name')
            ->toArray();

        $oldUser->role = $getUserOldRoles != null ? implode(',', $getUserOldRoles) : '';

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
                'nullable',
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
            $user   =  User::find($id);
            if (hasPermission(\auth()->user(), 'systemtools.index')) {
                $user->name = $request->name;
                $user->email = $request->email;
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                }
                $user->role_id = !empty($request->roles) ? $request->roles[0] : 2;
                $user->update();
            }
            $this->updateRoles($request, $user);
            // log event details
            $logEventDetails = eventDetails($user->id, 'User', 'Update', $request->ip(), $oldUser, false);

            return response()->json(['success' => 'User Updated successfully.']);
        }
    }

    private function updateRoles($request, $user)
    {
        if ($request->roles) {

            $currentRoleIds = RoleStudyUser::where('study_id', 'like', session('current_study'))
                ->where('user_id', 'like', $user->id)
                ->pluck('role_id')
                ->toArray();

            $newRoleIds = $request->roles;

            foreach ($currentRoleIds as $roleId) {
                if (!in_array($roleId, $newRoleIds)) {
                    RoleStudyUser::where('user_id', $user->id)
                        ->where('role_id', $roleId)
                        ->where('study_id', 'like', session('current_study'))
                        ->delete();
                }
            }

            foreach ($request->roles as $role) {
                if (!in_array($role, $currentRoleIds)) {
                    RoleStudyUser::create([
                        'id'         => (string)Str::uuid(),
                        'user_id'    =>  $user->id,
                        'role_id'    =>  $role,
                        'study_id'   => session('current_study'),
                    ]);
                    UserRole::createUserRole($user->id, $role);
                }
            }
        }
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
