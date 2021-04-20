<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressbarStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressbar_studies', function (Blueprint $table) {
            $table->id();
            $table->uuid('study_id')->nullable();
            $table->string('qc_percentage')->nullable();
            $table->string('grading_percentage')->nullable();
            $table->string('adjudication_percentage')->nullable();
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
        Schema::dropIfExists('progressbar_studies');
    }
}
