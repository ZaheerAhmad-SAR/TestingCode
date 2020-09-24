<?php

namespace Modules\UserRoles\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Entities\FormType;

class FormTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $type_forms = FormType::all();
        if (count($type_forms) <= 0){
            $id = 1;
            $form_type_qc = FormType::create([
                'id' => $id,
                'form_type'    => 'QC',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
            $id = 2;
            $form_type_grading = FormType::create([
                'id' => $id,
                'form_type'    => 'Grading',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
            $id = 3;
            $form_type_eligibility = FormType::create([
                'id' => $id,
                'form_type'    => 'Eligibility',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
            $id = 4;
            $form_type_others = FormType::create([
                'id' => $id,
                'form_type'    => 'Others',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
    }
}
