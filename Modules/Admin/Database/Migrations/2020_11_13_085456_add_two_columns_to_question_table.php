<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoColumnsToQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question', function (Blueprint $table) {
            $table->enum('make_decision',array('question','custom'))->after('operator_calculate')->nullable();
            $table->text('calculate_with_costum_val')->after('make_decision')->nullable();
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
            $table->dropColumn('make_decision','calculate_with_costum_val');
        });
    }
}
