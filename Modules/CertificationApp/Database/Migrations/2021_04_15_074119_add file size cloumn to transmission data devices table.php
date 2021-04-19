<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileSizeCloumnToTransmissionDataDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::table('transmission_data_devices', function (Blueprint $table) {
            $table->string('Submitted_Files')->nullable();
            $table->text('transmitted_file_list')->nullable();
            $table->string('Received_Zip')->nullable();
            $table->string('Received_Zip_Size')->nullable();
            $table->string('Received_Zip_MD5')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
