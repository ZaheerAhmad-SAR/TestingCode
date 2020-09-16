<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\UserRoles\Entities\RolePermission;

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
        session(['current_study'=>'','study_short_name'=> '']);

        return view('userroles::dashboard');
    }
}
