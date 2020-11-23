<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalityPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modality_phases', function (Blueprint $table) {
            $table->id();
            $table->uuid('modility_id')->nullable();
            $table->uuid('phase_id')->nullable();
            $table->tinyInteger('form_type_id')->default(1);
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
        Schema::dropIfExists('modality_phases');
    }
}
