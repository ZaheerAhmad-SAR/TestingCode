<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class RoleStudyUser extends Model
{
    protected $fillable = ['id','user_id','role_id','study_id'];
    protected $keyType = 'string';
    protected $table = 'study_role_users';
}
