<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleStudyUser extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'user_id', 'role_id', 'study_id'];
    protected $keyType = 'string';
    protected $table = 'study_role_users';

    public function study()
    {
        return $this->belongsTo(Study::class);
    }
}
