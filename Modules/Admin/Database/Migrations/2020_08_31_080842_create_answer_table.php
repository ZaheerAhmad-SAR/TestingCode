<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('grader_id')->nullable();
            $table->uuid('adjudicator_id')->nullable();
            $table->uuid('subject_id')->nullable();
            $table->uuid('study_id')->nullable();
            $table->uuid('study_structures_id')->nullable();
            $table->uuid('phase_steps_id')->nullable();
            $table->uuid('section_id')->nullable();
            $table->uuid('question_id')->nullable();
            $table->uuid('field_id')->nullable();
            $table->text('answer')->nullable();
            $table->enum('is_answer_accepted', array('no', 'yes'))->default('no');
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
        Schema::dropIfExists('answer');
    }
}
