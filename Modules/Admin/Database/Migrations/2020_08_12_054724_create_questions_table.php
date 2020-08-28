<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('study_id');
            // $table->foreign('study_id')->references('id')->on('studies')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type')->nullable();
            $table->longText('basic')->nullable();
            $table->longText('data_validation')->nullable();
            $table->longText('dependencies')->nullable();
            $table->longText('annotations')->nullable();
            $table->longText('advanced')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
