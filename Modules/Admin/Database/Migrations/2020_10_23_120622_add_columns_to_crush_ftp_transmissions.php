<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCrushFtpTransmissions extends Migration
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
            $table->uuid('subj_id')->after('Subject_ID')->nullable();
            $table->uuid('phase_id')->after('visit_name')->nullable();
            $table->uuid('modility_id')->after('ImageModality')->nullable();
            $table->uuid('qc_officerId')->change();
            
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
