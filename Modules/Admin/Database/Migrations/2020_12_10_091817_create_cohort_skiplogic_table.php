<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCohortSkiplogicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cohort_skiplogic', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('study_id')->nullable();
            $table->string('cohort_name')->nullable();
            $table->text('cohort_id')->nullable();
            $table->text('deactivate_forms')->nullable();
            $table->text('deactivate_sections')->nullable();
            $table->text('deactivate_questions')->nullable();
            $table->SoftDeletes();
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
        Schema::dropIfExists('cohort_skiplogic');
    }
}
