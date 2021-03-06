<?php

namespace Modules\BugReporting\Http\Controllers;

use App\Mail\BugMails;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Study;
use Modules\BugReporting\Entities\BugReport;
use App\Traits\UploadTrait;
use Modules\Queries\Entities\AppNotification;
use Modules\Queries\Entities\Query;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\UserRole;
use phpDocumentor\Reflection\Types\Null_;


class BugReportingController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
//        $role = Role::where('name','Developer')->first();
//
//        $userRoles = UserRole::where('role_id',$role->id)->pluck('user_id')->toArray();
//        dd($userRoles);
        $records = BugReport::where('parent_bug_id','like',0)->orderBy('created_at', 'DESC')->get();
        return view('bugreporting::pages.index',compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('bugreporting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $current_study = '';
        $current_study    = session('current_study');

        $findCurrentStudy = Study::where('id',$current_study)->first();

        if ($current_study == '')
        {
            $current_study = null;
        }
        else
        {
            $current_study =  $findCurrentStudy->study_short_name;
        }

        $shortTitle       = $request->post('shortTitle');
        $yourMessage      = $request->post('yourMessage');
        $query_url        = $request->post('query_url');
        $severity         = $request->post('severity');
        $filePath         = '';
        if ($request->has('attachFile'))
        {
            if (!empty($request->file('attachFile'))) {
                $image = $request->file('attachFile');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/bug_storage/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }

        $id              = Str::uuid();
        $query           = BugReport::create([
            'id'=>$id,
            'bug_reporter_by_id'=>\auth()->user()->id,
            'parent_bug_id'=> 0,
            'bug_message'=>$yourMessage,
            'status'=> 'open',
            'open_status'=>'Unconfirmed',
            'bug_url'=>$query_url,
            'bug_title'=>$shortTitle,
            'bug_attachments'=>$filePath,
            'bug_priority'=>$severity,
            'study_name'=>$current_study
        ]);
//        $role = Role::where('name','Developer')->first();
//        $userRole = UserRole::where('role_id',$role->id)->first();

        $role = Role::where('name','Developer')->first();

        $userRoles = UserRole::where('role_id',$role->id)->pluck('user_id')->toArray();

        $userEmails = User::where('id','=',$userRoles)->pluck('email')->toArray();

        foreach ($userRoles as $role)
        {
            AppNotification::create([
                'id' => Str::uuid(),
                'queryorbugid' => $id,
                'user_id'=>$role,
                'notifications_type'=>'bugReport',
                'is_read'=>'no',
                'notification_create_by_user_id'=>\auth()->user()->id
            ]);

            $checkNotificationType = User::where('id','like',\auth()->user()->id)->where('notification_type','=','email')
                ->where('bug_report','=',true)->first();


            if ($checkNotificationType !== null)
            {
                $data  = array(
                    'bug_title'=>$shortTitle,
                    'bug_message'=>$yourMessage,
                    'bug_attachments'=>$filePath,
                    'bug_priority'=>$severity,
                    'createdByName' =>\auth()->user()->name,
                );
                Mail::to($userEmails)->send(new BugMails($data));
            }
        }
        return response()->json([$query,'success'=>'Queries is generate successfully!!!!']);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('bugreporting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if ($id)
        {
            $record = BugReport::find($id);
            return response()->json([$record]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $checkIdIfExists = BugReport::where(array('id'=>$request->editBugId,'parent_bug_id'=>0))->first();
        if ($checkIdIfExists !== null)
        {
            $id = (string) Str::uuid();
            BugReport::create([
                'id' => $id,
                'bug_message' => $request->developerComment,
                'bug_title'=> $request->editBugTitle,
                'bug_url' => $request->editBugUrl,
                'parent_bug_id' => $checkIdIfExists['id'],
                'bug_reporter_by_id'=>\auth()->user()->id,
            ]);

            $bugStatusArray  = array
            (
                'bug_priority' => $request->editSeverity,
                'status' => $request->editStatus,
                'open_status'=>$request->openStatus,
                'closed_status'=>$request->closeStatus,
            );
            BugReport::where('id', $checkIdIfExists->id)->update($bugStatusArray);
            BugReport::where('parent_bug_id', $checkIdIfExists->id)->update($bugStatusArray);

            AppNotification::create([
                'id' => Str::uuid(),
                'queryorbugid' =>  $checkIdIfExists['id'],
                'user_id'=> $checkIdIfExists['bug_reporter_by_id'],
                'notifications_type'=>'bugReport',
                'is_read'=>'no',
                'notification_create_by_user_id'=>\auth()->user()->id
            ]);

            $checkNotificationType = User::where('id','=',$checkIdIfExists->bug_reporter_by_id)->where('notification_type','=','email')
                ->where('bug_report','=',true)->first();
            if ($checkNotificationType !== null)
            {
                $data  = array(
                    'bug_title'=>$request->editBugTitle,
                    'bug_message'=>$request->developerComment,
                    'bug_attachments'=>'',
                    'bug_priority'=>$request->editSeverity,
                    'createdByName' =>\auth()->user()->name,
                );

                Mail::to($checkNotificationType->email)->send(new BugMails($data));

            }
        }
        return response()->json(['success' => 'Bug Reporing  is generated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request,$id)
    {
        if ($request->ajax())
        {
            $delete = BugReport::find($id);
            $delete->delete();
            return response()->json(['success' => 'Bug  is deleted successfully.']);
        }
    }

    public function getCurrentRowData(Request $request)
    {
        $currentRow = $request->currentRow;
        $query      = BugReport::where('id',$currentRow)->orderBy('created_at','asc')->first();
        $answers    = BugReport::where('parent_bug_id',$currentRow)->orderBy('created_at','asc')->get();
        echo  view('bugreporting::pages.developer_reply_view',compact('answers','query'));

    }
}
