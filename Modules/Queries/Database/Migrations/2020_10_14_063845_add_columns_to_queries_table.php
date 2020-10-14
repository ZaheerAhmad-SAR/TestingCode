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
        $table->text('query_subject')->after('messages')->nullable();
        $table->text('query_url')->after('query_subject')->nullable();
        $table->enum('query_type',array('user','role'))->after('query_url')->nullable();
        $table->uuid('parent_query_id')->after('id')->change();
        $table->uuid('queried_remarked_by_id')->after('parent_query_id')->change();
        $table->uuid('module_id')->after('queried_remarked_by_id')->change();
        $table->text('module_name')->after('module_id')->change();
        //$table->enum('query_status',array('new','open', 'close'))->after('module_name')->default('new')->change();
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
            $table->dropColumn(['query_subject','query_url','query_type']);

        });
    }
}
