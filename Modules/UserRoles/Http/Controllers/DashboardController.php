<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\UserRoles\Entities\RolePermission;
use Modules\Admin\Entities\Modility;
use Modules\Admin\Entities\AssignWork;
use Modules\FormSubmission\Entities\FormStatus;
use Illuminate\Support\Carbon;
class DashboardController extends Controller
{
    /*public function __construct() {
        $this->middleware('can:users.dashboard');
    }*/
    /**
    whereDate('online_at', Carbon::today())
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $modalities = Modility::all();
        $records = User::get()->groupBy(function($date) {
            return Carbon::parse($date->online_at)->format('H');
        });
        $assign_work_cfp = AssignWork::where('form_type_id',2)->with(['get_form_status' => function ($query) { $query->where('form_status', '=', 'complete'); }])->get();
        return view('userroles::dashboard',compact('modalities','records'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('userroles::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('userroles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('userroles::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
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
