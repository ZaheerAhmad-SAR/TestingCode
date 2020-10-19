<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkipLogicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skip_logics', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('question_id')->nullable();
            $table->string('option_title')->nullable();
            $table->text('option_value')->nullable();
            $table->text('activate_forms')->nullable();
            $table->text('activate_sections')->nullable();
            $table->text('activate_questions')->nullable();
            $table->text('deactivate_forms')->nullable();
            $table->text('deactivate_sections')->nullable();
            $table->text('deactivate_questions')->nullable();
            $table->SoftDeletes();
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
        Schema::dropIfExists('skip_logics');
    }
}
