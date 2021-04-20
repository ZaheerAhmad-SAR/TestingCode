<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\User;
class UserLog extends Model
{
	protected $table = 'user_logs';
    protected $fillable = ['id', 'user_id', 'online_at', 'offline_at'];
    protected $keyType = 'string';

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
