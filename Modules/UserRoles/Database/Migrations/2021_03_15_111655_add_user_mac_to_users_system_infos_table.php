<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserMacToUsersSystemInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_system_infos', function (Blueprint $table) {
            $table->text('user_mac')->nullable()->after('browser_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_system_infos', function (Blueprint $table) {
            $table->dropColumn('user_mac');
        });
    }
}
