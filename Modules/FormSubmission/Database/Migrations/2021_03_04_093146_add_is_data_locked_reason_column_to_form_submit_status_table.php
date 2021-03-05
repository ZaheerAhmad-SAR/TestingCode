<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDataLockedReasonColumnToFormSubmitStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_submit_status', function (Blueprint $table) {
            $table->text('is_data_locked_reason')->nullable()->after('is_data_locked');
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

        });
    }
}
