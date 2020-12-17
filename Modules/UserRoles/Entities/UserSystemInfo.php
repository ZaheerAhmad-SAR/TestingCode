<?php

namespace Modules\UserRoles\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSystemInfo extends Model
{
    use SoftDeletes;
    protected $fillable = ['browser_name', 'remember_flag', 'user_id', 'qr_flag'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
