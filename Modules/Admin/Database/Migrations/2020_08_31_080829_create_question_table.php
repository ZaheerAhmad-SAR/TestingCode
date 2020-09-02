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
            $table->uuid('id')->primary()->unique();
            $table->uuid('form_field_id');
            $table->foreign('form_field_id')->references('id')->on('form_field')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('phase_steps_id');
            $table->foreign('phase_steps_id')->references('step_id')->on('phase_steps')->onDelete('cascade')->onUpdate('cascade');
            $table->string('question_text')->nullable();
            $table->string('c_disk')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->enum('is_dependent', array('no', 'yes'))->default('no');
            $table->text('dependent_on')->nullable();
            $table->text('annotations')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
