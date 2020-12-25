<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'email', 'token', 'role_id'];
}
