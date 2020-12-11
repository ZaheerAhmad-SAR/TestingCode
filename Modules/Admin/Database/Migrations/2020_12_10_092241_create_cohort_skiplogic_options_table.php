<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortSkiplogicOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohort_skiplogic_options', function (Blueprint $table) {
           $table->uuid('id')->primary()->unique();
            $table->uuid('cohort_skiplogic_id')->nullable();
            $table->uuid('study_id')->nullable();
            $table->uuid('option_question_id')->nullable();
            $table->text('title')->nullable();
            $table->text('value')->nullable();
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
        Schema::dropIfExists('cohort_skiplogic_options');
    }
}
