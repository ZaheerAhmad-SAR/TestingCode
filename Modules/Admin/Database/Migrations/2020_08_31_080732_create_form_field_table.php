<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_field', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('form_field_type_id');
            $table->foreign('form_field_type_id')->references('id')->on('form_field_type')->onDelete('cascade')->onUpdate('cascade');
            $table->string('xls_label')->default('No Label');
            $table->enum('is_exportable_to_xls', array('yes','no'))->default('yes');
            $table->enum('is_required', array('no', 'yes'))->default('no');
            $table->smallInteger('lower_limit')->default(1);
            $table->smallInteger('upper_limit')->default(250);
            $table->smallInteger('field_width')->default(50);
            $table->text('text_info')->nullable();
            $table->text('validation_rules')->nullable();
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
        Schema::dropIfExists('form_field');
    }
}
