<?php

namespace Modules\UserRoles\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Preference;

class PreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        /*********************************/
        $preference = Preference::find(1);
        if (null === $preference) {
            Preference::create([
                'id' => 1,
                'study_id' => 'ced232fb-2130-4edd-ba49-99ab8aa141e4',
                'preference_title'    => 'Visit Activation',
                'preference_value'    => 'Transmission',
                'is_selectable'       => 'yes', //yes/no
                'preference_options'  => 'Transmission|Manual', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        } else {
            Preference::updateOrCreate(['id' => 1], [
                'study_id' => 'ced232fb-2130-4edd-ba49-99ab8aa141e4',
                'preference_title'    => 'Visit Activation',
                'preference_value'    => 'Transmission',
                'is_selectable'       => 'yes', //yes/no
                'preference_options'  => 'Transmission|Manual', //Pipe sign seperated options
            ]);
        }
        $preference = Preference::find(2);
        if (null === $preference) {
            Preference::create([
                'id' => 2,
                'study_id' => 'ced232fb-2130-4edd-ba49-99ab8aa141e4',
                'preference_title'    => 'Study Email',
                'preference_value'    => 'study_email@study.com',
                'is_selectable'       => 'no', //yes/no
                'preference_options'  => '', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
        $preference = Preference::find(3);
        if (null === $preference) {
            Preference::create([
                'id' => 3,
                'study_id' => 'ced232fb-2130-4edd-ba49-99ab8aa141e4',
                'preference_title'    => 'Study Cc Email(s)',
                'preference_value'    => 'studyEmail1@study.com,studyEmail2@study.com,studyEmail3@study.com',
                'is_selectable'       => 'no', //yes/no
                'preference_options'  => '', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
        $preference = Preference::find(4);
        if (null === $preference) {
            Preference::create([
                'id' => 4,
                'study_id' => 'ced232fb-2130-4edd-ba49-99ab8aa141e4',
                'preference_title'    => 'Pagination per page',
                'preference_value'    => '25',
                'is_selectable'       => 'yes', //yes/no
                'preference_options'  => '15|25|50|100|200|500|1000|5000', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
        /*********************************/
        /*********************************/
    }
}
