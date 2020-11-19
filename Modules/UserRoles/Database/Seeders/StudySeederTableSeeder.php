<?php

namespace Modules\UserRoles\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Modules\Admin\Entities\Study;
use Modules\UserRoles\Entities\StudyRoleUsers;
use Modules\UserRoles\Entities\UserRole;
use Psy\Util\Str;

class StudySeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
      /*  $study = Study::all();
        if (count($study) <= 0){
            $study = Study::create([
                'id'    => \Illuminate\Support\Str::uuid(),
                'study_short_name'  =>  'Test Study',
                'study_title' => 'Study Title',
                'study_status'  => 'Development',
                'study_code' => '0001',
                'protocol_number'=> '0001',
                'study_phase'=>Null,
                'trial_registry_id'=>'0001',
                'study_sponsor'=>'Study Sponsor',
                'start_date' => Date::now(),
                'end_date' => Date::now()->addYears(2),
                'description'   =>  'Description will go here',
                'user_id'       => \Illuminate\Support\Str::uuid()
            ]);


            $studyUser = User::first();
            if ($studyUser->name = 'admin'){
                StudyRoleUsers::create([
                    'id'    => \Illuminate\Support\Str::uuid(),
                    'user_id'   => $studyUser->id,
                    'role_id'   => $studyUser->role_id,
                    'study_id'  => $study->id
                ]);
            }
        }*/

    }
}
