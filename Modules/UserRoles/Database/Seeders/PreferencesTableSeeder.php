<?php

namespace Modules\UserRoles\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Preference;
use Modules\Admin\Entities\Study;
use Modules\Admin\Scopes\PreferencesByStudy;

class PreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    }
    public function run123()
    {
        Model::unguard();
        /*********************************/
        $studies = Study::all();
        foreach ($studies as $study) {
            $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'VISIT_ACTIVATION')->withOutGlobalScopes()->first();
            if (null === $preference) {
                Preference::create([
                    'study_id' => $study->id,
                    'preference_title'    => 'VISIT_ACTIVATION',
                    'preference_value'    => 'Manual',
                    'is_selectable'       => 'yes', //yes/no
                    'preference_options'  => 'Transmission|Manual', //Pipe sign seperated options
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now()
                ]);
            }
            $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'STUDY_EMAIL')->withOutGlobalScopes()->first();
            if (null === $preference) {
                Preference::create([
                    'study_id' => $study->id,
                    'preference_title'    => 'STUDY_EMAIL',
                    'preference_value'    => 'study_email@study.com',
                    'is_selectable'       => 'no', //yes/no
                    'preference_options'  => '', //Pipe sign seperated options
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now()
                ]);
            }

            $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'STUDY_CC_EMAILS')->withOutGlobalScopes()->first();
            if (null === $preference) {
                Preference::create([
                    'study_id' => $study->id,
                    'preference_title'    => 'STUDY_CC_EMAILS',
                    'preference_value'    => 'studyEmail1@study.com,studyEmail2@study.com,studyEmail3@study.com',
                    'is_selectable'       => 'no', //yes/no
                    'preference_options'  => '', //Pipe sign seperated options
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now()
                ]);
            }

            $preference = Preference::where('study_id', 'like', $study->id)->where('preference_title', 'like', 'PER_PAGE_PAGINATION')->withOutGlobalScopes()->first();
            if (null === $preference) {
                Preference::create([
                    'study_id' => $study->id,
                    'preference_title'    => 'PER_PAGE_PAGINATION',
                    'preference_value'    => '25',
                    'is_selectable'       => 'yes', //yes/no
                    'preference_options'  => '15|25|50|100|200|500|1000|5000', //Pipe sign seperated options
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now()
                ]);
            }
        }
        /*********************************/
        /*********************************/
    }
}
