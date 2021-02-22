<?php

namespace App\Http\Controllers;
use App\User;
use App\UserPrefrences;
use Illuminate\Http\Request;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\Modility;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
class HomeController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //session(['current_study'=>'','study_short_name'=> '']);
        $modalities = Modility::all();
        $records = User::where('working_status','online')->get()->groupBy(function($date) {
            return Carbon::parse($date->online_at)->format('H');
        });
        return view('userroles::dashboard',compact('modalities','records'));
    }
    public function user_preferences()
    {
        $user_preferences = UserPrefrences::where('user_id',\Auth()->user()->id)->first();
        return view('prefrences.user_prefrences',compact('user_preferences'));
    }
    // Update User Prefrences
    public function update_user_prefrences(Request $request){
        $user_id = \Auth()->user()->id;
        $res=UserPrefrences::where('user_id',$user_id)->delete();
        $id    = (string)Str::uuid();
        UserPrefrences::create([
            'id' => $id,
            'user_id'    => $user_id,
            'default_theme' => $request->default_theme,
            'default_pagination' => $request->default_pagination,
        ]);
        return redirect()->route('home.user-preferences')->with('message', 'Record Update Successfully!');
    }


    public function update_online_at_time(){
        $user = User::find(\Auth()->user()->id);
        $user->online_at  =  now();
        $user->save();
    }
    public function working_status(){
        $id = \Auth()->user()->id;
        $date = now();
        $user = User::where('id',$id)->first();
        dd((new Carbon($date))->diff(new Carbon($user->online_at))->format('%h:%I'));
    }
    
}
