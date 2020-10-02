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
            $table->smallIncrements('id')->unique();
            $table->string('rule')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('rule_group', ['Radio-Checkbox-Select-Dropdown', 'Text-Textarea-Email', 'Number', 'Date-Time-Month-Day-Year', 'Upload-File', 'Certification', 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL', 'All'])->nullable();
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->enum('is_related_to_other_field', ['0', '1'])->default('1');
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
