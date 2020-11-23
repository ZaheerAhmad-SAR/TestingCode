<?php

namespace App\Http\Requests;

use App\backupCode;
use Illuminate\Cache;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

use App\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Factory as ValidatonFactory;

class ValidateSecretRequest extends Request
{
    /**
     *
     * @var \App\User
     */
    private $user;

    /**
     * Create a new FormRequest instance.
     *
     * @param \Illuminate\Validation\Factory $factory
     * @return void
     */
    public function __construct(ValidatonFactory $factory)
    {
        $factory->extend(
            'valid_token',
            function ($attribute, $value, $parameters, $validator) {
                $code_verify = backupCode::where('backup_code','=',$value)->first();
                if($code_verify){
                    $code_verify->delete();
                    return true;
                }
                else{
                    $secret = Crypt::decrypt($this->user->google2fa_secret);
                    $google_2fa = new Google2FA();

                    return $google_2fa->verifyKey($secret, $value);
                }
            },
            'Not a valid token'
        );

        $factory->extend(
            'used_token',
            function ($attribute, $value, $parameters, $validator) {
                $key = $this->user->id . ':' . $value;

                return !\Illuminate\Support\Facades\Cache::has($key);
            },
            'Cannot reuse token'
        );


    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->user = User::findOrFail(
                session('2fa:user:id')
            );
        } catch (Exception $exc) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'totp' => 'bail|required|digits:6|valid_token|used_token',
        ];
    }
}
