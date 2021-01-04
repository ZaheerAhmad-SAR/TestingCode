<?php

namespace Modules\UserRoles\Http\Controllers;

use App\backupCode;
use App\Notifications\InviteNotification;
use App\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\StudyUser;
use Modules\UserRoles\Entities\Invitation;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Entities\UserSystemInfo;
use Modules\UserRoles\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use ParagonIE\ConstantTime\Base32;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Session\Session;
use Modules\UserRoles\Entities\StudyRoleUsers;

class UserController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function __construct()
    {
        $this->middleware('auth')->except('registration_view');
    }
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes);
    }
    public function index(Request $request)
    {

        if (isThisUserSuperAdmin(\auth()->user())) {
            $roles  =   Role::where('role_type', '!=', 'study_role')->get();
            $systemRoleIds = Role::where('role_type', '!=', 'study_role')->pluck('id')->toArray();
        } else {
            $roles  =   Role::where('role_type', '=', 'system_role')->get();
            $systemRoleIds = Role::where('role_type', '=', 'system_role')->pluck('id')->toArray();
        }

        $currentStudyId = session('current_study');

        $userIdsOfSystemRoles = UserRole::whereIn('role_id', $systemRoleIds)->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIdsOfSystemRoles);
        if(isset($request->name) && $request->name !=''){
            $users = $users->where('users.name','like', '%'.$request->name.'%');
        }
        if(isset($request->email) && $request->email !=''){
            $users = $users->where('users.email','like', '%'.$request->email.'%');
        }
        if(isset($request->role_id) && $request->role_id !=''){
            $users = $users->where('users.role_id', 'like', '%'.$request->role_id.'%');
        }
        $users->orderBy('name', 'asc')->get();
        $users = $users->orderBy('name', 'asc')->get();

        $studyRoleIds = Role::where('role_type', '=', 'study_role')->pluck('id')->toArray();
        $userIdsOfStudyRoles = UserRole::whereIn('role_id', $studyRoleIds)->pluck('user_id')->toArray();

        if ($currentStudyId != '') {
            $studyUserIds = RoleStudyUser::where('study_id', 'like', $currentStudyId)->pluck('user_id')->toArray();
            $studyusers = User::whereIn('id', $userIdsOfStudyRoles)
                ->whereIn('id', $studyUserIds)
                ->where('id', '!=', \auth()->user()->id)->get();
        } else {
            $studyusers = User::whereIn('id', $userIdsOfStudyRoles)->where('id', '!=', \auth()->user()->id)->get();
        }
        $allroles = Role::all();
        return view('userroles::users.index', compact('users', 'roles', 'studyusers','allroles'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = new User();

        if (isThisUserSuperAdmin(\auth()->user())) {
            $unassigned_roles  =   Role::where('role_type', '!=', 'study_role')->get();
        } else {
            $unassigned_roles  =   Role::where('role_type', '=', 'system_role')->get();
        }
        $assigned_roles = [];
        $add_or_edit = 'Add';
        $readOnly = '';
        $route = route('users.store');
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
                        'role_id'   =>  !empty($request->roles) ? $request->roles[0] : 1
                    ]);

                    if (!empty($request->roles)) {
                        foreach ($request->roles as $role) {
                            UserRole::createUserRole($user->id, $role);
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

    public function assign_users(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_user' => 'required',
            'user_role' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()]);
        } else {

            $study_user = RoleStudyUser::create([
                'id'    => (string)Str::uuid(),
                'user_id' => $request->study_user,
                'role_id'   => $request->user_role,
                'study_id'  => session('current_study')
            ]);
            UserRole::createUserRole($request->study_user, $request->user_role);
            return response()->json(['success' => 'User assigned successfully.']);
        }
    }

    public function update_profile()
    {
        $user = auth()->user();
        $codes = backupCode::where('user_id', '=', \auth()->user()->id)->get();

        return view('userroles::users.profile', compact('user', 'codes'));
    }
    public function show($id)
    {
        RoleStudyUser::where('user_id', 'like', $id)->delete();
        UserRole::where('user_id', 'like', $id)->delete();
        User::where('id', 'like', $id)->delete();
        return redirect()->route('users.index')->with('success', 'User deleted');
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
        $allRoleIds = [];

        if (isThisUserHasSystemRole($user)) {
            $currentRoleIds = UserRole::where('user_id', 'like', $id)->pluck('role_id')->toArray();
            $assigned_roles = Role::whereIn('id', $currentRoleIds)->get();

            if (isThisUserSuperAdmin(\auth()->user())) {
                $allRoleIds = Role::where('role_type', '!=', 'study_role')->pluck('id')->toArray();
            } else {
                $allRoleIds = Role::where('role_type', '=', 'system_role')->pluck('id')->toArray();
            }
            $unassignedRoleIds = array_diff($allRoleIds, $currentRoleIds);
            $unassigned_roles = Role::whereIn('id', $unassignedRoleIds)->get();
        } else {
            $currentRoleIds = RoleStudyUser::where('user_id', 'like', $id)->pluck('role_id')->toArray();
            $assigned_roles = Role::whereIn('id', $currentRoleIds)->get();
            if (!empty($currentRoleIds)) {
                $unassigned_roles = Role::where('role_type', '=', 'study_role')->whereNotIn('id', $currentRoleIds)->get();
            } else {
                $unassigned_roles = Role::where('role_type', '=', 'study_role')->get();
            }
        }

        $add_or_edit = 'Edit';
        $readOnly = '';
        if (!hasPermission(\auth()->user(), 'systemtools.index')) {
            $readOnly = 'readonly';
        }
        $route = route('users.update', $id);
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
    public function update_user(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name'      =>  'required',
            'email'      =>  'required|email|unique:users,email,',
        ]);
        $user = User::where('id', $id)->first();
        if (!empty($request->password)) {
            $user->title  =  $request->title;
            $user->name  =  $request->name;
            $user->phone =  $request->phone;
            $user->password =   Hash::make($request->password);
            if ($request->has('profile_image')) {
                $image = $request->file('profile_image');
                $name = Str::slug($request->input('name')) . '_' . time();
                $folder = '/images/';
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
                $user->profile_image = $filePath;
            }

            // look for user signature
            if ($request->has('user_signature')) {

                @unlink(storage_path('/user_signature/'.$user->user_signature));

                $user->user_signature = $user->id.''.$request->file("user_signature")->getClientOriginalName();
                $request->user_signature->move(storage_path('/user_signature/'), $user->user_signature);
            }
            //dd($user);
            $user->save();
        } else {
            $user->title  =  $request->title;
            $user->name  =  $request->name;
            $user->phone =  $request->phone;
            if ($request->has('profile_image')) {
                $image = $request->file('profile_image');
                $name = Str::slug($request->input('name')) . '_' . time();
                $folder = '/images/';
                $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
                $user->profile_image = $filePath;
            }

             // look for user signature
            if ($request->has('user_signature')) {

                @unlink(storage_path('/user_signature/'.$user->user_signature));

                $user->user_signature = $user->id.''.$request->file("user_signature")->getClientOriginalName();
                $request->user_signature->move(storage_path('/user_signature/'), $user->user_signature);
            }

            //dd($user);
            $user->save();
        }

        return redirect()->back();
    }

    public function getcodes()
    {
        $codes = backupCode::where('user_id', '=', \auth()->user()->id)->get();
    }

    public function update(Request $request, $id)
    {
        // get old user data for trail log
        $oldUser = User::where('id', $id)->first();

        //get old Roles
        $getUserOldRoles = Role::leftjoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('user_roles.user_id', $id)
            ->pluck('roles.name')
            ->toArray();

        $oldUser->role = $getUserOldRoles != null ? implode(',', $getUserOldRoles) : '';

        $data = array('name' => $oldUser->name);
        $user   =   User::find($id);
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
            if ($request->fa == 'enabled' && !empty($request->password)) {
                //dd('fa enabled and not empty password');
                $user = $this->updateUser($request, $user);
                $this->updateRoles($request, $user);

                $secret = $this->generateSecret();
                $user->google2fa_secret = Crypt::encrypt($secret);
                $google2fa = new Google2FA();
                $inlineUrl = $google2fa->getQRCodeInline(
                    'OIRRC',
                    'info@oirrc.net',
                    $secret
                );
                $user->google_auth = $inlineUrl;
                $user->save();
            } elseif ($request->fa == 'enabled' && empty($request->password)) {
                //  dd('fa enabled and empty password');
                $user = $this->updateUser($request, $user);
                $this->updateRoles($request, $user);
                $secret = $this->generateSecret();
                $user->google2fa_secret = Crypt::encrypt($secret);
                $google2fa = new Google2FA();
                $inlineUrl = $google2fa->getQRCodeInline(
                    'OIRRC',
                    'info@oirrc.net',
                    $secret
                );
                $user->google_auth = $inlineUrl;
                $user->save();
            } elseif ($request->fa == 'disabled' && !empty($request->password)) {
                //dd('fa disabled and not empty password');
                $user = $this->updateUser($request, $user);
                $this->updateRoles($request, $user);
                $this->updateSystemInfo($oldUser);
            } elseif ($request->fa == 'disabled' && empty($request->password)) {
                //dd('fa disabled and empty password');
                $user = $this->updateUser($request, $user);
                $this->updateRoles($request, $user);
                $this->updateSystemInfo($oldUser);
            } else {
                $user = $this->updateUser($request, $user);
                $this->updateRoles($request, $user);
                $this->updateSystemInfo($oldUser);
            }
            // log event details
            $logEventDetails = eventDetails($user->id, 'User', 'Update', $request->ip(), $oldUser);
            return response()->json(['success' => 'User Updated successfully.']);
        }
    }


    private function updateSystemInfo($oldUser)
    {
        $system_infos = UserSystemInfo::where('user_id', '=', $oldUser->id)->get();
        foreach ($system_infos as $system_info) {
            $system_info->delete();
        }
    }
    private function updateUser($request, $user)
    {
        $user->name  =  $request->name;
        $user->email =  $request->email;
        $user->role_id   =  !empty($request->roles) ? $request->roles[0] : 2;
        if (!empty($request->password)) {
            $user->password =   Hash::make($request->password);
        }
        $user->qr_flag = '0';
        $user->google2fa_secret = NULL;
        $user->google_auth = NULL;
        $user->save();
        return $user;
    }
    private function updateRoles($request, $user)
    {
        if ($request->roles) {

            $currentRoleIds = UserRole::where('user_id', 'like', $user->id)->pluck('role_id')->toArray();
            $newRoleIds = $request->roles;

            foreach ($currentRoleIds as $roleId) {
                if (!in_array($roleId, $newRoleIds)) {
                    RoleStudyUser::where('user_id', $user->id)->where('role_id', $roleId)->delete();
                    UserRole::where('user_id', $user->id)->where('role_id', $roleId)->delete();
                }
            }

            foreach ($request->roles as $role) {
                if (!in_array($role, $currentRoleIds)) {
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
    }

    public function invite_view()
    {
        return view('userroles::users.invite');
    }

    public function process_invites(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users,email',
                'roles' => 'required'
            ],
            [
                'required' => 'Please provide email address!',
                'email' => 'Please provide a valid email address!',
                'unique' => 'A user with this email is already part of OCAP!',
                'roles' => 'Please select a role!',
            ]
        );
        /*$validator->after(function ($validator) use ($request) {
            if (Invitation::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'There exists an invite with this email!');
                $validator->errors()->add('roles', 'Please select a role to send invite!');
            }
        });*/
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()]);
        } else {
            do {
                $token = Str::random(15);
            } while (Invitation::where('token', $token)->first());

            Invitation::where('email', 'like', $request->input('email'))->delete();
            Invitation::create([
                'id'    => (string)Str::uuid(),
                'token' => $token,
                'role_id'   => $request->roles,
                'email' => $request->input('email')
            ]);
            $url = URL::temporarySignedRoute(

                'registration',
                now()->addMinutes(300),
                ['token' => $token]
            );

            Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));
            session()->put('message', 'The Invite has been sent successfully!');
            return response()->json(['success' => 'The Invite has been sent successfully.']);
        }
    }

    public function registration_view($token)
    {
        $invite = Invitation::where('token', $token)->first();
        $user = User::where('email', 'like', $invite->email)->first();
        if (null !== $invite) {
            if (null === $user) {
                return view('auth.register', ['invite' => $invite]);
            } else {
                UserRole::createUserRole($user->id, $invite->role_id);
                return redirect()->route('dashboard.index')->with('message', 'OCAP user role accepted successfully!');
            }
        } else {
            return redirect()->route('dashboard.index')->with('message', 'Invitition expired!');
        }
    }
}
