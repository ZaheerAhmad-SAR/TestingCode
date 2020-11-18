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

    public static function getStudyAdminRole(){
        $permissionsIdsArray = self::where(function ($query) {
            $query->where('permissions.name', '=', 'studytools.index')
                ->orwhere('permissions.name', '=', 'studytools.store')
                ->orWhere('permissions.name', '=', 'studytools.edit')
                ->orwhere('permissions.name', '=', 'studytools.update');
        })->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $studyAdminRoleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        return $studyAdminRoleId;

    }

    public static function getStudyQCRole(){
        $permissionsIdsArray = self::where(function ($query) {
            $query->where('permissions.name', '=', 'qualitycontrol.index');
        })->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $studyAdminRoleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        return $studyAdminRoleId;
    }

    public static function getStudyGraderRole(){
        $permissionsIdsArray = self::where(function ($query) {
            $query->where('permissions.name', '=', 'grading.index');
        })->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $studyAdminRoleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        return $studyAdminRoleId;
    }

    public static function getStudyAdjudicationRole(){
        $permissionsIdsArray = self::where(function ($query) {
            $query->where('permissions.name', '=', 'adjudication.index');
        })->distinct('id')->pluck('id')->toArray();
        $roleIdsArrayFromRolePermission = RolePermission::whereIn('permission_id', $permissionsIdsArray)->distinct()->pluck('role_id')->toArray();
        $roleIdsArray = Role::where('role_type', '!=', 'super_admin')->distinct()->pluck('id')->toArray();

        $studyAdminRoleId = array_intersect($roleIdsArrayFromRolePermission, $roleIdsArray);

        return $studyAdminRoleId;
    }
}
