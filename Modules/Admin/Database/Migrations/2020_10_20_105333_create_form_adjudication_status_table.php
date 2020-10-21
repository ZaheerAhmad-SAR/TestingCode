<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormAdjudicationStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_adjudication_status', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('form_adjudicated_by_id')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->uuid('study_id')->nullable();
            $table->uuid('study_structures_id')->nullable();
            $table->uuid('phase_steps_id')->nullable();
            $table->uuid('section_id')->nullable();
            $table->uuid('modility_id')->nullable();
            $table->enum('adjudication_status', array('resumable', 'incomplete', 'complete', 'no_status'))->default('incomplete');
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
        Schema::dropIfExists('form_adjudication_status');
    }
}
