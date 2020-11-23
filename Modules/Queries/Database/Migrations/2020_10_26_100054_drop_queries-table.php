<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('queries');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('sender_id')->nullable();
            $table->uuid('receiver_id')->nullable();
            $table->string('messages')->nullable();
            $table->enum('status', ['read', 'unread'])->default('unread');
            $table->timestamps();
        });
    }
}
