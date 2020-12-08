<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransmissionDataDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transmission_data_devices', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->string('Transmission_Number', 255);
            $table->string('Device_Category', 255)->nullable();
            $table->string('Device_manufacturer', 255)->nullable();
            $table->string('Device_Model', 255)->nullable();
            $table->string('Device_Serial', 255)->nullable();
            $table->string('Device_Software_version', 255)->nullable();
            $table->string('Device_OIRRCID', 255)->nullable();
            $table->string('Study_Name', 255)->nullable();
            $table->string('StudyI_ID', 255)->nullable();
            $table->string('Study_central_email', 255)->nullable();
            $table->string('sponsor', 255)->nullable();
            $table->string('Site_Name', 255)->nullable();
            $table->string('Site_ID', 255)->nullable();
            $table->string('PI_Name', 255)->nullable();
            $table->string('Site_st_address', 255)->nullable();
            $table->string('Site_city', 255)->nullable();
            $table->string('Site_state', 255)->nullable();
            $table->string('Site_Zip', 255)->nullable();
            $table->string('Site_country', 255)->nullable();
            $table->string('Requested_certification', 255)->nullable();
            $table->string('Certification_Type', 255)->nullable();
            $table->string('Request_MadeBy_FirstName', 255)->nullable();
            $table->string('Request_MadeBy_LastName', 255)->nullable();
            $table->string('Request_MadeBy_Email', 255)->nullable();
            $table->string('Comments', 255)->nullable();
            $table->string('previous_certification_status', 255)->nullable();
            $table->string('gfModality', 255)->nullable();
            $table->string('gfCertifying_Study', 255)->nullable();
            $table->string('gfCertifying_center', 255)->nullable();
            $table->string('gfCertificate_date', 255)->nullable();
            $table->string('Number_files', 255)->nullable();
            $table->string('transmitted_file_name', 255)->nullable();
            $table->string('transmitted_file_size', 255)->nullable();
            $table->string('archive_physical_location', 255)->nullable();
            $table->string('received_month', 255)->nullable();
            $table->string('received_day', 255)->nullable();
            $table->string('received_year', 255)->nullable();
            $table->string('received_hours', 255)->nullable();
            $table->string('received_minutes', 255)->nullable();
            $table->string('received_seconds', 255)->nullable();
            $table->string('Study_QCO1', 255)->nullable();
            $table->string('StudyQCO2', 255)->nullable();
            $table->string('Study_cc1', 255)->nullable();
            $table->string('Study_cc2', 255)->nullable();
            $table->string('QC_folder', 255)->nullable();
            $table->string('CO_folder', 255)->nullable();
            $table->string('CO_email', 255)->nullable();
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
        Schema::dropIfExists('transmission_data_device');
    }
}
