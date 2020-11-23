<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormVersionNumInAdjudicationFormStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjudication_form_status', function (Blueprint $table) {
            $table->string('form_version_num', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjudication_form_status', function (Blueprint $table) {
            //
        });
    }
}
