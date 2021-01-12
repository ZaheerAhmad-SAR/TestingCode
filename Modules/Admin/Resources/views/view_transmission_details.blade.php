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
            padding-top: 9px;
            margin-bottom: 0;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #eee !important;
            opacity: 1 !important;
        }

        .select2-selection__rendered {
            background-color: @if ($findTransmission->status == 'accepted') #eee; @else #fff; @endif
            opacity: 1 !important;
        }

        .span-text {
            color: red;
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
                        <form action="{{ route('transmissions.update', encrypt($findTransmission->id))}}" method="POST" class="transmission-form">
                            <div class="row">
                                @csrf
                                @method('PUT')
                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmission Number</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="hidden" name="phase_steps" id="phase_steps" value="{{ $getStepForVisit }}">

                                    <input type="text" name="d_transmission_no" readonly="" value="{{ $findTransmission->Transmission_Number }}" id="d_transmission_no" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Study_Name }}" readonly="" name="d_study_name" id="d_study_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 1 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->StudyI_ID }}</span>
                                    <select name="d_study_id" id="d_study_id" class="form-control remove-readonly" disabled="" required="required">
                                        <option value="">Select Study</option>
                                        @foreach($systemStudies as $study)
                                        <option @if($study->study_code == $findTransmission->StudyI_ID) selected @endif value="{{ $study->study_code }}">{{$study->study_code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Sponsor</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->sponsor }}" readonly="" name="d_sponsor" id="d_sponsor" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 2 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Salute</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_Salute" readonly="" value="{{ $findTransmission->Salute }}" id="d_Salute" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter First Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Submitter_First_Name }}" readonly="" name="d_submitter_first_name" id="d_submitter_first_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 3 ///////////////////////// -->

                               <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Last Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_submitter_last_name" readonly="" value="{{ $findTransmission->Submitter_Last_Name }}" id="d_submitter_last_name" class="form-control" required="required">
                                </div>

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Email</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Submitter_email }}" readonly="" name="d_submitter_email" id="d_submitter_email" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 4 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Phone</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_submitter_phone" readonly="" value="{{ $findTransmission->Submitter_phone }}" id="d_submitter_phone" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Role</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Submitter_Role }}" readonly="" name="d_submitter_role" id="d_submitter_role" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 5 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Initials</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_site_initial" readonly="" value="{{ $findTransmission->Site_Initials }}" id="d_site_initial" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_Name }}" readonly="" name="d_site_name" id="d_site_name" class="form-control remove-readonly" required="required">
                                </div>

                              <!--//////////////// row 6 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->Site_ID }}</span>
                                    <select name="d_site_id" id="d_site_id" class="form-control remove-readonly" disabled="" required="required">
                                        <option value="">Select Site</option>
                                        @foreach($getSites as $site)
                                        <option @if($site->site_code == $findTransmission->Site_ID) selected @endif value="{{$site->id.'__/__'.$site->site_code}}">{{$site->site_code}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->PI_Name }}" readonly="" name="d_pi_name" id="d_pi_name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row 7 ///////////////////////// -->

                               <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI First Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_pi_first_name" readonly="" value="{{ $findTransmission->PI_FirstName }}" id="d_pi_first_name" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Last Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->PI_LastName }}" readonly="" name="d_pi_last_name" id="d_pi_last_name" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 8 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Email</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_pi_email" readonly="" value="{{ $findTransmission->PI_email }}" id="d_pi_email" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site St Address</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_st_address }}" readonly="" name="d_site_st_address" id="d_site_st_address" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 9 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site City</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_site_city" readonly="" value="{{ $findTransmission->Site_city }}" id="d_site_city" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site State</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_state }}" readonly="" name="d_site_state" id="d_site_state" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 10 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Zip</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_site_zip" readonly="" value="{{ $findTransmission->Site_Zip }}" id="d_site_zip" class="form-control remove-readonly" required="required">
                                </div>

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Country</label>
                                </div>
                              
                                <div class=" form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_country }}" readonly="" name="d_site_country" id="d_site_country" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 11 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Subject ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->Subject_ID }}</span>
                                    <select name="d_subject_Id" id="d_subject_Id" class="form-control remove-readonly" disabled="" required="required">
                                        <option value="">Select Subject</option>
                                        <option @if ($findTransmission->new_subject == 1) selected @endif value="1">New Subject</option>
                                        @foreach($getSubjects as $subject)
                                        <option @if($subject->subject_id == $findTransmission->Subject_ID) selected @endif value="{{$subject->id.'__/__'.$subject->subject_id}}">{{$subject->subject_id}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Eye</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->StudyEye }}" readonly="" name="d_study_eye" id="d_study_eye" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 12 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Visit Name</label>
                                </div>

                                <div class="form-group col-sm-3">

                                    <span class="span-text">{{ $findTransmission->visit_name }}</span>
                                    <select name="d_visit_name" id="d_visit_name" class="form-control remove-readonly" disabled="" required="required">
                                        <option value="">Select Visit</option>
                                        @foreach($getPhases as $phase)
                                        <option @if($phase->name == $findTransmission->visit_name) selected @endif value="{{$phase->id.'__/__'.$phase->name}}">{{$phase->name}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Visit Date</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ date('d-M-Y', strtotime($findTransmission->visit_date)) }}</span>
                                    <input type="date" value="{{ date('Y-m-d', strtotime($findTransmission->visit_date)) }}" readonly="" name="d_visit_date" id="d_visit_date" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 13 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Image Modality</label>
                                </div>

                                <div class="form-group col-sm-3">

                                    <span class="span-text">{{ $findTransmission->ImageModality }}</span>
                                    <select name="d_image_modality" id="d_image_modality" class="form-control remove-readonly" disabled="" required="required">
                                        <option value="">Select Modality</option>
                                        @foreach($getModalities as $modality)
                                        <option @if($modality->modility_name == $findTransmission->ImageModality) selected @endif value="{{$modality->id.'__/__'.$modality->modility_name}}">{{$modality->modility_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Model</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->device_model }}" readonly="" name="d_device_model" id="d_device_model" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 14 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device OIRRC ID</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_device_oirrc_id" readonly="" value="{{ $findTransmission->device_oirrcID }}" id="d_device_oirrc_id" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Complaince</label>
                                </div>
                              
                                <div class=" form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Compliance }}" readonly="" name="d_compliance" id="d_compliance" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 14 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Complaince Comments</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_compliance_comments" readonly="" value="{{ $findTransmission->Compliance_comments }}" id="d_compliance_comments" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitted By</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Submitted_By }}" readonly="" name="d_submitted_by" id="d_submitted_by" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 15 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Full Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_photographer_full_name" readonly="" value="{{ $findTransmission->photographer_full_name }}" id="d_photographer_full_name" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Email</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->photographer_email }}" readonly="" name="d_photographer_email" id="d_photographer_email" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row 16 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_photographer_id" readonly="" value="{{ $findTransmission->photographer_ID }}" id="d_photographer_id" class="form-control" required="required">
                                </div>

                                {{--

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Number Files</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Number_files }}" readonly="" name="d_number_files" id="d_number_files" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 17 ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmitted File Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_transmitted_files_name" readonly="" value="{{ $findTransmission->transmitted_file_name }}" id="d_transmitted_files_name" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmitted File Size</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->transmitted_file_size }}" readonly="" name="d_transmitted_file_size" id="d_transmitted_file_size" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 18 ///////////////////////// -->

                                

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Archive Physical Location</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_archive_physical_location" readonly="" value="{{ $findTransmission->archive_physical_location }}" id="d_archive_physical_location" class="form-control" required="required">
                                </div>

                                --}}

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Date</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ date('d-M-Y', strtotime($findTransmission->received_year.'-'.$findTransmission->received_month.'-'.$findTransmission->received_day))}}" readonly="" name="d_recieve_month" id="d_recieve_month" class="form-control" required="required">
                                </div>


                                <!--//////////////// row 19 ///////////////////////// -->

                                {{--

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Day</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_recieve_day" readonly="" value="{{ $findTransmission->received_day }}" id="d_recieve_day" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Year</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->received_year }}" readonly="" name="d_recieve_year" id="d_recieve_year" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 20 ///////////////////////// -->


                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Hours</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_recieve_hours" readonly="" value="{{ $findTransmission->received_hours }}" id="d_recieve_hours" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Received Minutes</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->received_minutes }}" readonly="" name="d_recieve_minutes" id="d_recieve_minutes" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 21 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study QCO1</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_study_qco1" readonly="" value="{{ $findTransmission->Study_QCO1 }}" id="d_study_qco1" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study QCO2</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->StudyQCO2 }}" readonly="" name="d_study_qco2" id="d_study_qco2" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 22 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Cc1</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_study_cc1" readonly="" value="{{ $findTransmission->Study_cc1 }}" id="d_study_cc1" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Cc2</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Study_cc2 }}" readonly="" name="d_study_cc2" id="d_study_cc2" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 23 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">QC Folder</label>
                                </div>

                                <div class=" form-group col-sm-3">
                                    <input type="text" name="d_qc_folder" readonly="" value="{{ $findTransmission->QC_folder }}" id="d_qc_folder" class="form-control" required="required">
                                </div>

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Graders Folder</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Graders_folder }}" readonly="" name="d_grader_folders" id="d_grader_folders" class="form-control" required="required">
                                </div>

                               

                                <!--//////////////// row 24 ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">QC link</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="d_qc_link" readonly="" value="{{ $findTransmission->QClink }}" id="d_qc_link" class="form-control" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Glink</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Glink }}" readonly="" name="d_glink" id="d_glink" class="form-control" required="required">
                                </div>

                                <!--//////////////// row 25 ///////////////////////// -->

                                 --}}

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Status</label>
                                </div>

                                <div class="form-group col-md-9">
                                    <select name="status" id="status" class="form-control remove-readonly" required="required" disabled>
                                        <option value="">Select Status</option>
                                        <option @if ($findTransmission->status == 'pending') selected @endif value="pending">Pending</option>
                                        <option  @if ($findTransmission->status == 'accepted') selected @endif value="accepted">Accepted</option>
                                        <option  @if ($findTransmission->status == 'rejected') selected @endif value="rejected">Rejected</option>
                                        <option  @if ($findTransmission->status == 'onhold') selected @endif value="onhold">On-Hold</option>
                                        <option  @if ($findTransmission->status == 'query_opened') selected @endif value="query_opened">Open Query</option>
                                    </select>
                                </div>

                                <!-- //////////////////// row 26 //////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Reason for change</label>
                                </div>

                                <div class="form-group col-md-9">

                                    <textarea class="form-control" required="required"  @if ($findTransmission->status == 'accepted') disabled @endif name="comment" rows="4"></textarea>
                                </div>

                            <!-- ///////////////////////////// row 27 ///////////////////// -->

                              

                                @if ($findTransmission->status == 'accepted')

                                    <div class="col-md-11"></div>
                                    <div class="col-md-1" style="padding-top: 15px;">
                                        <a href="{{route('transmissions.index')}}" class="btn btn-danger">Close</a>
                                    </div>

                                @else

                                    <div class="col-md-10"></div>
                                    <div class="col-md-2 edit-section" style="padding-top: 15px;">
                                        <!-- <a href="javascript:void(0)" class="btn btn-success edit-transmission">
                                            Edit
                                        </a> -->

                                        <button class="btn btn-success update-transmission"\
                                          type="submit" name="submit">Update
                                        </button>

                                        <a href="{{route('transmissions.index')}}" class="btn btn-danger">
                                            Close
                                        </a>
                                    </div>

                                @endif

                            </div>
                            <!-- row ends -->
                        </form>
                        <br>
                        <hr>

                        <!-- Transmission Update Details -->
                        
                            <div style="background-color:#00A8B3;">
                                <h4 style="color: white; text-align: center; padding-top: 5px; padding-bottom: 5px;">
                                    Changes Log (Transmission Data)
                                </h4>
                            </div>
                        
                            <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Time of Update</th>
                                        <th>Change By</th>
                                        <th>Reason for change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissionUpdates->isEmpty())
                                    @foreach($getTransmissionUpdates as $transmissionUpdates)
                                    
                                        <tr>
                                            <td>
                                                {{ date('d-M-Y', strtotime($transmissionUpdates->created_at)).' '.date('h:m:s', strtotime($transmissionUpdates->created_at)) }}
                                            </td>
                                            <td>
                                                {{ $transmissionUpdates->users->name }}
                                            </td>
                                            <td>
                                                {{ $transmissionUpdates->comment }}
                                            </td>
                                        </tr>
                                    
                                    @endforeach
                                    @else
                                    <tr>
                                        <td style="text-align: center;" colspan="3">No record found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                         
                        </div>
                        <!-- Transmission Update Details -->
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

