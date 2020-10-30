<?php

namespace Modules\UserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserRolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(StudySeederTableSeeder::class);
        $this->call(FormFieldTypeSeederTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(FormTypeTableSeeder::class);
        $this->call(ValidationRulesTableSeeder::class);
        $this->call(PreferencesTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }
}
