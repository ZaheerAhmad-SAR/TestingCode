<?php

namespace Modules\UserRoles\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['id','name','description'];
    public $incrementing = false;
    public $keyType = 'string';

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany('Modules\UserRoles\Entities\Permission');
    }
}
