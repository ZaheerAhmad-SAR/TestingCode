<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignWorkColumnToSubjectsPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects_phases', function (Blueprint $table) {
            //
            $table->enum('assign_work', [0, 1])->default(0)->after('is_out_of_window')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects_phases', function (Blueprint $table) {
            //
        });
    }
}
