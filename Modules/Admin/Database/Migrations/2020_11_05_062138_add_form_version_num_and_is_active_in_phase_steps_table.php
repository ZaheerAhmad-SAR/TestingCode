<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormVersionNumAndIsActiveInPhaseStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phase_steps', function (Blueprint $table) {
            $table->string('form_version_num', 50)->nullable();
            $table->tinyInteger('is_active')->default(0)->nullable();
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
            //
        });
    }
}
