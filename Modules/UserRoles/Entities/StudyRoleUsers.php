<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\Study;

class StudyRoleUsers extends Model
{
    protected $fillable = ['id', 'study_id', 'user_roles_id', 'user_id', 'role_id'];
    public $incrementing = false;
    public $keyType = 'string';

    public function study()
    {
        return $this->belongsTo(Study::class);
    }
}
