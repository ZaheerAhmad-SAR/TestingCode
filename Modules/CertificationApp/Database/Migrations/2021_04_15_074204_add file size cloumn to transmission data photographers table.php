<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileSizeCloumnToTransmissionDataPhotographersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::table('transmission_data_photographers', function (Blueprint $table) {
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
