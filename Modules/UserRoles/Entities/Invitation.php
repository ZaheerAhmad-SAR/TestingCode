<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['id','email','token','role_id'];
}
