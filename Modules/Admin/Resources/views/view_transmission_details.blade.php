@extends ('layouts.home')

@section('title')
    <title> Transmission Details | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')

    <style type="text/css">
        /*.table{table-layout: fixed;}*/

        .select2-container--default
        .select2-selection--single {
            background-color: #fff;
             border: transparent !important;
            border-radius: 4px;
        }
        .select2-selection__rendered {
            font-weight: 400;
            line-height: 1.5;
            color: #495057 !important;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .control-label {
            padding-top: 7px;
            margin-bottom: 0;
        }

        .form-control, .form-control:focus, .form-control:disabled, .form-control[readonly] {
            background-color: #eee !important;
            opacity: 1 !important;
        }
    </style>
    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
@endsection

@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Transmission Details</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Transmission Details</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="card-body">
                        <form>
                            <div class="row">
                                
                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmission Number</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_transmission_no" readonly="" value="{{ $findTransmission[0]->Transmission_Number }}" id="d_transmission_no" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Study_Name }}" readonly="" name="d_study_name" id="d_study_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 1 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study ID</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_study_id" readonly="" value="{{ $findTransmission[0]->StudyI_ID }}" id="d_study_id" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Sponsor</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->sponsor }}" readonly="" name="d_sponsor" id="d_sponsor" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 2 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Salute</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_Salute" readonly="" value="{{ $findTransmission[0]->Salute }}" id="d_Salute" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter First Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Submitter_First_Name }}" readonly="" name="d_submitter_first_name" id="d_submitter_first_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 3 ///////////////////////// -->

                               <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Last Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_submitter_last_name" readonly="" value="{{ $findTransmission[0]->Submitter_Last_Name }}" id="d_submitter_last_name" class="form-control" required="required">
                                </div>

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Email</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Submitter_email }}" readonly="" name="d_submitter_email" id="d_submitter_email" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 4 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Phone</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_submitter_phone" readonly="" value="{{ $findTransmission[0]->Submitter_phone }}" id="d_submitter_phone" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Role</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Submitter_Role }}" readonly="" name="d_submitter_role" id="d_submitter_role" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 5 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Initials</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_site_initial" readonly="" value="{{ $findTransmission[0]->Site_Initials }}" id="d_site_initial" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Site_Name }}" readonly="" name="d_site_name" id="d_site_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 6 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_site_id" readonly="" value="{{ $findTransmission[0]->Site_ID }}" id="d_site_id" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->PI_Name }}" readonly="" name="d_pi_name" id="d_pi_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 7 ///////////////////////// -->

                               <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI First Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_pi_first_name" readonly="" value="{{ $findTransmission[0]->PI_FirstName }}" id="d_pi_first_name" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Last Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->PI_LastName }}" readonly="" name="d_pi_last_name" id="d_pi_last_name" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 8 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Email</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_pi_email" readonly="" value="{{ $findTransmission[0]->PI_email }}" id="d_pi_email" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site St Address</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Site_st_address }}" readonly="" name="d_site_st_address" id="d_site_st_address" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 9 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site City</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_site_city" readonly="" value="{{ $findTransmission[0]->Site_city }}" id="d_site_city" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site State</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Site_state }}" readonly="" name="d_site_state" id="d_site_state" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 10 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Zip</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_site_zip" readonly="" value="{{ $findTransmission[0]->Site_Zip }}" id="d_site_zip" class="form-control" required="required">
                                </div>

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Country</label>
                                </div>
                              
                                <div class=" form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Site_country }}" readonly="" name="d_site_country" id="d_site_country" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 11 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Subject ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_subject_Id" readonly="" value="{{ $findTransmission[0]->Subject_ID }}" id="d_subject_Id" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Eye</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->StudyEye }}" readonly="" name="d_study_eye" id="d_study_eye" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 12 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Visit Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_visit_name" readonly="" value="{{ $findTransmission[0]->visit_name }}" id="d_visit_name" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Visit Date</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->visit_date }}" readonly="" name="d_visit_date" id="d_visit_date" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 13 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Image Modality</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_image_modality" readonly="" value="{{ $findTransmission[0]->ImageModality }}" id="d_image_modality" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Model</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->device_model }}" readonly="" name="d_device_model" id="d_device_model" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 14 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Oirrc ID</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_device_oirrc_id" readonly="" value="{{ $findTransmission[0]->device_oirrcID }}" id="d_device_oirrc_id" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Complaince</label>
                                </div>
                              
                                <div class=" form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Compliance }}" readonly="" name="d_compliance" id="d_compliance" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 14 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Complaince Comments</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_compliance_comments" readonly="" value="{{ $findTransmission[0]->Compliance_comments }}" id="d_compliance_comments" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitted By</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Submitted_By }}" readonly="" name="d_submitted_by" id="d_submitted_by" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 15 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Full Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_photographer_full_name" readonly="" value="{{ $findTransmission[0]->photographer_full_name }}" id="d_photographer_full_name" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Email</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->photographer_email }}" readonly="" name="d_photographer_email" id="d_photographer_email" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 16 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_photographer_id" readonly="" value="{{ $findTransmission[0]->photographer_ID }}" id="d_photographer_id" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Number Files</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Number_files }}" readonly="" name="d_number_files" id="d_number_files" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 17 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmitted File Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_transmitted_files_name" readonly="" value="{{ $findTransmission[0]->transmitted_file_name }}" id="d_transmitted_files_name" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmitted File Size</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->transmitted_file_size }}" readonly="" name="d_transmitted_file_size" id="d_transmitted_file_size" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 18 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Archive Physical Location</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_archive_physical_location" readonly="" value="{{ $findTransmission[0]->archive_physical_location }}" id="d_archive_physical_location" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Month</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->received_month }}" readonly="" name="d_recieve_month" id="d_recieve_month" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 19 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Day</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_recieve_day" readonly="" value="{{ $findTransmission[0]->received_day }}" id="d_recieve_day" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Year</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->received_year }}" readonly="" name="d_recieve_year" id="d_recieve_year" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 20 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Hours</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_recieve_hours" readonly="" value="{{ $findTransmission[0]->received_hours }}" id="d_recieve_hours" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Minutes</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->received_minutes }}" readonly="" name="d_recieve_minutes" id="d_recieve_minutes" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 21 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study QCO1</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_study_qco1" readonly="" value="{{ $findTransmission[0]->Study_QCO1 }}" id="d_study_qco1" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study QCO2</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->StudyQCO2 }}" readonly="" name="d_study_qco2" id="d_study_qco2" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 22 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Cc1</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_study_cc1" readonly="" value="{{ $findTransmission[0]->Study_cc1 }}" id="d_study_cc1" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Cc2</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Study_cc2 }}" readonly="" name="d_study_cc2" id="d_study_cc2" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 23 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">QC Folder</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_qc_folder" readonly="" value="{{ $findTransmission[0]->QC_folder }}" id="d_qc_folder" class="form-control" required="required">
                                </div>

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Graders Folder</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Graders_folder }}" readonly="" name="d_grader_folders" id="d_grader_folders" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 24 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">QC link</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_qc_link" readonly="" value="{{ $findTransmission[0]->QClink }}" id="d_qc_link" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Glink</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission[0]->Glink }}" readonly="" name="d_glink" id="d_glink" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 25 ///////////////////////// -->
                                
                                <div class="col-md-11">
                                </div>

                                <div class="col-md-1" style="padding-top: 15px;">
                                    <a href="{{route('transmissions.index')}}" class="btn btn-danger">Close</a>
                                </div>
                               
                            </div>
                            <!-- row ends -->
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

@endsection
@section('script')

<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

@endsection




