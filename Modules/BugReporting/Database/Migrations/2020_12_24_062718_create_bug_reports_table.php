<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBugReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->text('bug_title')->nullable();
            $table->text('bug_message')->nullable();
            $table->uuid('parent_bug_id')->nullable();
            $table->uuid('bug_reporter_by_id')->nullable();
            $table->enum('status', ['open', 'close'])->default('open');
            $table->enum('open_status', ['Unconfirmed', 'Untriaged','Available','Assigned','Started'])->default('Unconfirmed');
            $table->enum('closed_status', ['Fixed', 'Verified','Duplicate','WontFix','ExternalDependency','FixUnreleased','Invalid'])->default('Fixed');
            $table->enum('bug_priority', ['low', 'high','medium'])->default('low');
            $table->text('bug_attachments')->nullable();
            $table->text('bug_url')->nullable();
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
        Schema::dropIfExists('bug_reports');
    }
}
