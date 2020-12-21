<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certification_data', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('certificate_id')->nullable();
            $table->uuid('photographer_id')->nullable();
            $table->string('photographer_email')->nullable();
            $table->string('cc_emails')->nullable();
            $table->uuid('study_id')->nullable();
            $table->string('study_name')->nullable();
            $table->uuid('site_id')->nullable();
            $table->string('site_name')->nullable();
            $table->uuid('device_id')->nullable();
            $table->string('device_model')->nullable();
            $table->string('device_serial_no')->nullable();
            $table->string('user_input_device_id')->nullable();
            $table->string('modility_id')->nullable();
            $table->string('certificate')->nullable();
            $table->string('certificate_for')->nullable();
            $table->string('certificate_status')->nullable();
            $table->string('certificate_type')->nullable();
            $table->string('grandfather_certificate_id')->nullable();
            $table->string('transmissions')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('change_date')->nullable();
            $table->uuid('certification_officer_id')->nullable();
            $table->string('certificate_file_name')->nullable();
            $table->enum('transmission_type', ['device_transmission','photographer_transmission','system_transmission'])->nullable();
            $table->enum('validity', ['no','yes'])->default('yes');

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
        Schema::dropIfExists('certification_data');
    }
}
