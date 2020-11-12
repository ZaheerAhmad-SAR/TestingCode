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
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Entities\UserSystemInfo;
use Modules\UserRoles\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use ParagonIE\ConstantTime\Base32;
use PragmaRX\Google2FAQRCode\Google2FA;
use App\Traits\UploadTrait;




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
    public function index()
    {
        if (Auth::user()->can('users.create')) {
            $roles  =   Role::where('role_type','=','system_role')->get();
        }
        $currentStudy = session('current_study');

            $users  =  User::orderBY('name','asc')->get();
            $studyusers = User::where('id','!=',\auth()->user()->id)->get();

        return view('userroles::users.index',compact('users','roles','studyusers'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
            $roles  =   Role::where('role_type','=','system_role')->get();
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
//        $validator = Validate::;
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
                    ]);

                }
            }
            $oldUser = [];
            // log event details
            $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser);
            return redirect()->route('users.index')->with('message','User added');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function assign_users(Request $request){
        $study_user = UserRole::create([
            'id'    => Str::uuid(),
            'user_id' => $request->study_user,
            'role_id'   => $request->user_role,
            'study_id'  => session('current_study')
        ]);

        return redirect()->route('studyusers.index');
    }

    public function update_profile()
    {
        $user = auth()->user();
        $codes = backupCode::where('user_id','=',\auth()->user()->id)->get();

        return view('userroles::users.profile',compact('user','codes'));
    }
    public function show($id)
    {
        if (!empty(session('current_study'))){
           $user = UserRole::where('user_id','=',$id)->where('study_id','=',session('current_study'))->get();
           foreach ($user as $studyuser){
              // dd('study admin has got permission',$studyuser);
              $studyuser->delete();
           }
        }
        else
        {
            $user = User::find($id);
            $user->delete();

        }
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
        if (!empty($roleArray)){
            $unassignedRoles = Role::select('roles.*')
            ->whereNotIn('roles.id', $roleArray)->get();
        }
        else{
            $unassignedRoles = Role::where('role_type','=','system_role' )->get();
        }


        return view('userroles::users.edit',compact('user','unassignedRoles','currentRoles'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update_user(Request $request, $id){
        $validate = Validator::make($request->all(), [
            'name'      =>  'required',
            'email'      =>  'required|email|unique:users,email,',
        ]);
        $user = User::where('id', $id)->first();
        if (!empty($request->password)){
            $user->title  =  $request->title;
            $user->name  =  $request->name;
            $user->phone =  $request->phone;
            $user->password =   Hash::make($request->password);
            if ($request->has('profile_image')) {
                $image = $request->file('profile_image');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/images/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
                $user->profile_image = $filePath;
            }
            //dd($user);
            $user->save();
        }
        else{
            $user->title  =  $request->title;
            $user->name  =  $request->name;
            $user->phone =  $request->phone;
            if ($request->has('profile_image')) {
                $image = $request->file('profile_image');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/images/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
                $user->profile_image = $filePath;
            }
            //dd($user);
            $user->save();

        }

        return redirect()->route('dashboard.index')->with('message', 'Record Updated Successfully!');
    }

    public function getcodes(){
        $codes = backupCode::where('user_id','=',\auth()->user()->id)->get();

    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        // get old user data for trail log
        $oldUser = User::where('id', $id)->first();
        $data = array('name'=>$oldUser->name);
        $user   =   User::find($id);
        if ($request->fa == 'enabled' && !empty($request->password)){
            //dd('fa enabled and not empty password');
        $user->name  =  $request->name;
        $user->email =  $request->email;
        $user->role_id   =  !empty($request->roles) ? $request->roles[0] : 2;
        $user->password =   Hash::make($request->password);
        $user->qr_flag = '0';
        $user->save();
        if ($request->roles){
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
        }
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
        }
        elseif($request->fa == 'enabled' && empty($request->password))
        {
          //  dd('fa enabled and empty password');
            $user->name  =  $request->name;
            $user->email =  $request->email;
            $user->role_id   =  !empty($request->roles) ? $request->roles[0] : 2;
            $user->qr_flag = '0';
            $user->save();
            if ($request->roles){
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
            }
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
        }
        elseif($request->fa == 'disabled' && !empty($request->password)){
            //dd('fa disabled and not empty password');
            $user->name  =  $request->name;
            $user->email =  $request->email;
            $user->role_id   =  !empty($request->roles) ? $request->roles[0] : 2;
            $user->password =   Hash::make($request->password);
            $user->qr_flag = '0';
            $user->google2fa_secret = NULL;
            $user->google_auth = NULL;

            $user->save();
            if ($request->roles){
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
            }
            $system_infos = UserSystemInfo::where('user_id','=',$oldUser->id)->get();
            foreach ($system_infos as $system_info){
                $system_info->delete();
            }
        }
        elseif ($request->fa == 'disabled' && empty($request->password)){
            //dd('fa disabled and empty password');
            $user->name  =  $request->name;
            $user->email =  $request->email;
            $user->role_id   =  !empty($request->roles) ? $request->roles[0] : 2;
            $user->qr_flag = '0';
            $user->google2fa_secret = NULL;
            $user->google_auth = NULL;
            $user->save();
            if ($request->roles){
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
            }
            $system_infos = UserSystemInfo::where('user_id','=',$oldUser->id)->get();
            foreach ($system_infos as $system_info){
                $system_info->delete();
            }

        }


     // log event details
        $logEventDetails = eventDetails($user->id, 'User', 'Update', $request->ip(), $oldUser);

        return redirect()->back()->with('message','user updated');

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'roles' => 'required'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (Invitation::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'There exists an invite with this email!');
                $validator->errors()->add('roles', 'Please select a role to send invite!');
            }
        });
        if ($validator->fails()) {
            return redirect(route('users.index'))
                ->withErrors($validator)
                ->withInput();
        }
        do {
            $token = Str::random(15);
        } while (Invitation::where('token', $token)->first());
        Invitation::create([
            'id'    => Str::uuid(),
            'token' => $token,
            'role_id'   => $request->roles,
            'email' => $request->input('email')
        ]);
        $url = URL::temporarySignedRoute(

            'registration', now()->addMinutes(300), ['token' => $token]
        );

        Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));

        return redirect('/users')->with('message', 'The Invite has been sent successfully');
    }

    public function registration_view($token)
    {
        $invite = Invitation::where('token', $token)->first();
        return view('auth.register',['invite' => $invite]);
    }
}
