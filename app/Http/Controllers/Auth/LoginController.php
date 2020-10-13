<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/studies';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated() {
            $roleId =  \auth()->user()->role_id;

            return redirect()->route('studies.index');
    }
   /* public function authenticated()
    {
        $user = Auth::user();
        $user->token_2fa_expiry = \Carbon\Carbon::now();
        $user->save();
        return redirect('/admin');
    }*/

    /*protected function authenticated(Request $request, $user)
    {
            $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());


        if ($user->role_id ==1 || $user->role_id==2){
            return redirect()->intended(route('admin.index'));
        }elseif ($user->role_id==4){
            return redirect()->intended(route('user.index'));
        }elseif($user->role_id==3){
            return redirect()->intended(route('company.dashboard'));
        }
    }*/
}
