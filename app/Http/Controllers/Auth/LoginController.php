<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use App\Helpers\UserSystemInfoHelper;

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


    private function authenticated(Request $request, Authenticatable $user)
    {
        if ($user->google2fa_secret) {
            Auth::logout();

            $request->session()->put('2fa:user:id', $user->id);

            return redirect('2fa/validate');
        }

        return redirect(route('studies.index'));
    }

/*    private function authenticated(Request $request, Authenticatable $user)
    {
        $getbrowser = UserSystemInfoHelper::get_browsers();
        if ($user->browser_name != Null && $user->browser_name == $getbrowser) {
            //login and redirect user
            Auth::loginUsingId($user->id);

            return redirect(route('studies.index'));

        } elseif ($user->browser_name != $getbrowser) {
            $user->browser_name = $getbrowser;
            $user->save();
            if ($user->google2fa_secret) {
                Auth::logout();

                $request->session()->put('2fa:user:id', $user->id);

                return view('2fa/validate', compact('user'));
            }

            return redirect()->intended($this->redirectTo);
        }
    }*/

    public function getValidateToken()
    {
        if (session('2fa:user:id')) {
            return view('2fa/validate');
        }

        return redirect('login');
    }

   /* public function postValidateToken(ValidateSecretRequest $request)
    {

        //get user id and create cache key
        $user = User::where('id','=',session('2fa:user:id'))->first();

        //login and redirect user
        Auth::loginUsingId($user->id);

        return redirect(route('studies.index'));
    }*/

    public function postValidateToken(ValidateSecretRequest $request)
    {
        //get user id and create cache key
        $userId = $request->session()->pull('2fa:user:id');
        $key    = $userId . ':' . $request->totp;

        //use cache to store token to blacklist
        Cache::add($key, true, 4);

        //login and redirect user
        Auth::loginUsingId($userId);

        return redirect(route('studies.index'));
    }
}
