<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToStudySitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_study', function (Blueprint $table) {
          //  $table->foreign('study_id')->references('id')->on('studies');
          //  $table->foreign('site_id')->references('id')->on('sites');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_sites', function (Blueprint $table) {
           // $table->dropForeign('site_id');
          //  $table->dropForeign('study_id');

        });
    }
}
