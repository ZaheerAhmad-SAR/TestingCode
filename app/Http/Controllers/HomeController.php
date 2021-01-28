<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\Modility;
use Illuminate\Support\Carbon;
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
