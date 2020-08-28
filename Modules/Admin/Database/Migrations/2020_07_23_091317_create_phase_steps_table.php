<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhaseStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phase_steps', function (Blueprint $table) {
            $table->uuid('step_id')->unique()->primary();
            $table->uuid('phase_id');
            $table->string('step_position');
            $table->string('step_name');
            $table->string('step_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phase_steps');
    }
}
