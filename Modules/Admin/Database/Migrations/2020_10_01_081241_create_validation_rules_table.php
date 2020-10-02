<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidationRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validation_rules', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('rule')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('rule_group')->nullable();
            $table->tinyInteger('is_active')->default('1');
            $table->tinyInteger('is_range')->default('0');
            $table->tinyInteger('is_related_to_other_field')->default('1');
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
        Schema::dropIfExists('validation_rules');
    }
}
