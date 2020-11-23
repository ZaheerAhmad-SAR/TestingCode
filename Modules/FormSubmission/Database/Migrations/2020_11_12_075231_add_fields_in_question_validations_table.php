<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInQuestionValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_validations', function (Blueprint $table) {
            $table->integer('parameter_1')->nullable()->after('validation_rule_id');
            $table->integer('parameter_2')->nullable()->after('parameter_1');
            $table->string('message_type', 50)->nullable()->after('parameter_2');
            $table->string('message')->nullable()->after('message_type');
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
