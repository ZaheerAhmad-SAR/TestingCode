<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTransmissionDataDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transmission_data_devices', function (Blueprint $table) {
            $table->uuid('transmission_site_id')->after('Site_ID')->nullable();
            $table->uuid('transmission_modility_id')->after('Requested_certification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transmission_data_devices', function (Blueprint $table) {

        });
    }
}
