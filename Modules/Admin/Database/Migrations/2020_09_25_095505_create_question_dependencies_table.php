<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionDependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_dependencies', function (Blueprint $table) {
          $table->uuid('id')->primary();
          $table->uuid('question_id')->nullable();
          $table->enum('q_d_status',array('yes','no'))->default('no');
          $table->text('opertaor')->nullable();
          $table->uuid('dep_on_question_id')->nullable();
          $table->text('custom_value')->nullable();

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
        Schema::dropIfExists('question_dependencies');
    }
}
