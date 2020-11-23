<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormTypeIdToAdjudicationFormStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjudication_form_status', function (Blueprint $table) {
            $table->uuid('form_type_id')->nullable()->after('modility_id')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjudication_form_status', function (Blueprint $table) {
            //
        });
    }
}
