<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditingReasonColumnInFormSubmitStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_submit_status', function (Blueprint $table) {
            $table->mediumText('edit_reason_text')->nullable();
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
            $table->dropColumn('edit_reason_text');
        });
    }
}
