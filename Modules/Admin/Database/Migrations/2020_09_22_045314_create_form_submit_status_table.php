<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSubmitStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submit_status', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('form_filled_by_user_id')->nullable();
            $table->uuid('form_filled_by_user_role_id')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->uuid('study_id')->nullable();
            $table->uuid('study_structures_id')->nullable();
            $table->uuid('phase_steps_id')->nullable();
            $table->uuid('section_id')->nullable();
            $table->smallInteger('form_type_id')->nullable();
            $table->enum('form_status', array('resumable', 'incomplete', 'complete'))->default('resumable');
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
        Schema::dropIfExists('form_submit_status');
    }
}
