<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('form_field_id');
            $table->uuid('phase_steps_id');
            $table->string('question_text')->nullable();
            $table->string('c_disk')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->enum('is_dependent', array('no', 'yes'))->default('no');
            $table->text('dependent_on')->nullable();
            $table->text('annotations')->nullable();
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
        Schema::dropIfExists('question');
    }
}
