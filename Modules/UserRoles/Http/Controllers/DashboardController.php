<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\AssignWork;
use Modules\FormSubmission\Entities\FormStatus;
use Illuminate\Support\Carbon;
class DashboardController extends Controller
{
    public function index ()
    {
        $modalities = Modility::all();
        $studies = Study::all();
        $records = User::where('working_status','online')->get()->groupBy(function($date) {
            return Carbon::parse($date->online_at)->format('H');
        });
        return view('userroles::dashboard',compact('modalities','records','studies'));
    }

    public function switch_role($role_id)
    {
        if ($role_id){
            $user   =   User::find(auth()->user()->id);
            $user->role_id  =   $role_id;
            $user->save();
        }

        return redirect()->back();

    }
}
