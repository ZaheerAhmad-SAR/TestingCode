<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_answer', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('study_id')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->uuid('study_structures_id')->nullable();
            $table->uuid('phase_steps_id')->nullable();
            $table->uuid('section_id')->nullable();
            $table->uuid('question_id')->nullable();
            $table->uuid('field_id')->nullable();
            $table->text('answer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('final_answer');
    }
}
