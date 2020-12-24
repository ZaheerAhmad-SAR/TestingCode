<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhaseIdToCohortSkiplogicOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cohort_skiplogic_options', function (Blueprint $table) {
            
            $table->uuid('phase_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cohort_skiplogic_options', function (Blueprint $table) {

            $table->dropColumn('phase_id');
        });
    }
}
