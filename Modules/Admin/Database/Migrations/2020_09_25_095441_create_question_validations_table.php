<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_validations', function (Blueprint $table) {
          $table->uuid('id')->primary();
          $table->uuid('question_id')->nullable();
          $table->enum('decision_one',array('question_value','custom_value'))->nullable();
          $table->text('opertaor_one')->nullable();
          $table->uuid('dep_on_question_one_id')->nullable();
          $table->text('custom_value_one')->nullable();
          $table->enum('decision_two',array('question_value','custom_value'))->nullable();
          $table->text('opertaor_two')->nullable();
          $table->uuid('dep_on_question_two_id')->nullable();
          $table->text('custom_value_two')->nullable();
          $table->enum('error_type',array('waring','error'))->nullable();
          $table->text('error_message')->nullable();
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
        Schema::dropIfExists('question_validations');
    }
}
