<?php

namespace Modules\UserRoles\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Admin\Entities\FormFieldType;

class FormFieldTypeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $id = Str::uuid();
        $form_filed_number = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Number',
            'icon'          => 'fa fa-list',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_radio = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Radio',
            'icon'          => 'fa fa-bullseye',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Dropdown = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Dropdown',
            'icon'          => 'fa fa-caret-square-down',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Checkbox = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Checkbox',
            'icon'          => 'fa fa-check-square',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Text = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Text',
            'icon'          => 'fa fa-list',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Textarea = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Textarea',
            'icon'          => 'fa fa-list',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Date_Time = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Date & Time',
            'icon'          => 'fa fa-calendar-alt',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Upload = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Upload',
            'icon'          => 'fas fa-cloud-upload-alt',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        $id = Str::uuid();
        $form_filed_Upload = FormFieldType::create([
            'id' => $id,
            'field_type'    => 'Certification',
            'icon'          => 'fa fa-list',
            'support_multiple_values' => 'no',
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);

        // $this->call("OthersTableSeeder");
    }
}
