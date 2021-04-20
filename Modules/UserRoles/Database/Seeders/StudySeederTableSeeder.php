<?php

namespace Modules\UserRoles\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\UserRole;
use Illuminate\Support\Str;

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
        $study = Study::all();
        if (count($study) <= 0) {
            $studyID = (string)Str::uuid();
            $study = Study::create([
                'id'    => $studyID,
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
                'user_id'       => (string)Str::uuid()
            ]);

            $studyUser = User::where('name', 'Super Admin')->first();
            if ($studyUser->name = 'Super Admin') {
                $studyUser = new RoleStudyUser;
                    $studyUser->id    = (string)Str::uuid();
                    $studyUser->user_id   = $studyUser->id;
                    $studyUser->role_id   = $studyUser->role_id;
                    $studyUser->study_id  = $studyID;
                    $studyUser->save();
            }
        }
    }
}
