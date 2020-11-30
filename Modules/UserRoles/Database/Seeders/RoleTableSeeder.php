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

        $role  =   'Super Admin';
        $check_role = Role::where('name', 'like', $role)->first();
        if (null === $check_role) {
            $check_role = Role::create([
                'id' => Str::uuid(),
                'name' => $role,
                'description' => $role,
                'role_type' => 'super_admin',
                'created_by' => ''
            ]);
            $check_role_2 = Role::create([
                'id' => Str::uuid(),
                'name' => 'basic',
                'description' => 'basic',
                'role_type' => 'system_role',
                'created_by' => ''
            ]);
        }
        $roles = Role::all();
        foreach ($roles as $role) {
            if ($role->name == 'Super Admin') {
                $permissions = Permission::all();
                foreach ($permissions as $permission) {
                    $permission_id = $permission->id;
                    $permission_data = RolePermission::where('role_id', '=', $role->id)
                        ->where('permission_id', '=', $permission->id)->first();
                    if (!empty($permission_data)) {
                        $permission_data->update([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id
                        ]);
                    }
                    if (($permission_data == Null)) {
                        //dd($role_id, $permission_id);
                        $rolepermission = new RolePermission();
                        /*$rolepermission['id'] = Str::uuid();*/
                        $rolepermission['role_id'] = $role->id;
                        $rolepermission['permission_id'] = $permission_id;
                        $rolepermission->save();
                    }

                    //                    dd($rolepermission);
                }
                /*$all = RolePermission::all();
                    foreach ($all as $rp){
                        dd($rp);*/
            }
            if ($role->name == 'basic') {
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
                    if (!empty($permission_data)) {
                        $permission_data->update([
                            'role_id' => $role->id,
                            'permission_id' => $permission->id
                        ]);
                    }
                    if (($permission_data == Null)) {
                        //dd($role_id, $permission_id);
                        $rolepermission = new RolePermission();
                        /*$rolepermission['id'] = Str::uuid();*/
                        $rolepermission['role_id'] = $role->id;
                        $rolepermission['permission_id'] = $permission_id;
                        $rolepermission->save();
                    }

                    //                    dd($rolepermission);
                }
            }
        }
    }
}
