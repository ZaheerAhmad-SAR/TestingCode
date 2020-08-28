<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options_groups', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->text('option_group_name')->nullable();
            $table->text('option_group_description')->nullable();
            $table->text('option_layout')->nullable();
            $table->text('option_name')->nullable();
            $table->text('option_value')->nullable();
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
        Schema::dropIfExists('options_groups');
    }
}
