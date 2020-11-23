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

        $user   =   User::get();
        $role = Role::first();
        $role_id = $role->id;

        if (count($user) <= 0){
            $user =  User::create([
                'id'    => Str::uuid(),
                 'role_id'  => $role_id,
                 'name' =>  'admin',
                'email' =>  'admin@admin.com',
                'user_type' => 'super_user',
                'password'  =>  Hash::make('Cyb#rG@tE@1234'),
                'created_by' => ''
            ]);
//            dd($user);
            $user   =   User::first();
            $user_id = $user->id;
            $role = Role::first();
            $role_id = $role->id;

            UserRole::create([
                'id'    => Str::uuid(),
                'role_id'   =>  $role_id,
                'user_id'   =>  $user_id,
                'user_type' => '2'
            ]);
        }
        else{
            dd('useer exists');
        }
    }
}
