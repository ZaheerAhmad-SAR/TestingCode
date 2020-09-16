<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    public $incrementing = false;

    public $table = 'permissions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'name',
        'for',
        'controller_name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function roles()
    {
        return $this->belongsToMany('Modules\UserRoles\Entities\Roles');
    }
}