<script type="text/javascript">

    // $('.edit-transmission').click(function() {

        // $('.remove-readonly').each(function() {
        //     // remove attr
        //     $(this).removeAttr('readonly');
        //     $(this).removeAttr('disabled');
        //     // remove classes
        //     $(this).removeClass('.form-control:disabled');
        //     $(this).removeClass('.form-control[readonly]');
            
        // });
        // // change background of select2
        // $('.select2-selection__rendered').css('background-color', '#fff !important;');

    //     // hide edit button
    //     $(this).remove();
    //     $('.edit-section').prepend('<button class="btn btn-success update-transmission"\
    //                                   type="submit" name="submit">Update\
    //                                 </button> ');

    // });

    $('document').ready(function () {
    // check status and apply changes as per need
        if ($('select[name="status"]').val() != 'accepted') {

                $('.remove-readonly').each(function() {
                // remove attr
                $(this).removeAttr('readonly');
                $(this).removeAttr('disabled');
                $(this).removeAttr('required');
                // remove classes
                $(this).removeClass('.form-control:disabled');
                $(this).removeClass('.form-control[readonly]');
                
                }); //each ends
            
        } // if ends

        // initialize select 2
        $('select[name="d_subject_Id"]').select2();
        $('select[name="d_visit_name"]').select2();
        $('select[name="d_image_modality"]').select2();
        $('select[name="d_site_id"]').select2();
        $('select[name="d_study_id"]').select2();

        // show alert on status changed value
        $('select[name="status"]').change(function() {

            if ($(this).val() == 'accepted') {

                    // apply requires attribute
                    $('.remove-readonly').each(function() {

                        $(this).attr('required', true);
                       
                    }); //each ends
                    
                // show alert message
                alert('Warning! Please verify data, once it submitted it will not be changed.');

            } else {

                // apply requires attribute
                $('.remove-readonly').each(function() {

                    $(this).removeAttr('required');
                   
                }); //each ends

            } // if ends
        }); // on change ends

        // $('.transmission-form').submit(function(e) {

        //     // check for phase steps if not found alert zero
        //     var phaseSteps = $('#phase_steps').val();

        //     if($('select[name="status"]').val() == 'accepted' && phaseSteps == 0) {
                
        //         e.preventDefault();
        //         alert('No steps found for this visit.');
        //     } else {

        //        //continue submitting
        //         e.currentTarget.submit();
        //     }
        // });

}); // document ready

</script>

@endsection




