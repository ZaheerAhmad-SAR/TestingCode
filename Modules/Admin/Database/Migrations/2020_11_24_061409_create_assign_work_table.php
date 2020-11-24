<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_work', function (Blueprint $table) {
            $table->id();
            $table->uuid('subject_id')->nullable();
            $table->uuid('phase_id')->nullable();
            $table->uuid('modility_id')->nullable();
            $table->string('form_type_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->dateTime('assign_date', 0);
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
        Schema::dropIfExists('assign_work');
    }
}
