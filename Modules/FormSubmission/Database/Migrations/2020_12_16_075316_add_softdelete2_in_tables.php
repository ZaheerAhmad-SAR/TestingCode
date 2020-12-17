<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdelete2InTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tablesArray = [
            'validation_rules',
            'user_system_infos',
            'permission_role',
            'phase_replication_structure',
            'phase_steps_roles',
            'question_adjudication_required',
            'study_structures_roles',
        ];
        foreach ($tablesArray as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }
}
