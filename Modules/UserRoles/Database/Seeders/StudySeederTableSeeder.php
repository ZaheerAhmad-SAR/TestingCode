<?php

namespace Modules\UserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Modules\Admin\Entities\Study;

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

        // $this->call("OthersTableSeeder");
        $study = Study::find(1);

        if (!$study){
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
        }
    }
}
