<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModilityAbbreviationInModilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modilities', function (Blueprint $table) {
            $table->string('modility_abbreviation')->nullable();
        });
    }
}
