<?php

namespace App\Http\Controllers\Auth;

use App\backupCode;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests\ValidateSecretRequest;
use App\Helpers\UserSystemInfoHelper;
use Modules\UserRoles\Entities\UserSystemInfo;
use Modules\UserRoles\Entities\UserLog;
use Illuminate\Support\Str;
use App\Helpers\helper;
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

    public function login(Request $request)
    {
        
        //dd(get_mac_address());
      
        //$this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ((int)auth()->user()->is_active == 1) {
                
                $user = User::where('email', $request->email)->first();
                $user->working_status = 'online';
                $user->online_at = now();
                $user->save();
                $id    = (string)Str::uuid();
                UserLog::create([
                    'id' => $id,
                    'user_id'    => $user->id,
                    'online_at' => now()
                ]);
                return $this->sendLoginResponse($request);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    private function authenticated(Request $request, Authenticatable $user)
    {
    
    $user = User::where('id','=', $user->id) ->first();
       
         if($user->qr_flag==1)    
         {
            if (isset($_COOKIE['ocap_remember_user'])) 
               {       
                return redirect(route('studies.index'));
                }  
            else{ 
                Auth::logout();

                $request->session()->put('2fa:user:id', $user->id);
                return view('2fa/validate', compact('user'));
            }
         }
         else{
             return redirect(route('studies.index'));
         }
          
        
       
    }



    // public function getValidateToken()
    // {
    
    //     if (session('2fa:user:id')) {
    //         return view('2fa/validate');
    //     }

    //     return redirect('login');
    // }


    public function postValidateToken(Request $request)
    {

     $userId = session('2fa:user:id');
    
     $user = User::where('id','=', session('2fa:user:id')) ->first();
        
     
       

          $google_2fa = new Google2FA();

        $valid = $google_2fa->verifyKey($user->google2fa_secret, $request->totp);



        if($valid)
        {

         
        if ($request->remember_browser == 'on') {
            if(config('app.env') == 'live') {

            setcookie('ocap_live_remember_user','yes',false,'/');
          }
          else 
          {
               setcookie('ocap_dev_remember_user','yes',false,'/');
          }
        } 

      elseif (config('app.env') == 'live') {
          # code...
        if (isset($_COOKIE['ocap_live_remember_user'])) {
               unset($_COOKIE['ocap_live_remember_user']);
               setcookie('ocap_live_remember_user', null, -1, '/');
            }
      }
      // {
      //        if(config('app.env') == 'live') {

      //       if (isset($_COOKIE['ocap_live_remember_user'])) {
      //          unset($_COOKIE['ocap_live_remember_user']);
      //          setcookie('ocap_live_remember_user', null, -1, '/');
      //       }
      //     }
          else
          {
              if (isset($_COOKIE['ocap_dev_remember_user'])) {
               unset($_COOKIE['ocap_dev_remember_user']);
               setcookie('ocap_dev_remember_user', null, -1, '/');
        
          }


        }
         Auth::loginUsingId($userId);

          return redirect(route('studies.index'));
    }
     else 
     {
     return redirect(route('login'));

    }
}
   
    
}
