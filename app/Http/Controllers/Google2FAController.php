<?php

namespace App\Http\Controllers;

use App\backupCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\UserRoles\Entities\UserSystemInfo;
use \ParagonIE\ConstantTime\Base32;
use BaconQrCode\Writer;
use PragmaRX\Google2FAQRCode\Google2FA;
use PragmaRX\Recovery\Recovery;

class Google2FAController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {

         //get user
        $user = $request->user();
        //generate new secret
        $secret = $this->generateSecret();

        //generate image for QR barcode
        $google2fa = new Google2FA();
        if(config('app.env') == 'live') {
              $project_name='OCAP';
              $project_url= 'ocap.oirrc.net';
            }
            else{
              $project_name = 'DEVOCAP';
              $project_url =   'devocap.oirrc.net';
            }
        $inlineUrl = $google2fa->getQRCodeInline(
          $project_name,
          $project_url , 
          $secret    
        ); 
     
                $user->qr_flag = '1';
                $user->google_auth = $inlineUrl;
                $user->google2fa_secret = $secret;
                $user->save();   


        //generate backup codes
        $this->recovery = new Recovery();
        $codes = $this->recovery->setCount(10)->setBlocks(1)->setChars(6)
            ->numeric()->toArray();
        $oldCodes = backupCode::where('user_id','=',auth()->user()->id)->get();
        foreach ($oldCodes as $oldCode){
            $oldCode->delete();
        }
        foreach ($codes as $code){
            $bacup_code = new backupCode();
            $bacup_code->user_id = $user->id;
            $bacup_code->backup_code = $code;
            $bacup_code->expiry_duration = Carbon::now()->addDays(60);
            $bacup_code->save();

        }
        $codes = backupCode::where('user_id','=',\auth()->user()->id)->get();
        $user = User::where('id',\Auth()->user()->id)->first();
        return view('2fa/enableTwoFactor',compact('inlineUrl','secret','codes','user'));

    }
    public function verify_code(Request $request){
        
       $user = $request->user();
        //dd($user);
        $google2fa = new Google2FA();
        $secret = $request->input('secret');
        $valid = $google2fa->verifyKey($user->google2fa_secret, $secret);
        if($valid){
            $user->google2fa_secret->google2fa_enable = 1;
            $user->google2fa_secret->save();
            return redirect('2fa')->with('success',"2FA is enabled successfully.");
        }else{
            return redirect('2fa')->with('error',"Invalid verification Code, Please try again.");
       //$current_totp=$google2fa->getCurrentOtp($user->google2fa_secret);
    //     if(isset($request->totp) && $request->totp !=''){
    //         if($inlineUrl == $secret_totp){
    //         echo json_encode('Valide Code');
    //     }else{
    //         echo json_encode('InValid Code');
    //     }
    // }
      }  
     }
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();
        $system_info = UserSystemInfo::where('user_id','=',$user->id)->get();
        foreach ($system_info as $info){
            $info->delete();
        }
        $codes = backupCode::where('user_id','=',$user->id)->get();
        foreach ($codes as $code){
            $code->delete();
        }

        //make secret column blank
        $user->google2fa_secret = null;
        $user->google_auth = null;

        $user->save();
        if(config('app.env') == 'live') {
         unset($_COOKIE['ocap_live_remember_user']);
               setcookie('ocap_live_remember_user', null, -1, '/');
             }
             else {
                unset($_COOKIE['ocap_dev_remember_user']);
               setcookie('ocap_dev_remember_user', null, -1, '/');
             }

        return view('userroles::users.profile',compact('user','codes'));
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    { 
        
        $randomBytes = random_bytes(10);
       

        return Base32::encodeUpper($randomBytes);
    }

    public function getcodes(){
      
        $codes = backupCode::where('user_id','=',\auth()->user()->id)->get();

    }
}
