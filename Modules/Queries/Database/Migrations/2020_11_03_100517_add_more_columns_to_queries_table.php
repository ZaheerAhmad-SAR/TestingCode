<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queries', function (Blueprint $table) {
        $table->uuid('study_id')->after('queried_remarked_by_id')->nullable();
        $table->uuid('subject_id')->after('study_id')->nullable();
        $table->uuid('study_structures_id')->after('subject_id')->nullable();
        $table->uuid('phase_steps_id')->after('study_structures_id')->nullable();
        $table->uuid('section_id')->after('phase_steps_id')->nullable();
        $table->uuid('question_id')->after('section_id')->nullable();
        $table->uuid('field_id')->after('question_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('queries', function (Blueprint $table) {
         $table->dropColumn('study_id');
         $table->dropColumn('subject_id');
         $table->dropColumn('study_structures_id');
         $table->dropColumn('phase_steps_id');
         $table->dropColumn('section_id');
         $table->dropColumn('question_id');
         $table->dropColumn('field_id');
        });
    }
}
