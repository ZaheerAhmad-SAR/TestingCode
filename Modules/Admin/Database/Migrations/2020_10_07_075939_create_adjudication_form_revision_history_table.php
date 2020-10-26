<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjudicationFormRevisionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjudication_form_revision_history', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('adjudication_form_submit_status_id')->nullable();
            $table->mediumText('adjudication_form_edit_reason_text')->nullable();
            $table->text('adjudication_form_data')->nullable();
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
        Schema::dropIfExists('adjudication_form_revision_history');
    }
}
