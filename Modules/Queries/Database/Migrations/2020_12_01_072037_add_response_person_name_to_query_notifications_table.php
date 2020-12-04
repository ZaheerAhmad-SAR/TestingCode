<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponsePersonNameToQueryNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('query_notifications', function (Blueprint $table) {
        $table->string('person_name')->nullable()->after('notification_remarked_id');
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
        $table->dropColumn('person_name');
        });
    }
}
