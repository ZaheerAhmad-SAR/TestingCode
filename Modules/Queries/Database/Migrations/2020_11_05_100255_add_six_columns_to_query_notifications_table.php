<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSixColumnsToQueryNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('query_notifications', function (Blueprint $table) {
        $table->uuid('parent_notification_id')->after('email_attachment')->nullable();
        $table->uuid('notification_remarked_id')->after('parent_notification_id')->nullable();
        $table->uuid('study_id')->after('notification_remarked_id')->nullable();
        $table->uuid('subject_id')->after('study_id')->nullable();
        $table->uuid('transmission_number')->after('subject_id')->nullable();
        $table->text('vist_name')->after('transmission_number')->nullable();
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
        $table->dropColumn('parent_notification_id');
        $table->dropColumn('notification_remarked_id');
        $table->dropColumn('study_id');
        $table->dropColumn('subject_id');
        $table->dropColumn('transmission_number');
        $table->dropColumn('vist_name');
        });
    }
}
