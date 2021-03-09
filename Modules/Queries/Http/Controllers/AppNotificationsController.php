<?php

namespace Modules\Queries\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Admin\Entities\Study;
use Modules\Queries\Entities\AppNotification;
use Modules\Queries\Entities\Query;

class AppNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        $records = AppNotification::query();

        if ($request->is_read != '') {

            $records = $records->where('is_read','like', '%'.$request->is_read.'%');
        }
        $records = $records->where('user_id','=', auth()->user()->id);
        $records = $records->orderBy('created_at','DESC');
        //$records = $records->where('is_read', '=','no');
        $records = $records->get();

        return view('queries::notifications.index',compact('records'));
    }
    /// Show the count of each user notification on the bell icon along with lists, normally call this function after 10 second
    public  function countUserNotification()
    {
        $records = AppNotification::where('user_id','=', auth()->user()->id)
            ->where('is_read','no')
            //->distinct('question_id')
            //->groupBy('question_id')
            ->get();
            $count  = count($records);
            return view('queries::notifications.icon',compact('records','count'));

    }

//    public  function countUserNotification()
//    {
//
//        $count = AppNotification::where('user_id','=', auth()->user()->id)
//            ->where('is_read','no')
//            //->distinct('question_id')
//            //->groupBy('question_id')
//            ->count();
//        if ($count > 0 )
//        {
//            return response()->json(['counter'=>$count]);
//        }
//
//    }


    public function notificationList(Request $request)
    {
        $records = AppNotification::where('user_id','=', $request->id)->where('is_read','no')->get();
        return view('queries::notifications.icon',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('queries::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        //return view('queries::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('queries::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        //dd(session()->has('current_study'));
        //dd(session()->has('current_study'),$request->all());
        if ($request->ajax())
        {
            $query_url    = $request->post('query_url');
            $studyId      = $request->post('study_id');
            $study_code   = $request->post('study_code');
            $queryorbugid = $request->post('currentNotificationId');
            $study_short_name = $request->post('study_short_name');

            if ($request->session()->has('current_study'))
            {
                $request->session()->forget('current_study');

            }

            session([
                'current_study' => $studyId,
                'study_short_name' =>$study_short_name,
                'study_code' => $study_code
            ]);
            $isRead    = array('is_read'=>'yes');
            AppNotification::where('queryorbugid',$queryorbugid)->update($isRead);
            return response()->json(['success'=>$query_url]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /// function to mark all the unread notification to read
    public function markAllNotificationToRead()
    {
        $records = AppNotification::where('user_id','=', auth()->user()->id)->where('is_read','no')->get();
        foreach ($records as $record)
        {
            //dd($record['user_id']);
            $isRead    = array('is_read'=>'yes');
            AppNotification::where('user_id',$record['user_id'])->update($isRead);
            return response()->json(['success'=>'All Notification is mark to Read successfully!!!!']);
        }
    }


    public function markAsUnRead(Request $request)
    {
        $id    = $request->post('id');
        $check = AppNotification::find($id);
        if ($check!== '')
        {
            $isRead    = array('is_read'=>'no');
            AppNotification::where('id',$check['id'])->update($isRead);
            return response()->json(['success'=>' Notification is mark to un-Read successfully!!!!']);
        }
    }

    public function markAsRead(Request $request)
    {
        $id    = $request->post('id');
        $check = AppNotification::find($id);
        if ($check!== '')
        {
            $isRead    = array('is_read'=>'yes');
            AppNotification::where('id',$check['id'])->update($isRead);
            return response()->json(['success'=>' Notification is mark to Read successfully!!!!']);
        }
    }

    public function removeNotification(Request $request)
    {
        $id    = $request->post('id');
        $check = AppNotification::find($id);
        if ($check!== '')
        {
            $check->delete();
            return response()->json(['success' => 'Notification is deleted successfully.']);
        }
    }
}
