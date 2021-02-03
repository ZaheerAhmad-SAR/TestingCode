<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSomeColumnFromAppNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('app_notifications', function(Blueprint $table) {
            $table->dropColumn('bug_report_id');
            $table->dropColumn('query_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_notifications', function(Blueprint $table) {
            $table->uuid('bug_report_id');
            $table->uuid('query_id');
        });
    }
}
