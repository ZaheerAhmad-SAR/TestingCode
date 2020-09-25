<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trail_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('user_id', 36);
            $table->string('user_name', 191);
            $table->char('role_id', 36);
            $table->char('event_id', 36);
            $table->string('event_type', 191);
            $table->string('event_message', 191);
            $table->text('ip_address');
            $table->char('study_id', 36)->nullable();
            $table->text('event_url');
            $table->text('event_details')->nullable();
            $table->text('event_old_details')->nullable();
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
        Schema::dropIfExists('trail_logs');
    }
}
