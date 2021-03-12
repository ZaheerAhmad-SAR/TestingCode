<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceSerialAndSoftwareVersionColumnToDeviceSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_sites', function (Blueprint $table) {
            $table->string('device_serial', 255)->nullable()->after('site_id');
            $table->string('device_software_version', 255)->nullable()->after('device_serial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_sites', function (Blueprint $table) {

        });
    }
}
