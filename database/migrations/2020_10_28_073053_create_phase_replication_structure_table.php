<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhaseReplicationStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phase_replication_structure', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('study_structures_id')->nullable();
            $table->uuid('replicated_study_structures_id')->nullable();
            $table->text('replication_structure')->nullable();
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
        Schema::dropIfExists('phase_replication_structure');
    }
}
