<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionAdjudicationRequiredTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_adjudication_required', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('subject_id')->nullable();
            $table->uuid('study_id')->nullable();
            $table->uuid('study_structures_id')->nullable();
            $table->uuid('phase_steps_id')->nullable();
            $table->uuid('section_id')->nullable();
            $table->uuid('question_id')->nullable();
            $table->string('val_difference', 10)->nullable();
            $table->string('is_percentage')->default('no')->nullable();
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
        Schema::dropIfExists('question_adjudication_required');
    }
}
