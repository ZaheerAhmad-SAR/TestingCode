<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\TrailLog;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\StudyUser;
use Session;
use Auth;
use Carbon\Carbon;

class TrailLogController extends Controller
{
    //
    public function index(Request $request) {
        // initialize arrays
        $getLogs = [];
        $eventSection = [];
        $getUsers = [];
        $getStudies = [];

    	// check for system user Admin
    	if(hasPermission(auth()->user(),'systemtools.index') && hasPermission(auth()->user(),'trail_logs.list')) {
    		// get logs
            $getLogs = TrailLog::query();
            $getLogs = $getLogs->select('trail_logs.*', 'users.name')
                               ->leftjoin('users', 'users.id', '=', 'trail_logs.user_id');

            if($request->user_name != '') {
                $getLogs =  $getLogs->where('trail_logs.user_id', $request->user_name);
            }

            if ($request->event_study != '') {
                $getLogs =  $getLogs->where('trail_logs.study_id', $request->event_study);
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
                "Primary Investigator" => "Primary Investigator",
                "Coordinator" => "Coordinator",
                "Photographer" => "Photographer",
                "Others" => "Others",
                "Annotation" => "Annotation",
                "Role" => "Role",
                "User" => "User",
                "Modality" => "Modality",
                "Child Modality" => "Child Modality",
                "Device" => "Device",
                "Phase" => "Phase",
                "Step" => "Step",
                "Section" => "Section",
                "Study Site" => "Study Site",
                "Study" => "Study",
                "Study Status" => "Study Status",
                "Subject" => "Subject",
                "Transmission Data" => "Transmission Data",
                "QC Form" => "QC Form",
                "Grading Form" => "Grading Form",
                "Adjudication Form" => "Adjudication Form",
                "System Adjudication Form" => "System Adjudication Form",
                "Assign Work" => "Assign Work"

            );

            // get user for search filter
            $getUsers = TrailLog::select('trail_logs.user_id', 'users.id', 'users.name')
                                ->leftjoin('users', 'users.id', '=', 'trail_logs.user_id')
                                ->GroupBy('trail_logs.user_id')
                                ->orderBy('users.name', 'asc')
                                ->get();

            // get all studies
            $getStudies =  Study::where('id','!=', Null)->orderBy('study_short_name')->get();


    	} // check if session for study is set

        else if (hasPermission(auth()->user(),'studytools.index') && hasPermission(auth()->user(),'trail_logs.list')) {

                //get logs for current study and user
                $getLogs = TrailLog::query();
                $getLogs = $getLogs->select('trail_logs.*', 'users.name')
                                    ->leftjoin('users', 'users.id', '=', 'trail_logs.user_id')
                                    ->where('trail_logs.study_id', Session::get('current_study'));
                                    //->where('trail_logs.user_id', Auth::user()->id);

                if ($request->event_section != '') {
                    $getLogs =  $getLogs->where('trail_logs.event_section', $request->event_section);
                }

                if ($request->event_study != '') {
                    $getLogs =  $getLogs->where('trail_logs.study_id', $request->event_study);
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

                //get event sections
                $eventSection = TrailLog::GroupBy('event_section')
                                ->where('study_id', Session::get('current_study'))
                                //->where('user_id', Auth::user()->id)
                                ->pluck('event_section')
                                ->toArray();

                // get studies of this user
                // $getStudies = StudyUser::select('study_user.*', 'users.*', 'studies.*')
                //     ->join('users', 'users.id', '=', 'study_user.user_id')
                //     ->join('studies', 'studies.id', '=', 'study_user.study_id')
                //     ->where('users.id', '=', \auth()->user()->id)
                //     ->orderBy('study_short_name')->get();
                $getStudies = [];

            }  else if (hasPermission(auth()->user(),'trail_logs.list')) {

                //get logs for current study and user
                $getLogs = TrailLog::query();
                $getLogs = $getLogs->select('trail_logs.*', 'users.name')
                                    ->leftjoin('users', 'users.id', '=', 'trail_logs.user_id')
                                    ->where('trail_logs.study_id', Session::get('current_study'))
                                    ->where('trail_logs.user_id', Auth::user()->id);

                if ($request->event_section != '') {
                    $getLogs =  $getLogs->where('trail_logs.event_section', $request->event_section);
                }

                if ($request->event_study != '') {
                    $getLogs =  $getLogs->where('trail_logs.study_id', $request->event_study);
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

                //get event sections
                $eventSection = TrailLog::GroupBy('event_section')
                                ->where('study_id', Session::get('current_study'))
                                ->where('user_id', Auth::user()->id)
                                ->pluck('event_section')
                                ->toArray();

                // get studies of this user
                // $getStudies = StudyUser::select('study_user.*', 'users.*', 'studies.*')
                //     ->join('users', 'users.id', '=', 'study_user.user_id')
                //     ->join('studies', 'studies.id', '=', 'study_user.study_id')
                //     ->where('users.id', '=', \auth()->user()->id)
                //     ->orderBy('study_short_name')->get();
                $getStudies = [];

            } // study session ends
        // user role echeck ends

    	return view('admin::trail_log', compact('getLogs', 'eventSection','getUsers', 'getStudies'));
    }
}
