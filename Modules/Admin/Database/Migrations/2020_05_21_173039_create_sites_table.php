<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->text('site_name')->nullable();
            $table->text('site_manager')->nullable();
            $table->text('site_address')->nullable();
            $table->text('site_city')->nullable();
            $table->text('site_state')->nullable();
            $table->bigInteger('site_phone')->nullable();
            $table->text('site_email')->nullable();
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
        Schema::dropIfExists('sites');
    }
}
