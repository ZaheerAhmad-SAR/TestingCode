<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudyModilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_modilities', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('parent_modility_id');
            $table->uuid('child_modility_id');
            $table->uuid('study_id');
            $table->uuid('assign_by');
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
        Schema::dropIfExists('study_modalities');
    }
}
