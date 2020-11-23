<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueryNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->text('site_name')->nullable();
            $table->text('cc_email')->nullable();
            $table->text('subject')->nullable();
            $table->text('email_body')->nullable();
            $table->text('email_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('query_notifications');

    }
}
