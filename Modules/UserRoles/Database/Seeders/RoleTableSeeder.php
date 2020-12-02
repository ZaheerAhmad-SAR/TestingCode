<?php

namespace Modules\UserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\RolePermission;
use Illuminate\Support\Str;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $check_role = Role::where('name', 'like', 'Super Admin')->first();
        if (null === $check_role) {
            $check_role = Role::create([
                'id' => Str::uuid(),
                'name' => 'Super Admin',
                'description' => 'Super Admin',
                'role_type' => 'super_admin',
                'created_by' => ''
            ]);
        }
        $check_role = Role::where('name', 'like', 'Basic')->first();
        if (null === $check_role) {
            Role::create([
                'id' => Str::uuid(),
                'name' => 'Basic',
                'description' => 'Basic',
                'role_type' => 'system_role',
                'created_by' => ''
            ]);
        }

        $roles = Role::whereIn('name', ['Super Admin', 'Basic'])->get();
        foreach ($roles as $role) {
            if ($role->name == 'Super Admin') {
                $permissions = Permission::all();
                foreach ($permissions as $permission) {
                    $permission_id = $permission->id;
                    $permission_data = RolePermission::where('role_id', '=', $role->id)
                        ->where('permission_id', '=', $permission->id)->first();
                    if (null !== $permission_data) {
                        $permission_data->update([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id
                        ]);
                    } else {
                        $rolepermission = new RolePermission();
                        $rolepermission['role_id'] = $role->id;
                        $rolepermission['permission_id'] = $permission_id;
                        $rolepermission->save();
                    }
                }
            }
            if ($role->name == 'Basic') {
                $permissions = Permission::where('name', '=', 'dashboard.index')
                    ->orwhere('name', '=', 'dashboard.create')
                    ->orwhere('name', '=', 'dashboard.store')
                    ->orwhere('name', '=', 'dashboard.edit')
                    ->orwhere('name', '=', 'dashboard.update')
                    ->get();
                foreach ($permissions as $permission) {
                    $permission_id = $permission->id;
                    $permission_data = RolePermission::where('role_id', '=', $role->id)
                        ->where('permission_id', '=', $permission->id)->first();
                    if (null !== $permission_data) {
                        $permission_data->update([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id
                        ]);
                    } else {
                        $rolepermission = new RolePermission();
                        $rolepermission['role_id'] = $role->id;
                        $rolepermission['permission_id'] = $permission_id;
                        $rolepermission->save();
                    }
                }
            }
        }
    }
}
