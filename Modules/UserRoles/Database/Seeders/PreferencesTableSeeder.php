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
                'preference_title'    => 'Visit Activation',
                'preference_value'    => 'Transmission',
                'is_selectable'       => 'yes', //yes/no
                'preference_options'  => 'Transmission|Manual', //Pipe sign seperated options
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
        /*********************************/
        /*********************************/
    }
}
