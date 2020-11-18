<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToStudyRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('study_role_users', function (Blueprint $table) {
            $table->uuid('user_id')->after('study_id')->nullable();
            $table->uuid('role_id')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('study_role_users', function (Blueprint $table) {
                $table->dropColumn('user_id','role_id');
        });
    }
}
