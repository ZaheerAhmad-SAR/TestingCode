<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('study_id')->nullable();
            $table->string('subject_id')->nullable();
            $table->foreign('study_id')->references('id')->on('studies')->onDelete('cascade')->onUpdate('cascade');
            $table->date('enrollment_date')->nullable();
            $table->uuid('site_id');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->enum('study_eye',array('OD','OS','OU','NA'))->default('NA')->nullable();
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
        Schema::dropIfExists('subjects');
    }
}
