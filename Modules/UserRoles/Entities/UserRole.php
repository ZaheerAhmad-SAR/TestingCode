<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = ['id','user_id','role_id'];
    public $incrementing = false;
    public $keyType = 'string';

    public function role(){
        return $this->hasOne(Role::class,'id','role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany('Modules\UserRoles\Entities\Permission');
    }
}
