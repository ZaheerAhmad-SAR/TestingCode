<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsreplicateOrCloneInDifferentTables extends Migration
{


    public function up()
    {
        $tablesArray = [
            'cohort_skiplogic',
            'cohort_skiplogic_options',
            'skip_logics',
            'question_options',
        ];
        foreach ($tablesArray as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->uuid('parent_id')->default('no-parent')->nullable();
            });
        }

        $tablesArray = [
            'study_structures',
            'phase_steps',
            'sections',
            'question',
            'question_validations',
            'question_dependencies',
            'question_adjudication_statuses',
            'form_field',
            'studies',
            'cohort_skiplogic',
            'cohort_skiplogic_options',
            'skip_logics',
            'question_options',
        ];
        foreach ($tablesArray as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('replicating_or_cloning', 15)->default('not-any')->nullable();
            });
        }

        Schema::table('studies', function (Blueprint $table) {
            $table->uuid('parent_id')->default('no-parent')->nullable()->change();
        });
    }
}
