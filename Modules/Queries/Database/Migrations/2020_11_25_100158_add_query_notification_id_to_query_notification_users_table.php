<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQueryNotificationIdToQueryNotificationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('query_notification_users', function (Blueprint $table) {
         $table->uuid('query_notification_id')->after('query_notification_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('query_notification_users', function (Blueprint $table) {
         $table->dropColumn('query_notification_id');
        });
    }
}
