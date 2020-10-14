<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects_phases', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('subject_id')->nullable();
            $table->uuid('phase_id')->nullable();
            $table->string('visit_date')->nullable();
            $table->tinyInteger('is_out_of_window')->default(0);
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
        Schema::dropIfExists('subjects_phases');
    }
}
