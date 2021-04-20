<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_sites', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('device_id')->nullable();
            $table->text('device_name')->nullable();
            $table->uuid('site_id')->nullable();
            $table->text('device_serial_no')->nullable();
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
        Schema::dropIfExists('device_site');
    }
}
