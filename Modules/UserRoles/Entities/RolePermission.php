<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RolePermission extends Model
{
    use SoftDeletes;
    protected $table    =   'permission_role';
    protected $fillable = ['role_id', 'permission_id'];
}
