<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVisitDateColumnInSubjectsPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_phases', function (Blueprint $table) {
            DB::statement("ALTER TABLE `subjects_phases` CHANGE `visit_date` `visit_date` DATETIME NULL DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects_phases', function (Blueprint $table) {
            $table->dropColumn('visit_date');
        });
    }
}
