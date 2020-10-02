<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFormStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_submit_status', function (Blueprint $table) {
            $table->dropColumn('form_status');
        });

        Schema::table('form_submit_status', function (Blueprint $table) {
            $table->enum('form_status', array('resumable', 'incomplete', 'complete', 'adjudication', 'not_required', 'no_status'))->default('incomplete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_submit_status', function (Blueprint $table) {
            $table->dropColumn('form_status');
        });
    }
}
