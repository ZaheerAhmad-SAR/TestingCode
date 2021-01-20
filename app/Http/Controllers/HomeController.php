<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\Modility;
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
        return view('userroles::dashboard',compact('modalities'));
    }
    
}
