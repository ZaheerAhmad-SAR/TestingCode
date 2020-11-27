<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOneColumnToQueryNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('query_notifications', function (Blueprint $table) {
            $table->enum('notifications_status', ['new','open','read','close'])
                ->default('new')->after('cc_email');
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
        $table->dropColumn('notifications_status');
        });
    }
}
