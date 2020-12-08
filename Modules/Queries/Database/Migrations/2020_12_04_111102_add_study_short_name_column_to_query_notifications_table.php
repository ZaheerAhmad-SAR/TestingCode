<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStudyShortNameColumnToQueryNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('query_notifications', function (Blueprint $table) {
        $table->text('study_short_name')->nullable()->after('site_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('query_notifications', function (Blueprint $table) {
        $table->dropColumn('study_short_name');
        });
    }
}
