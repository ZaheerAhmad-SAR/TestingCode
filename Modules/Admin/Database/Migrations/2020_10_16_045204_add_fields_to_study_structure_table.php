<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToStudyStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('study_structures', function (Blueprint $table) {
            $table->tinyInteger('is_repeatable')->default(0)->nullable();
            $table->uuid('parent_id')->default('no-parent')->nullable();
            $table->integer('count')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_structures', function (Blueprint $table) {
            $table->dropColumn('is_repeatable');
            $table->dropColumn('parent_id');
            $table->dropColumn('count');
        });
    }
}
