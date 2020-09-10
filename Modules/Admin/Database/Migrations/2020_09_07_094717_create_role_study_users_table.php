<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleStudyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_study_users', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('study_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('role_id')->nullable();

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
        Schema::dropIfExists('role_study_users');
    }
}