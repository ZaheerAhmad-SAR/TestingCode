<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromQuestionValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_validations', function (Blueprint $table) {
            $table->dropColumn('decision_one');
            $table->dropColumn('opertaor_one');
            $table->dropColumn('dep_on_question_one_id');
            $table->dropColumn('custom_value_one');
            $table->dropColumn('condition');
            $table->dropColumn('decision_two');
            $table->dropColumn('opertaor_two');
            $table->dropColumn('dep_on_question_two_id');
            $table->dropColumn('custom_value_two');
            $table->dropColumn('error_type');
            $table->dropColumn('error_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_validations', function (Blueprint $table) {
            //
        });
    }
}
