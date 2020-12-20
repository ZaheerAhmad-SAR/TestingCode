<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_type', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('study_id')->nullable();
            $table->text('phase_ids')->nullable();
            $table->uuid('form_type_id')->nullable();
            $table->uuid('modility_id')->nullable();
            $table->string('titles_values', 15)->nullable();
            $table->string('export_type_title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_type');
    }
}
