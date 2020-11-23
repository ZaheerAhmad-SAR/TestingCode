<?php

namespace Modules\UserRoles\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserSystemInfo extends Model
{
    protected $fillable = ['browser_name','remember_flag','user_id','qr_flag'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
