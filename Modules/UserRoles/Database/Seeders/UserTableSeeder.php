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

        $user   =   User::find(1);
        $role = Role::first();
        $role_id = $role->id;

        if (!$user){
            $user =  User::create([
                'id'    => Str::uuid(),
                 'role_id'  => $role_id,
                 'name' =>  'admin',
                'email' =>  'admin@admin.com',
                'user_type' => 'system_user',
                'password'  =>  Hash::make('12345678'),
            ]);
            dd($user);
            $user   =   User::first();
            $user_id = $user->id;
            $role = Role::first();
            $role_id = $role->id;

            UserRole::create([
                'id'    => Str::uuid(),
                'role_id'   =>  $role_id,
                'user_id'   =>  $user_id
            ]);
        }
        else{
            dd('useer exists');
        }
    }
}
