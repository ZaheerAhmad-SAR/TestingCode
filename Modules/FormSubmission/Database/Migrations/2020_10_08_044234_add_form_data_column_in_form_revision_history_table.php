<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormDataColumnInFormRevisionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_revision_history', function (Blueprint $table) {
            $table->text('form_data')->after('edit_reason_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_revision_history', function (Blueprint $table) {
            $table->dropColumn('form_data');
        });
    }
}
