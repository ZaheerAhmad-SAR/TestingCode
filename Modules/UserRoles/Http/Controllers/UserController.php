<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\Admin\Entities\StudyUser;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Http\Requests\UserRequest;
use Illuminate\Support\Str;
use App\Traits\UploadTrait;




class UserController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (Auth::user()->can('users.create')) {
            $roles  =   Role::where('role_type','=','system_role')->get();
        }

        if (hasPermission(auth()->user(),'systemtools.index')){
            session(['current_study'=>'']);
            $users  =  User::orderBY('name','asc')->get();
            $studyusers = User::where('id','!=',\auth()->user()->id)->get();
        }
        else{
            session(['current_study'=>'']);
            $users = User::where('deleted_at','=',Null)
                ->where('user_type','=','study_user')
                ->get();


            $studyusers = UserRole::select('users.*','user_roles.study_id')
                ->join('users','users.id','=','user_roles.user_id')
                ->where('id','!=',\auth()->user()->id)
                ->where('user_roles.study_id','!=',session('current_study'))->distinct()
                ->get();
        }

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
       $validate = Validator::make($request->all(), [
            'name'      =>  'required',
            'email'      =>  'required|email|unique:users,email,'.$this->user->id,
            'password' => 'required|string|min:8|nullable|confirmed'
        ]);
       dd($validate);
        if(session('current_study')){
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
                        'study_id' => !empty(session('current_study'))?session('current_study'):''
                    ]);
                    if (session('current_study')){
                        $studyuser = StudyUser::create([
                            'id'    => Str::uuid(),
                            'user_id'     => $user->id,
                            'study_id' => !empty(session('current_study'))?session('current_study'):''
                        ]);
                    }
                }
            }
            $oldUser = [];
            // log event details
            $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser);
            return redirect()->route('studyusers.index');
        }
        else {
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
                        'study_id' => !empty(session('current_study'))?session('current_study'):''
                    ]);
                }
            }
            $oldUser = [];
            // log event details
            $logEventDetails = eventDetails($id, 'User', 'Add', $request->ip(), $oldUser);

            return redirect()->route('users.index');

        }
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

        return view('userroles::users.profile',compact('user'));
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
            'password' => 'required|string|min:8|nullable|confirmed'
        ]);
       // dd($validate);
        $user = User::where('id', $id)->first();
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
        return redirect()->route('users.index')->with('message', 'Record Updated Successfully!');
    }

    public function resetpassword(Request $request){
        dd('resetpassword');
    }
    public function update(UserRequest $request, $id)
    {
        $validate = $this->validate([
            'name'      =>  'required',
            'email'      =>  'required|email|unique:users,email,'.$this->user->id,
            'password' => 'required|string|min:8|nullable|confirmed'
        ]);
        dd($validate);
        // get old user data for trail log
        $oldUser = User::where('id', $id)->first();

        $user   =   User::find($id);
        $user->name  =  $request->name;
        $user->email =  $request->email;
        $user->role_id   =  !empty($request->roles) ? $request->roles[0] : 2;
        $user->password =   Hash::make($request->password);
        $user->save();
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
     // log event details
        $logEventDetails = eventDetails($user->id, 'User', 'Update', $request->ip(), $oldUser);

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
