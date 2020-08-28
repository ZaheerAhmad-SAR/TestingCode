<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studies', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->text('study_short_name')->nullable();
            $table->text('study_title')->nullable();
            $table->text('study_code')->nullable();
            $table->text('protocol_number')->nullable();
            $table->enum('study_phase',['0','1','2','3','4'])->default('0')->nullable();
            $table->text('trial_registry_id')->nullable();
            $table->text('study_sponsor')->nullable();
            $table->date('start_date')->default(now())->nullable();
            $table->date('end_date')->default(now())->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('studies');
    }
}
