<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryInvestigatorIdToSiteStudyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_study', function (Blueprint $table) {
            $table->uuid('primaryInvestigator_id')->after('site_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_study', function (Blueprint $table) {
            $table->dropColumn('primaryInvestigator_id');
        });
    }
}
