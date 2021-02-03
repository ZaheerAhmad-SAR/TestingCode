<?php

namespace Modules\UserRoles\Entities;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\Study;

class StudyRoleUsers extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'study_id', 'user_roles_id', 'user_id', 'role_id'];
    public $incrementing = false;
    public $keyType = 'string';

    public function study()
    {
        return $this->belongsTo(Study::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function role()
    {
    	return $this->belongsTo(Role::class);
    }
}
