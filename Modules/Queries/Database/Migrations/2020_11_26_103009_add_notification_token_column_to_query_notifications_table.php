<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationTokenColumnToQueryNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('query_notifications', function (Blueprint $table) {
        $table->uuid('notifications_token')->nullable()->after('notifications_status');
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
        $table->dropColumn('notifications_token');
        });
    }
}
