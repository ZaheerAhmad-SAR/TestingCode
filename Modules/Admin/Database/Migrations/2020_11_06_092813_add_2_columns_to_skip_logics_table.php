<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add2ColumnsToSkipLogicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skip_logics', function (Blueprint $table) {
            $table->text('textbox_value')->after('option_value')->nullable();
            $table->text('number_value')->after('textbox_value')->nullable();
            $table->string('operator')->after('number_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skip_logics', function (Blueprint $table) {
            $table->dropColumn('textbox_value','number_value','operator');
        });
    }
}
