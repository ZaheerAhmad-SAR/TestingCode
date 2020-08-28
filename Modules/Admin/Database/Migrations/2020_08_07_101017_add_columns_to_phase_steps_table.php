<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPhaseStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phase_steps', function (Blueprint $table) {
            $table->enum('graders_number',array(0,1,2,3))
                ->default(0)->after('step_description');
            $table->enum('q_c',array('yes','no'))->default('no')->after('graders_number');
            $table->enum('eligibility',array('yes','no'))->default('no')->after('q_c');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phase_steps', function (Blueprint $table) {
            $table->dropColumn('graders_number','q_c','eligibility');
        });
    }
}
