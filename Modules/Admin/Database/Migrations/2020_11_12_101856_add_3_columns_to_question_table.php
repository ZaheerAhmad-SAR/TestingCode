<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add3ColumnsToQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question', function (Blueprint $table) {
            $table->uuid('first_question_id')->after('option_group_id')->nullable();
            $table->uuid('operator_calculate')->after('first_question_id')->nullable();
            $table->uuid('second_question_id')->after('operator_calculate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question', function (Blueprint $table) {
           $table->dropColumn('first_question_id','operator_calculate','second_question_id');
        });
    }
}
