<?php

namespace App\Http\Controllers;

use App\backupCode;
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
        //generate new secret
        $secret = $this->generateSecret();

        //get user
        $user = $request->user();

        //encrypt and then save secret
       // dd(encrypt($secret));
        $user->google2fa_secret = Crypt::encrypt($secret);

        //generate image for QR barcode
        $google2fa = new Google2FA();

        $inlineUrl = $google2fa->getQRCodeInline(
            'OIRRC',
            'info@oirrc.net',
            $secret
        );
        $user->google_auth = $inlineUrl;
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

        return view('2fa/enableTwoFactor',compact('inlineUrl','secret','codes'));
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
