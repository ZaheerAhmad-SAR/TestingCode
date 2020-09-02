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
            $table->uuid('form_field_type_id');
            $table->foreign('form_field_type_id')->references('id')->on('form_field_type')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('section_id');
            $table->uuid('option_group_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
            $table->string('question_text')->nullable();
            $table->string('c_disk')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->enum('is_dependent', array('no', 'yes'))->default('no')->nullable();
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
