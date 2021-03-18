<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorAuthMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // show the two factor auth form
    public function show2faForm()
    {
    
        return view('2fa');
    }

// post token to the backend for check
    public function sendToken(Request $request)
    { 
        
        $clientIP = request()->ip();
        dd($clientIP);
        $browser = get_browser(null, true);
        dd($browser);
        $get_mac = get_mac_address(null,true);
        dd($get_mac); 

        //$this->validate(['token' => 'required']);

        // $user = auth()->user();
        $user = User::where('email', '=', $request->email)->first();
        $user->two_factor_token = rand(000000, 999999);
        $user->save();
        $mail = Mail::to($user)->send(new TwoFactorAuthMail($user->two_factor_token));

        return redirect('/2f_login/' . encrypt($user->two_factor_token));
    }

    public function verfiyToken(Request $request)
    { 
        
        
        $user = User::where('two_factor_token', '=', $request->two_factor_token)->first();
        dd($user);
        if ($user) {
            if (null !== Auth::loginUsingId($user->id)) {
               // dd(\auth()->user()->id);
                return redirect(route('studies.index'));
            } else {
                dd('else');
            }
        }
    }
}
