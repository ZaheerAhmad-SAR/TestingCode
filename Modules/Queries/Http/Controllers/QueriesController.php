<?php

namespace Modules\Queries\Http\Controllers;

use App\Events\NotifyUserAboutQuery;
use App\Events\UserShouldNotifyQuery;
use App\Mail\QueriesEmail;
use App\Mail\TransmissonQuery;
use App\User;
use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Queries\Entities\AppNotification;
use Modules\Queries\Entities\Query;
use Modules\Admin\Entities\Study;
use Modules\Queries\Entities\QueryUser;
use Modules\Queries\Entities\RoleQuery;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\StudyRoleUsers;
use Modules\UserRoles\Entities\UserRole;
use phpDocumentor\Reflection\Types\Null_;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Redirect;
use Session;


class QueriesController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $queries = Query::where('parent_query_id','like',0)->get();
        return view('queries::queries.index',compact('queries'));

    }
    public function loadHtml(Request $request)
    {
        $studyusers = UserRole::select('users.*','user_roles.study_id','roles.role_type', 'roles.name as role_name','study_role_users.study_id','study_role_users.user_id')
            ->join('users','users.id','=','user_roles.user_id')
            ->join('roles','roles.id','=','user_roles.role_id')
            ->join('study_role_users','study_role_users.user_id','=','user_roles.user_id')
            ->where('roles.role_type','!=','system_role')
            ->where('study_role_users.study_id','=',$request->study_id)
            ->get();
        echo  view('queries::queries.usersdropdown',compact('studyusers'));
    }
    /// User DropList for Form Query
    public function usersDropDownListForm(Request $request)
    {
        $role = Role::where('role_type','=','system_role')->where('name', 'Study Admin')->first();
        $studyRoleUsers = StudyRoleUsers::where('role_id',$role->id)
            ->where('study_id',$request->study_id)
            ->pluck('user_id')->toArray();
        $studyusers = User::whereIn('id',$studyRoleUsers)->get();

        echo  view('queries::queries.form.usersdropdownform',compact('studyusers'));
    }
    /// User DropList for Question Query
    public function usersDropDownListQuestion(Request $request)
    {

      $role = Role::where('role_type','=','system_role')->where('name', 'Study Admin')->first();
        $studyRoleUsers = StudyRoleUsers::where('role_id',$role->id)
            ->where('study_id',$request->study_id)
            ->pluck('user_id')->toArray();
        $studyusers = User::whereIn('id',$studyRoleUsers)->get();

        echo  view('queries::queries.question.usersdropdownquestions',compact('studyusers'));
    }


    public function getStudyDataByStudyId(Request $request)
    {
        $study_id = $request->study_id;
        $records = Study::where('id','like',$study_id)->first();
        echo  view('queries::queries.getstudydata',compact('records'));
    }

    public function loadAllQueriesByStudyId(Request $request)
    {
    if ($request->ajax())
    {
        $study_id = $request->study_id;
        $records = Query::where('query_status','!=','close')->where('module_id','like',$study_id)->where('parent_query_id','like',0)->get();
        echo  view('queries::queries.queries_table_view',compact('records'));
    }
    }
    public function loadAllQuestionById(Request $request)
    {
        $question_id = $request->question_id;
        $records = Query::where('query_status','!=','new')->where('question_id','like',$question_id)->where('parent_query_id','like',0)->get();
        echo  view('queries::queries.question.queries_questions_table_view',compact('records'));

    }

    public function loadAllCloseQuestionById(Request $request)
    {
        $question_id = $request->question_id;
        $records = Query::where('query_status','=','close')->where('question_id','like',$question_id)->where('parent_query_id','like',0)->get();
        echo  view('queries::queries.question.queries_close_questions_table_view',compact('records'));

    }

    public function loadAllCloseFormPhaseById(Request $request)
    {
        $phase_steps_id = $request->phase_steps_id;

        $records = Query::where('query_status','=','close')->where('phase_steps_id','like',$phase_steps_id)->where('parent_query_id','like',0)->get();
        echo  view('queries::queries.form.queries_close_form_table_view',compact('records'));

    }


    public function loadFormByPhaseId(Request $request)
    {
        $phase_steps_id = $request->phase_steps_id;
        $records = Query::where('query_status','!=','close')
            ->where('phase_steps_id','like',$phase_steps_id)
            ->whereNull('section_id')
            ->whereNull('question_id')
            ->where('parent_query_id','like',0)
            ->get();
        echo  view('queries::queries.form.queries_form_table_view',compact('records'));

    }

    public function queryReply(Request $request)
    {
        $parentQueryId    = $request->post('parent_query_id'); // return 0
        $query_status     = $request->post('query_status'); // return the status value
        $query_id         = $request->post('query_id');
        $find             = Query::find($query_id);
        $queryStatusArray = array('query_status'=>$query_status);
        Query::where('id',$find['id'])->update($queryStatusArray);
        $reply            = $request->post('reply');
        $query_subject    = $request->post('query_subject');
        $module_id        = $request->post('module_id');
        $query_url        = $request->post('query_url');
        $query_type       = $request->post('query_type');
        $id               = (string)Str::uuid();
        $filePath = '';
        if ($request->has('query_file'))
        {
            if (!empty($request->file('query_file'))) {
                $image = $request->file('query_file');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/query_attachments/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }
        $query            = Query::create([
            'id'=>$id,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> $query_id,
            'messages'=>$reply,
            'module_id'=>$module_id,
            'query_type' =>$query_type,
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'query_attachments'=>$filePath
        ]);
        return response()->json([$query,'success'=>'Queries response is successfully save!!!!','reply_id'=>$id]);

    }

    public function showCommentsById(Request $request)
    {
    $query_id = $request->query_id;
    $query    = Query::where('id',$query_id)->orderBy('created_at','asc')->first();
    $answers  = Query::where('parent_query_id',$query_id)->orderBy('created_at','asc')->get();
    echo  view('queries::queries.queries_reply_view',compact('answers','query'));
    }

    public function showQuestionsById(Request $request)
    {
    $query_id = $request->query_id;
    $query    = Query::where('id',$query_id)->orderBy('created_at','asc')->first();
    $answers  = Query::where('parent_query_id',$query_id)->orderBy('created_at','asc')->get();
    echo  view('queries::queries.question.question_reply_view',compact('answers','query'));
    }

    public function showFormByQueryId (Request $request)
    {
    $query_id = $request->query_id;
    $query    = Query::where('id',$query_id)->orderBy('created_at','asc')->first();
    $answers  = Query::where('parent_query_id',$query_id)->orderBy('created_at','asc')->get();
    echo  view('queries::queries.form.form_reply_view',compact('answers','query'));
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
        $roles            = $request->post('assignedRoles');
        $rolesArray       = explode(',',$roles);
        $users            = $request->post('assignedUsers');
        $usersArray       = explode(',',$users);
        $remarks          = $request->post('assignedRemarks');
        $querySectionData = $request->post('querySectionData');
        $query_subject    = $request->post('query_subject');
        $module_id        = $request->post('module_id');
        $query_url        = $request->post('query_url');
        $queryAssignedTo  = $request->post('queryAssignedTo');
        $filePath = '';
        if ($request->has('query_file'))
        {
            if (!empty($request->file('query_file'))) {
                $image = $request->file('query_file');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/query_attachments/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }

        $id              = (string)Str::uuid();
        $query           = Query::create([
            'id'=>$id,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> 0,
            'messages'=>$remarks,
            'module_name'=>$querySectionData,
            'module_id'=>$module_id,
            'query_status'=> 'open',
            'query_type' =>$queryAssignedTo,
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'query_attachments'=>$filePath
        ]);
        if ($queryAssignedTo == 'user')
        {
            foreach ($usersArray as $user)
            {
                $roles = (array)null;
                QueryUser::create([
                    'id' => (string)Str::uuid(),
                    'user_id' => $user,
                    'query_id' => $id
                ]);
            }
        }
        if ($queryAssignedTo == 'role')
        {
            foreach ($rolesArray as $role)
            {
                RoleQuery::create([
                    'id' => (string)Str::uuid(),
                    'roles_id' => $role,
                    'query_id' => $id
                ]);
            }
        }


        return response()->json([$query,'success'=>'Queries is generate successfully!!!!']);

    }

    public function storeQuestionQueries(Request $request)
    {
        //dd($request->all());
        $study_id            = $request->post('study_id');
        $question_id         = $request->post('question_id');
        $phase_steps_id      = $request->post('phase_steps_id');
        $section_id          = $request->post('section_id');
        $subject_id          = $request->post('subject_id');
        $study_structures_id = $request->post('study_structures_id');
        $field_id            = $request->post('field_id');
        $form_type_id        = $request->post('form_type_id');
        $module              = $request->post('module');
        $modility_id         = $request->post('modility_id');
        $roles               = $request->post('assignedRolesForm');
        $rolesArray          = explode(',',$roles);
        $users               = $request->post('assignedUsers');
        $usersArray          = explode(',',$users);
        $message             = $request->post('message');
        $query_subject       = $request->post('query_subject_form');
        $query_url           = $request->post('query_url');
        $queryAssignedTo     = $request->post('queryAssignedTo');
        $filePath            = '';

        if (!empty($request->file('queryFileForm'))) {
            $image = $request->file('queryFileForm');
            $name = Str::slug($request->input('name')).'_'.time();
            $folder = '/query_attachments/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
        }

        $queryid              = (string)Str::uuid();
        $query           = Query::create([
            'id'=>$queryid,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> 0,
            'messages'=>$message,
            'module_name'=>$module,
            'study_id'=>$study_id,
            'query_status'=> 'open',
            'query_type' =>$queryAssignedTo,
            'query_level'=> 'question',
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'question_id'=>$question_id,
            'subject_id'=>$subject_id,
            'study_structures_id'=>$study_structures_id,
            'phase_steps_id'=>$phase_steps_id,
            'section_id'=>$section_id,
            'field_id'=>$field_id,
            'form_type_id'=>$form_type_id,
            'modility_id'=>$modility_id,
            'query_attachments'=>$filePath,
            'query_condition'=>'new'
        ]);
        $current_study = '';
        $current_study    = session('current_study');
        $findCurrentStudy = Study::where('id',$current_study)->first();
        $current_study    = $findCurrentStudy->study_short_name;

        if ($queryAssignedTo == 'user')
        {
            foreach ($usersArray as $user)
            {

                $roles = (array)null;
                QueryUser::create([
                    'id' => (string)Str::uuid(),
                    'user_id' => $user,
                    'query_id' => $queryid
                ]);
                $checkNotificationType = User::where('id',$user)->where('notification_type','=','email')
                    ->where('is_subject','=',true)
                    ->pluck('id')->toArray();
                if (count($checkNotificationType) > 0)
                {
                    $yes       = is_array($checkNotificationType) ? $checkNotificationType : [$checkNotificationType];
                    $userEmail = User::whereIn('id',$yes)->pluck('email')->toArray();
                    $usersList = implode(',', $userEmail);

                    $data  = array(
                        'query_subject'=>$query_subject,
                        'messages'=>$message,
                        'attachment' => $filePath,
                        'studyShortName' => $current_study,
                        'study_code'=>$findCurrentStudy->study_code,
                        'study_id'=>$findCurrentStudy->id,
                        'createdByName' =>\auth()->user()->name,
                        'parent_query_id'=>0,
                    );

                    Mail::to($usersList)->send(new QueriesEmail($data));
                }
                AppNotification::create([
                    'id' => Str::uuid(),
                    'queryorbugid' => $queryid,
                    'user_id'=>$user,
                    'is_read'=> 'no',
                    'notifications_type'=>'query',
                    'question_id'=>$question_id,
                    'notification_create_by_user_id'=>\auth()->user()->id
                ]);
                //NotifyUserAboutQuery::dispatch(\auth()->user()->name);
                //broadcast(new UserShouldNotifyQuery());
                //event(new NotifyUserAboutQuery());
            }

        }
        if ($queryAssignedTo == 'role')
        {
            foreach ($rolesArray as $role)
            {
                RoleQuery::create([
                    'id' => (string)Str::uuid(),
                    'roles_id' => $role,
                    'query_id' => $queryid
                ]);
                AppNotification::create([
                    'id' => Str::uuid(),
                    'role_id'=>$role,
                    'queryorbugid' => $queryid,
                    'notifications_type'=>'query',
                    'question_id'=>$question_id,
                    'is_read'=> 'no',
                    'notification_create_by_user_id'=>\auth()->user()->id
                ]);
            }
        }

        return response()->json([$query,'success'=>'Queries is generate successfully!!!!']);

    }


    public function queryQuestionReply(Request $request)
    {
        $query_status     = $request->post('query_status'); // return the status value
        $query_id         = $request->post('query_id');
        $record           = AppNotification::where('queryorbugid',$query_id)->first();
        $find             = Query::find($query_id);
        $foundusers       = QueryUser::where('query_id','=',$find->id)->get();
        $queryCreatedBy   = Query::where('id',$find->id)->where('parent_query_id',0)->first();
        //dd($queryCreatedBy->queried_remarked_by_id);

        foreach ($foundusers as $founduser)
        {

            $arrayofIds = array(
                $queryCreatedBy->queried_remarked_by_id, $founduser['user_id']
            );
            if (($key = array_search(\auth()->user()->id, $arrayofIds )) !== false) {
                unset($arrayofIds[$key]);
            }
        }

        $message_reply    = $request->post('message_query_for_reply');
        $query_subject    = $request->post('subject_question');
        $query_level_q    = $request->post('query_level_question');
        $query_url        = $request->post('query_url');
        $query_type       = $request->post('query_type');
        $study_id         = $request->post('study_id');
        $subject_id       = $request->post('subject_id');
        $phase_steps_id   = $request->post('phase_steps_id');
        $section_id       = $request->post('section_id');
        $question_id      = $request->post('question_id');
        $field_id         = $request->post('field_id');
        $form_type_id     = $request->post('form_type_id');
        $modility_id      = $request->post('modility_id');
        $module_name      = $request->post('module_name');
        $study_structures = $request->post('study_structures_id');
        $queryId          = (string)Str::uuid();
        $filePath = '';
        if ($request->has('question_file'))
        {
            if (!empty($request->file('question_file'))) {
                $image = $request->file('question_file');
                $name = Str::slug($request->input('name')).'_'.time();
                $folder = '/query_attachments/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
            }
        }
        $query        = Query::create([
            'id'=>$queryId,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> $query_id,
            'messages'=>$message_reply,
            'query_type' =>$query_type,
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'query_attachments'=>$filePath,
            'study_id'=>$study_id,
            'subject_id'=>$subject_id,
            'study_structures_id'=>$study_structures,
            'phase_steps_id'=>$phase_steps_id,
            'section_id'=>$section_id,
            'question_id'=>$question_id,
            'field_id'=>$field_id,
            'form_type_id'=>$form_type_id,
            'modility_id'=>$modility_id,
            'module_name'=>$module_name,
            'query_level'=>$query_level_q,
            'query_condition'=>'reply'
        ]);
        //broadcast(new UserShouldNotifyQuery());
        foreach ($arrayofIds as $founduser)
        {

            AppNotification::create([
                'id' => Str::uuid(),
                'queryorbugid' => $queryId,
                'is_read'=> 'no',
                'notifications_type'=> 'query',
                'question_id'=>$question_id,
                'user_id'=>$founduser,
                'notification_create_by_user_id'=>\auth()->user()->id
            ]);
        }

        $current_study = '';
        $current_study    = session('current_study');
        $findCurrentStudy = Study::where('id',$current_study)->first();
        $current_study    = $findCurrentStudy->study_short_name;
        $assignedToUsers  = QueryUser::where('query_id',$find->id)->pluck('user_id')->toArray(); // Find the Id of Assigned User During query creating

        $checkNotificationType = User::whereIn('id',$assignedToUsers)->where('notification_type','=','email')
            ->where('is_subject','=',true)->get();

        if ($checkNotificationType!== null) {

            foreach ($checkNotificationType as $item)
            {
                $usersList = explode(" ", $item['email']);

                $data = array(
                    'query_subject' => $query_subject,
                    'messages' => $message_reply,
                    'attachment' => $filePath,
                    'studyShortName' => $current_study,
                    'study_code' => $findCurrentStudy->study_code,
                    'study_id' => $findCurrentStudy->id,
                    'createdByName' => \auth()->user()->name,
                    'parent_query_id'=>1,
                );
                Mail::to($usersList)->send(new QueriesEmail($data));
            }

            }
        $queryStatusArray = array('query_status'=>$query_status);
        $queryStatusArrayChild = array('query_status'=>$query_status);
        Query::where('id',$find['id'])->update($queryStatusArray);
        Query::where('parent_query_id',$find['id'])->update($queryStatusArrayChild);
        return response()->json([$query,'success'=>'Question response is successfully save!!!!','reply_id'=>$queryId]);

    }

    public function storeFormQueries(Request $request){
        //dd($request->all());
        $study_id            = $request->post('form_study_id');
        $question_id         = $request->post('form_question_id');
        $phase_steps_id      = $request->post('form_phase_steps_id');
        $section_id          = $request->post('form_section_id');
        $subject_id          = $request->post('form_subject_id');
        $study_structures_id = $request->post('form_study_structures_id');
        $field_id            = $request->post('form_field_id');
        $form_type_id        = $request->post('form_form_type_id');
        $module              = $request->post('form_module');
        $modility_id         = $request->post('form_modility_id');
        $roles               = $request->post('assignedRolesForm');
        $rolesArray          = explode(',',$roles);
        $users               = $request->post('assignedUsers');
        $usersArray          = explode(',',$users);
        $message             = $request->post('form_message');
        $query_subject       = $request->post('form_subject');
        $query_url           = $request->post('form_query_url');
        $queryAssignedTo     = $request->post('queryAssignedTo');
        $filePath            = '';

        if (!empty($request->file('inputFormFile'))) {
            $image = $request->file('inputFormFile');
            $name = Str::slug($request->input('name')).'_'.time();
            $folder = '/query_attachments/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
        }

        $id              = (string)Str::uuid();
        $query           = Query::create([
            'id'=>$id,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> 0,
            'messages'=>$message,
            'module_name'=>$module,
            'study_id'=>$study_id,
            'query_status'=> 'open',
            'query_type' =>$queryAssignedTo,
            'query_level'=> 'form',
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'question_id'=>$question_id,
            'subject_id'=>$subject_id,
            'study_structures_id'=>$study_structures_id,
            'phase_steps_id'=>$phase_steps_id,
            'section_id'=>$section_id,
            'field_id'=>$field_id,
            'form_type_id'=>$form_type_id,
            'modility_id'=>$modility_id,
            'query_attachments'=>$filePath,
            'query_condition'=>'new'
        ]);

        $current_study = '';
        $current_study    = session('current_study');
        $findCurrentStudy = Study::where('id',$current_study)->first();
        $current_study    = $findCurrentStudy->study_short_name;

        if ($queryAssignedTo == 'user')
        {
            foreach ($usersArray as $user)
            {
                $roles = (array)null;
                QueryUser::create([
                    'id' => (string)Str::uuid(),
                    'user_id' => $user,
                    'query_id' => $id
                ]);

                $checkNotificationType = User::where('id',$user)->where('notification_type','=','email')
                    ->where('is_subject','=',true)
                    ->pluck('id')->toArray();
                if (count($checkNotificationType) > 0)
                {
                    $yes       = is_array($checkNotificationType) ? $checkNotificationType : [$checkNotificationType];
                    $userEmail = User::whereIn('id',$yes)->pluck('email')->toArray();
                    $usersList = implode(',', $userEmail);

                    $data  = array(
                        'query_subject'=>$query_subject,
                        'messages'=>$message,
                        'attachment' => $filePath,
                        'studyShortName' => $current_study,
                        'study_code'=>$findCurrentStudy->study_code,
                        'study_id'=>$findCurrentStudy->id,
                        'createdByName' =>\auth()->user()->name,
                        'parent_query_id'=>0,
                    );

                    Mail::to($usersList)->send(new QueriesEmail($data));
                }


                AppNotification::create([
                    'id' => Str::uuid(),
                    'queryorbugid' => $id,
                    'user_id'=>$user,
                    'is_read'=> 'no',
                    'notifications_type'=>'query',
                    'question_id'=>$question_id,
                    'notification_create_by_user_id'=>\auth()->user()->id
                ]);
            }
        }
        if ($queryAssignedTo == 'role')
        {
            foreach ($rolesArray as $role)
            {
                RoleQuery::create([
                    'id' => (string)Str::uuid(),
                    'roles_id' => $role,
                    'query_id' => $id
                ]);

                AppNotification::create([
                    'id' => Str::uuid(),
                    'role_id'=>$role,
                    'queryorbugid' => $id,
                    'notifications_type'=>'query',
                    'question_id'=>$question_id,
                    'is_read'=> 'no',
                    'notification_create_by_user_id'=>\auth()->user()->id
                ]);
            }
        }
        return response()->json([$query,'success'=>'Queries is generate successfully!!!!']);

    }

    public function replyFormQueries(Request $request)
    {
        $query_status        = $request->post('formStatusInput'); // return the status value
        $query_id            = $request->post('queryIdInput');
        $record              = AppNotification::where('queryorbugid',$query_id)->first();
        $find                = Query::find($query_id);
        $foundusers          = QueryUser::where('query_id','=',$find->id)->get();
        $queryCreatedBy      = Query::where('id',$find->id)->where('parent_query_id',0)->first();

        foreach ($foundusers as $founduser)
        {
            $arrayofIds = array(
                $queryCreatedBy->queried_remarked_by_id, $founduser['user_id']
            );
            if (($key = array_search(\auth()->user()->id, $arrayofIds )) !== false) {
                unset($arrayofIds[$key]);
            }
        }

        $study_id            = $request->post('studyIdInput');
        $question_id         = $request->post('questionIdInput');
        $phase_steps_id      = $request->post('phaseStepsIdInput');
        $section_id          = $request->post('sectionIdInput');
        $subject_id          = $request->post('subjectIdInput');
        $study_structures_id = $request->post('studyStructuresIdInput');
        $field_id            = $request->post('fieldIdInput');
        $form_type_id        = $request->post('formTypeIdInput');
        $module              = $request->post('moduleNameInput');
        $modility_id         = $request->post('modilityIdInput');
        $message             = $request->post('formReply');
        $query_subject       = $request->post('subjectFormInput');
        $query_url           = $request->post('queryUrlInput');
        $queryAssignedTo     = $request->post('queryTypeInput');
        $queryLeveFormInput  = $request->post('queryLeveFormInput');
        $filePath            = '';

        if (!empty($request->file('formFileInput'))) {
            $image = $request->file('formFileInput');
            $name = Str::slug($request->input('name')).'_'.time();
            $folder = '/query_attachments/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
        }

        $id              = (string)Str::uuid();
        $query           = Query::create([
            'id'=>$id,
            'queried_remarked_by_id'=>\auth()->user()->id,
            'parent_query_id'=> $query_id,
            'messages'=>$message,
            'module_name'=>$module,
            'study_id'=>$study_id,
            'query_status'=> 'open',
            'query_type' =>$queryAssignedTo,
            'query_level'=>$queryLeveFormInput,
            'query_condition'=>'reply',
            'query_url'=>$query_url,
            'query_subject'=>$query_subject,
            'question_id'=>$question_id,
            'subject_id'=>$subject_id,
            'study_structures_id'=>$study_structures_id,
            'phase_steps_id'=>$phase_steps_id,
            'section_id'=>$section_id,
            'field_id'=>$field_id,
            'form_type_id'=>$form_type_id,
            'modility_id'=>$modility_id,
            'query_attachments'=>$filePath
        ]);

        foreach ($arrayofIds as $founduser)
        {

            AppNotification::create([
                'id' => Str::uuid(),
                'queryorbugid' => $id,
                'is_read'=> 'no',
                'notifications_type'=> 'query',
                'question_id'=>$question_id,
                'user_id'=>$founduser,
                'notification_create_by_user_id'=>\auth()->user()->id
            ]);
        }
        $current_study = '';
        $current_study    = session('current_study');
        $findCurrentStudy = Study::where('id',$current_study)->first();
        $current_study    = $findCurrentStudy->study_short_name;
        $assignedToUsers  = QueryUser::where('query_id',$find->id)->pluck('user_id')->toArray(); // Find the Id of Assigned User During query creating

        $checkNotificationType = User::whereIn('id',$assignedToUsers)->where('notification_type','=','email')
            ->where('is_subject','=',true)->get();

        if ($checkNotificationType!== null) {

            foreach ($checkNotificationType as $item)
            {
                $usersList = explode(" ", $item['email']);

                $data = array(
                    'query_subject' => $query_subject,
                    'messages' => $message,
                    'attachment' => $filePath,
                    'studyShortName' => $current_study,
                    'study_code' => $findCurrentStudy->study_code,
                    'study_id' => $findCurrentStudy->id,
                    'createdByName' => \auth()->user()->name,
                    'parent_query_id'=>1,
                );
                Mail::to($usersList)->send(new QueriesEmail($data));
            }
        }
        $queryStatusArray = array('query_status'=>$query_status);
        $queryStatusArrayChild = array('query_status'=>$query_status);
        Query::where('id',$find['id'])->update($queryStatusArray);
        Query::where('parent_query_id',$find['id'])->update($queryStatusArrayChild);
        return response()->json([$query,'success'=>'Query reply is generate successfully!!!!']);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
//        if ($request->ajax()) {
//            $records = Query::where('module_id','=',$id)->get();
//            $output = '';
//            foreach ($records as $record)
//            {
//                $output .= "<p>$record->messages</p>";
//            }
//            return Response($output);
//        }
        $queries = Query::where('module_id','=',$id)->get();
        return view('queries::queries.index',compact('queries'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        //
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


}
