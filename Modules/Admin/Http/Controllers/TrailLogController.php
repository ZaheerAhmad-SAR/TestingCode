<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\TrailLog;
use Session;
use Auth;
use Carbon\Carbon;

class TrailLogController extends Controller
{
    //
    public function index(Request $request) {
   
    	// check for system user and common users
    	if(Auth::user()->role->role_type == 'system_role') {
    		// get logs
    		// $getLogs = TrailLog::with('users')->orderBy('id', 'desc')->paginate(15);
            $getLogs = TrailLog::query();
            $getLogs = $getLogs->select('trail_logs.*', 'users.name')
                               ->leftjoin('users', 'users.id', '=', 'trail_logs.user_id');

            if($request->user_name != '') {
                $getLogs =  $getLogs->where('trail_logs.user_id', $request->user_name);
            }

            if ($request->event_section != '') {
                $getLogs =  $getLogs->where('trail_logs.event_section', $request->event_section);
            }

            if ($request->event_type != '') {
                $getLogs =  $getLogs->where('trail_logs.event_type', $request->event_type);
            }

            if ($request->event_date != '') {
                $eventDate = explode('-', $request->event_date);
                    $from   = Carbon::parse($eventDate[0])
                                        ->startOfDay()        // 2018-09-29 00:00:00.000000
                                        ->toDateTimeString(); // 2018-09-29 00:00:00

                    $to     = Carbon::parse($eventDate[1])
                                        ->endOfDay()          // 2018-09-29 23:59:59.000000
                                        ->toDateTimeString(); // 2018-09-29 23:59:59

                $getLogs =  $getLogs->whereBetween('trail_logs.created_at', [$from, $to]);
            }

            $getLogs = $getLogs->orderBy('id', 'desc')->paginate(15);

            // event section filter array
            $eventSection = array(
                "Option Group" => "Option Group",
                "Site" => "Site",
                "PI" => "Primary Investigator",
                "Coordinator" => "Coordinator",
                "Photographer" => "Photographer",
                "Others" => "Others",
                "Annotation" => "Annotation"

            );

            // get user for search filter
            $getUsers = TrailLog::select('trail_logs.user_id', 'users.id', 'users.name')
                                ->leftjoin('users', 'users.id', '=', 'trail_logs.user_id')
                                ->GroupBy('trail_logs.user_id')
                                ->orderBy('users.name', 'asc')
                                ->get();

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
      	
    	return view('admin::trail_log', compact('getLogs', 'eventSection', 'getUsers'));
    }
}
