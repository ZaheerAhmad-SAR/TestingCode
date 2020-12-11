<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudySetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_setups', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('study_email');
            $table->text('study_cc_email');
            $table->text('allowed_no_transmission');
            $table->uuid('study_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_setups');
    }
}
