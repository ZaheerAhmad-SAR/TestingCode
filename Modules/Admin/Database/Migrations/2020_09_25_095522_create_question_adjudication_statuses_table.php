<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionAdjudicationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_adjudication_statuses', function (Blueprint $table) {
          $table->uuid('id')->primary();
          $table->uuid('question_id')->nullable();
           $table->enum('adj_status',array('yes','no'))->default('no');
           $table->enum('decision_based_on',array('any_change','custom','percentage'))->default('any_change');
           $table->text('opertaor')->nullable();
           $table->enum('differnce_status',array('greater_than','less_than'))->nullable();
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
        Schema::dropIfExists('question_adjudication_statuses');
    }
}
