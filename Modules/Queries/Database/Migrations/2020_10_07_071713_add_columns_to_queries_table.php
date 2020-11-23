<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queries', function (Blueprint $table) {
            $table->uuid('parent_query_id')->nullable();
            $table->uuid('queried_remarked_by_id')->nullable();
            $table->uuid('module_id')->nullable();
            $table->string('module_name')->nullable();
            $table->enum('query_status', ['new','open', 'close'])->default('new');
            $table->dropColumn('sender_id','receiver_id','status');
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
            $table->dropColumn('parent_query_id');
            $table->dropColumn('queried_remarked_by_id');
            $table->dropColumn('module_id');
            $table->dropColumn('module_name');
            $table->dropColumn('query_status');
        });
    }
}
