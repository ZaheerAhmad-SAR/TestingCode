<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransmissionDataPhotographersTable extends Migration
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
            $table->string('PI_email', 255)->after('PI_Name')->nullable();
            $table->string('notification', 255)->after('CO_email')->nullable();
            $table->string('notification_list', 255)->after('notification')->nullable();
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
