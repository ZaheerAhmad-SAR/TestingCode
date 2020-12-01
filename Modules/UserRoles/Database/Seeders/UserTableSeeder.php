<?php

namespace Modules\UserRoles\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\UserRole;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $userCheck   =   User::where('name', 'like', 'Super Admin')->first();
        $role = Role::where('name', 'like', 'Super Admin')->first();
        $role_id = $role->id;
        if (null === $userCheck) {
            $user_id = Str::uuid();
            User::create([
                'id'    => $user_id,
                'role_id'  => $role_id,
                'name' =>  'Super Admin',
                'email' =>  'superadmin@admin.com',
                'user_type' => 'super_user',
                'password'  =>  Hash::make('at@m|c_en@rgy1272'),
                'created_by' => ''
            ]);
            UserRole::create([
                'id'    => Str::uuid(),
                'role_id'   =>  $role_id,
                'user_id'   =>  $user_id,
                'user_type' => '2'
            ]);
        }
    }
}
