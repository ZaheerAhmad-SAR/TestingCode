<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToCrushFtpTransmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crush_ftp_transmissions', function (Blueprint $table) {
            //
            $table->uuid('sit_id')->after('Site_ID')->nullable();
            $table->enum('new_subject', [0, 1])->default(0)->after('subj_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crush_ftp_transmissions', function (Blueprint $table) {
            //
        });
    }
}
