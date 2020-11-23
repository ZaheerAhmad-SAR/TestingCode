<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('parent_query_id')->nullable();
            $table->uuid('module_id')->nullable();
            $table->uuid('queried_remarked_by_id')->nullable();
            $table->text('module_name')->nullable();
            $table->text('messages')->nullable();
            $table->text('query_subject')->nullable();
            $table->text('query_url')->nullable();
            $table->enum('query_type',array('user','role'))->nullable();
            $table->text('query_attachments');
            $table->enum('query_status', ['new','open','confirmed', 'unconfirmed', 'close','in progress'])
                ->default('new');

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
        Schema::dropIfExists('new_queries');
    }
}
