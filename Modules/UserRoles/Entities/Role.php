<?php

namespace Modules\UserRoles\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\RoleStudy;

class Role extends Model
{
    protected $fillable = ['id','name','description','created_by'];
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

    public function study()
    {
        return $this->hasMany(RoleStudy::class);
    }

}
