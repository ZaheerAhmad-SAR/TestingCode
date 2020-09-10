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
            $table->uuid('id');
            $table->uuid('grader_id');
            $table->uuid('adjudicator_id');
            $table->uuid('study_id');
            $table->uuid('study_structures_id');
            $table->uuid('phase_steps_id');
            $table->uuid('section_id');
            $table->uuid('question_id');
            $table->uuid('field_id');            
            $table->text('answer')->nullable();
            $table->enum('is_answer_accepted', array('no', 'yes'))->default('no');
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
        Schema::dropIfExists('answer');
    }
}
