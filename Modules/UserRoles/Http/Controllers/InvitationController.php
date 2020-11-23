<?php

namespace Modules\UserRoles\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\UserRoles\Emails\InvitationMail;
use Modules\UserRoles\Entities\Invitation;
use Psy\Util\Str;

class InvitationController extends Controller
{

    public function invite()
    {
        return view('userroles::invitation.index');
    }

    public function process(Request $request)
    {
        // validate the incoming request data

        do {
            //generate a random string using Laravel's str_random helper
            $token = rand(000000, 999999);
        } //check if the token already exists and if it does, try again
        while (Invitation::where('token', $token)->first());

        //create a new invite record
        $invite = Invitation::create([
            'id'    => \Illuminate\Support\Str::uuid(),
            'email' => $request->get('email'),
            'token' => $token,
            'role_id'   => $request->roles
        ]);
        $user = User::where('email','=',$invite->email)->first();
        if(empty($user)) {
            // send the email
            Mail::to($request->get('email'))->send(new InvitationMail($invite));
            return redirect()
                ->back();
        }
        // redirect back where we came from
        return redirect()->route('users.index')->with('message','Invitation already sent to the given email');

    }

    public function accept($token)
    {
        dd('accept');
        if (!$invite = Invitation::where('token', $token)->first()) {
            //if the invite doesn't exist do something more graceful than this
            abort(404);
        }

        // create the user with the details from the invite
        User::create(['email' => $invite->email]);

        // delete the invite so it can't be used again
        $invite->delete();

        // here you would probably log the user in and show them the dashboard, but we'll just prove it worked

        return 'Good job! Invite accepted!';

    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('userroles::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('userroles::create');
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
        return view('userroles::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('userroles::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
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
