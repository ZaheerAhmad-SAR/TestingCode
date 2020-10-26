<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;
use BaconQrCode\Writer;
use PragmaRX\Google2FAQRCode\Google2FA;

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

        return view('2fa/enableTwoFactor',compact('inlineUrl','secret'));
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        //make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        return view('userroles::users.profile',compact('user'));
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
}
