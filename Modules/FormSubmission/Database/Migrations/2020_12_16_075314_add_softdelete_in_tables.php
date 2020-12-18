<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tablesArray = [
            'assign_work',
            'backup_codes',
            'coordinators',
            'crush_ftp_transmissions',
            'device_modilities',
            'device_sites',
            'disease_cohorts',
            'form_types',
            'form_version',
            'invitations',
            'modality_phases',
            'options_groups',
            'others',
            'photographers',
            'preferences',
            'primary_investigators',
            'queries',
            'query_notifications',
            'query_notification_users',
            'query_users',
            'question_options',
            'roles',
            'role_queries',
            'sites',
            'site_study',
            'site_study_coordinators',
            'study_role_users',
            'study_user',
            'subjects_phases',
            'trail_logs',
            'transmission_data_devices',
            'transmission_data_photographers',
            'transmission_update_details',
            'user_roles',
        ];
        foreach ($tablesArray as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }
}
