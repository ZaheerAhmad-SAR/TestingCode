<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\TrailLog;
use Session;
use Auth;

class TrailLogController extends Controller
{
    //
    public function index() {
    	// check for system user and common users
    	if(Auth::user()->role->role_type == 'system_role') {
    		// get logs
    		$getLogs = TrailLog::with('users')->orderBy('id', 'desc')->paginate(15);

    	} else {
    		// check if session for subject is set
    		if (Session::has('current_study')) {
    			//get logs for current study and user
    			$getLogs = TrailLog::where('study_id', Session::get('current_study'))
					    			->where('user_id', Auth::user()->id)
					    			->with('users')
					    			->orderBy('id', 'desc')
					    			->paginate(15);
    		}
    	} // user role echeck ends
      	
    	return view('admin::trail_log', compact('getLogs'));
    }
}
