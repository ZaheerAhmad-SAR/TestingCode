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
use Modules\UserRoles\Entities\UserSystemInfo;

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
        $getbrowser = UserSystemInfoHelper::get_browsers();
        $secret = $user->google2fa_secret;

        if ($user->google2fa_secret) {
            $check_info = UserSystemInfo::where('user_id', '=', $user->id)->get();
            if (count($check_info) > 0) {
                foreach ($check_info as $info) {
                    if (!empty($info->browser_name && $info->browser_name == $getbrowser && $info->remember_flag == 1)) {
                        return redirect(route('studies.index'));
                    }
                    elseif (!empty($info->browser_name && $info->browser_name == $getbrowser)) {
                        Auth::logout();
                        $request->session()->put('2fa:user:id', $user->id);
                        return view('2fa/validate', compact('user'));
                    }
                    elseif (empty($info->browser_name)) {
                        $info->browser_name = $getbrowser;
                        $info->save();
                        Auth::logout();
                        $request->session()->put('2fa:user:id', $user->id);
                        return view('2fa/validate', compact('user', 'secret'));
                    }
                    elseif($info->browser_name != $getbrowser){
                        $qr_flag = $user->qr_flag;

                        $info->browser_name = $getbrowser;
                        $info->user_id = $user->id;
                        $info->save();

                        Auth::logout();
                        $request->session()->put('2fa:user:id', $user->id);
                        return view('2fa/validate', compact('user', 'qr_flag'));
                    }
                }
            }
            else
                {
                   // dd('count is zero');
                Auth::logout();
                    $system_info = new UserSystemInfo();
                    $system_info->user_id = $user->id;
                    $system_info->browser_name = $getbrowser;
                    $user->qr_flag = '1';
                    $system_info->save();
                    $user->save();
                $request->session()->put('2fa:user:id', $user->id);
                return view('2fa/validate', compact('user', 'secret'));
            }
        }
            return redirect(route('studies.index'));
    }



    public function getValidateToken()
    {
        if (session('2fa:user:id')) {
            return view('2fa/validate');
        }

        return redirect('login');
    }


    public function postValidateToken(ValidateSecretRequest $request)
    {
        //get user id and create cache key
        $userId = $request->session()->pull('2fa:user:id');
        $key    = $userId . ':' . $request->totp;

        if($request->remember_browser == 'on'){
            $getbrowser = UserSystemInfoHelper::get_browsers();
            $system_info = UserSystemInfo::where('browser_name','=',$getbrowser)->where('user_id','=',$userId)->first();
            $system_info->remember_flag = '1';
            $system_info->save();
        }
        else{
            $getbrowser = UserSystemInfoHelper::get_browsers();
            $system_info = UserSystemInfo::where('browser_name','=',$getbrowser)->where('user_id','=',$userId)->first();
            $system_info->remember_flag = '0';
            $system_info->save();
        }

        //use cache to store token to blacklist
        Cache::add($key, true, 4);

        //login and redirect user
        Auth::loginUsingId($userId);

        return redirect(route('studies.index'));/*->intended($this->redirectTo);*/
    }
}
