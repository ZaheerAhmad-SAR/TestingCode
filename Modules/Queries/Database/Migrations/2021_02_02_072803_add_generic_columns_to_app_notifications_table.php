<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGenericColumnsToAppNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_notifications', function (Blueprint $table) {
            $table->enum('notifications_type', ['query','bugReport'])
                ->default('query')->after('is_read');
            $table->uuid('queryorbugid')->nullable()->after('notifications_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_notifications', function (Blueprint $table) {
            $table->dropColumn('notifications_type');
            $table->dropColumn('queryorbugid');
        });
    }
}
