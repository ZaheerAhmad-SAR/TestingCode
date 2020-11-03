<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class CrushFtpTransmission extends Model
{
	protected $primaryKey = 'id'; // or null

    protected $fillable = ['data','Transmission_Number','Study_Name','StudyI_ID','sponsor','Study_central_email','Salute',
        'Submitter_First_Name','Submitter_Last_Name','Submitter_email','Submitter_phone','Submitter_Role','Site_Initials',
        'Site_Name','Site_ID','sit_id','PI_Name','PI_FirstName','PI_LastName','PI_email','Site_st_address','Site_city',
        'Site_state','Site_Zip','Site_country','Subject_ID','subj_id','new_subject','StudyEye','visit_name','phase_id',
        'visit_date','ImageModality','modility_id','device_model','device_oirrcID','Compliance','Compliance_comments',
        'Submitted_By','photographer_full_name','photographer_email','photographer_ID','Number_files','transmitted_file_name',
        'transmitted_file_size','archive_physical_location','received_month','received_day','received_year','received_hours',
        'received_minutes','received_seconds','received-mesc','Study_QCO1','StudyQCO2','Study_cc1','Study_cc2','QC_folder',
        'Graders_folder','QClink','Glink','created_by','updated_by','status','is_read','dcm_availability','received_file_format',
        'qc_officerId','qc_officerName','cms_visit_reference','comment','created_date','updated_date'];

    public $table = 'crush_ftp_transmissions';

    public $timestamps = true;
}
