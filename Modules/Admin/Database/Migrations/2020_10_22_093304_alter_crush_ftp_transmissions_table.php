<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCrushFtpTransmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('crush_ftp_transmissions', function (Blueprint $table) {
            $table->string('Transmission_Number', 255)->after('data');
            $table->string('Study_Name', 255)->after('Transmission_Number');
            $table->string('StudyI_ID', 255)->after('Study_Name');
            $table->string('sponsor', 255)->after('StudyI_ID');
            $table->string('Study_central_email', 255)->after('sponsor');
            $table->string('Salute', 255)->after('Study_central_email');
            $table->string('Submitter_First_Name', 255)->after('Salute');
            $table->string('Submitter_Last_Name', 255)->after('Submitter_First_Name');
            $table->string('Submitter_email', 255)->after('Submitter_Last_Name');
            $table->string('Submitter_phone', 255)->after('Submitter_email');
            $table->string('Submitter_Role', 255)->after('Submitter_phone');
            $table->string('Site_Initials', 255)->after('Submitter_Role');
            $table->string('Site_Name', 255)->after('Site_Initials');
            $table->string('Site_ID', 255)->after('Site_Name');
            $table->string('PI_Name', 255)->after('Site_ID');
            $table->string('PI_FirstName', 255)->after('PI_Name');
            $table->string('PI_LastName', 255)->after('PI_FirstName');
            $table->string('PI_email', 255)->after('PI_LastName');
            $table->string('Site_st_address', 255)->after('PI_email');
            $table->string('Site_city', 255)->after('Site_st_address');
            $table->string('Site_state', 255)->after('Site_city');
            $table->string('Site_Zip', 255)->after('Site_state');
            $table->string('Site_country', 255)->after('Site_Zip');
            $table->string('Subject_ID', 255)->after('Site_country');
            $table->string('StudyEye', 255)->after('Subject_ID');
            $table->string('visit_name', 255)->after('StudyEye');
            $table->string('visit_date', 255)->after('visit_name');
            $table->string('ImageModality', 255)->after('visit_date');
            $table->string('device_model', 255)->after('ImageModality');
            $table->string('device_oirrcID', 255)->after('device_model');
            $table->string('Compliance', 255)->after('device_oirrcID');
            $table->string('Compliance_comments', 255)->after('Compliance');
            $table->string('Submitted_By', 255)->after('Compliance_comments');
            $table->string('photographer_full_name', 255)->after('Submitted_By');
            $table->string('photographer_email', 255)->after('photographer_full_name');
            $table->string('photographer_ID', 255)->after('photographer_email');
            $table->string('Number_files', 255)->after('photographer_ID');
            $table->string('transmitted_file_name', 255)->after('Number_files');
            $table->string('transmitted_file_size', 255)->after('transmitted_file_name');
            $table->string('archive_physical_location', 255)->after('transmitted_file_size');
            $table->string('received_month', 255)->after('archive_physical_location');
            $table->string('received_day', 255)->after('received_month');
            $table->string('received_year', 255)->after('received_day');
            $table->string('received_hours', 255)->after('received_year');
            $table->string('received_minutes', 255)->after('received_hours');
            $table->string('received_seconds', 255)->after('received_minutes');
            $table->string('received-mesc', 255)->after('received_seconds');
            $table->string('Study_QCO1', 255)->after('received-mesc');
            $table->string('StudyQCO2', 255)->after('Study_QCO1');
            $table->string('Study_cc1', 255)->after('StudyQCO2');
            $table->string('Study_cc2', 255)->after('Study_cc1');
            $table->string('QC_folder', 255)->after('Study_cc2');
            $table->string('Graders_folder', 255)->after('QC_folder');
            $table->string('QClink', 255)->after('Graders_folder');
            $table->string('Glink', 255)->after('QClink');
            $table->dateTime('created_date', 0)->after('Glink');
            $table->dateTime('updated_date', 0)->after('created_date');
            $table->integer('created_by')->after('updated_date');
            $table->integer('updated_by')->after('created_by');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'onhold', 'query_opened'])->default('pending')->after('updated_by');
            $table->enum('is_read', ['no', 'yes'])->after('status');
            $table->enum('dcm_availability', ['no', 'yes'])->default('no')->after('is_read');
            $table->string('received_file_format', 10)->after('dcm_availability');
            $table->integer('qc_officerId')->after('received_file_format');
            $table->string('qc_officerName', 255)->after('qc_officerId');
            $table->integer('cms_visit_reference')->after('qc_officerName');
            $table->longText('comment')->after('cms_visit_reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
