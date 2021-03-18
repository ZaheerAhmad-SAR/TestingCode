<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransmissionDeviceIdColumnToTransmissionDataPhotographersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transmission_data_photographers', function (Blueprint $table) {
            $table->uuid('transmission_device_id')->after('Device_Model')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transmission_data_photographers', function (Blueprint $table) {
        });
    }
}
