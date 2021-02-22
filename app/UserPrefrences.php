<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserPrefrences extends Model
{
    protected $fillable = ['id','user_id','default_theme','default_pagination','created_by','deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
