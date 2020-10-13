<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModilityToPhaseStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phase_steps', function (Blueprint $table) {
            //
            $table->integer('modility_id')->unsigned()->nullable();
            $table->foreign('modility_id')
                  ->references('id')
                  ->on('modilities')
                  ->onDelete('cascade');  
        });              
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phase_steps', function (Blueprint $table) {
            //
            $table->dropForeign('modility_id');
        });
    }
}
