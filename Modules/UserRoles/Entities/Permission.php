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

    public static function getStudyAdminRole()
    {
        $permissionsIdsArray = self::where('permissions.name', 'like', 'studytools.index')
            ->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();
        $roleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);
        if (!empty($roleId)) {
            return $roleId;
        }
        return null;
    }

    public static function getStudyQCRole()
    {
        $permissionsIdsArray = self::where('permissions.name', 'like', 'qualitycontrol.create')
            ->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $roleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        if (!empty($roleId)) {
            return $roleId;
        }
        return null;
    }

    public static function getStudyGraderRole()
    {
        $permissionsIdsArray = self::where('permissions.name', 'like', 'grading.create')
            ->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $roleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        if (!empty($roleId)) {
            return $roleId;
        }
        return null;
    }

    public static function getStudyAdjudicationRole()
    {
        $permissionsIdsArray = self::where('permissions.name', 'like', 'adjudication.create')
            ->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $roleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        if (!empty($roleId)) {
            return $roleId;
        }
        return null;
    }
}
