<?php

namespace Modules\UserRoles\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserRole extends Model
{
    use softDeletes;
    protected $fillable = ['id', 'user_id', 'role_id', 'study_id'];
    public $incrementing = false;
    public $keyType = 'string';

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany('Modules\UserRoles\Entities\Permission');
    }

    public static function createUserRole($userId, $roleId, $studyId = '', $userType = '')
    {
        $userRoleCheck = UserRole::where('role_id', 'like', $roleId)->where('user_id', 'like', $userId)->first();
        if (null === $userRoleCheck) {
            $id = (string)Str::uuid();
            UserRole::create([
                'id'    => $id,
                'role_id' => $roleId,
                'user_id' => $userId,
                'study_id' => $studyId,
                'user_type' => $userType
            ]);
        }
    }
}
