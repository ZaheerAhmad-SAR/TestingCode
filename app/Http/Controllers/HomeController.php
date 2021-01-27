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
        $records = User::get()->groupBy(function($date) {
            return Carbon::parse($date->online_at)->format('H');
        });
        return view('userroles::dashboard',compact('modalities','records'));
    }
    
}
