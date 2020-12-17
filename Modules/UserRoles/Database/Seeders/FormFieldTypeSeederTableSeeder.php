<?php

namespace Modules\UserRoles\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

        $id = 1;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_number = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Number',
                'icon'          => 'fa fa-list',
                'support_multiple_values' => 'no',
                'sort_order'    =>  1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 2;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_radio = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Radio',
                'icon'          => 'fa fa-bullseye',
                'support_multiple_values' => 'no',
                'sort_order'    =>  2,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }

        $id = 3;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Dropdown = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Dropdown',
                'icon'          => 'fa fa-caret-square-down',
                'support_multiple_values' => 'no',
                'sort_order'    =>  3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 4;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Checkbox = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Checkbox',
                'icon'          => 'fa fa-check-square',
                'support_multiple_values' => 'no',
                'sort_order'    =>  4,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 5;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Text = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Text',
                'icon'          => 'fa fa-list',
                'support_multiple_values' => 'no',
                'sort_order'    =>  5,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 6;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Textarea = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Textarea',
                'icon'          => 'fa fa-list',
                'support_multiple_values' => 'no',
                'sort_order'    =>  6,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 7;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Date_Time = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Date & Time',
                'icon'          => 'fa fa-calendar-alt',
                'support_multiple_values' => 'no',
                'sort_order'    =>  7,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 8;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Upload = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Upload',
                'icon'          => 'fas fa-cloud-upload-alt',
                'support_multiple_values' => 'no',
                'sort_order'    =>  8,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 10;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Upload = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Certification',
                'icon'          => 'fa fa-list',
                'support_multiple_values' => 'no',
                'sort_order'    =>  10,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 11;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Upload = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Description',
                'icon'          => 'fas fa-text-width',
                'support_multiple_values' => 'no',
                'sort_order'    =>  11,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
        $id = 12;
        $field = FormFieldType::find($id);
        if (null === $field) {
            $form_filed_Upload = FormFieldType::create([
                'id' => $id,
                'field_type'    => 'Calculated',
                'icon'          => 'fas fa-calculator',
                'support_multiple_values' => 'no',
                'sort_order'    =>  12,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }
    }
}
