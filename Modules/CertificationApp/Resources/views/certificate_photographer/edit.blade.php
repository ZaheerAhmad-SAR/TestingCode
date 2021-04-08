@extends ('layouts.home')

@section('title')
    <title> Photographer Transmission Details | {{ config('app.name', 'Laravel') }}</title>
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

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid black 1px;
            outline: 0;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #485e9029 !important; 
            border-radius: 4px;
            cursor: text;
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
            background-color: #fff;
            opacity: 1 !important;
        }

        div#cc_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }


        div#bcc_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }

        .span-text {
            color: red;
        }

        .field-required {
            color: red;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('public/dist/vendors/summernote/summernote-bs4.css') }}">

    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- tag based input -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css" rel="stylesheet">

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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Photographer Transmission Details</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Photographer Transmission Details</li>
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
                        <form action="{{ route('certification-photographer.update', encrypt($findTransmission->id))}}" method="POST" class="transmission-form">
                            <input type="hidden" name="notification" id="notification" value="{{$findTransmission->notification}}">
                            <div class="row">
                                @csrf
                                @method('PUT')
                                <div class="col-sm-3">
                                    <strong>Transmission Type</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge badge-info">
                                    {{$findTransmission->Certification_Type}}
                                    </span>
                                </div>
                                
                                <div class="col-md-12">
                                    <p class="bg-primary text-center" style="color: #fff; margin: 10px; font-size: 20px;">
                                    Transmission Data
                                    </p>
                                </div>
                                <hr>
                                
                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmission Number</label>
                                </div>

                                <div class="form-group col-sm-3">

                                    <input type="text" name="Transmission_Number" readonly="" value="{{ $findTransmission->Transmission_Number }}" id="Transmission_Number" class="form-control" required="required">
                                </div>

                                

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Study_Name }}" readonly="" name="Study_Name" id="Study_Name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Study ID<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->StudyI_ID }}</span>
                                    <select name="StudyI_ID" id="StudyI_ID" class="form-control required-data" required>
                                        <option value="">Select Study</option>
                                        @foreach($systemStudies as $study)
                                        <option @if($study->study_code == $findTransmission->StudyI_ID) selected @endif value="{{ $study->study_code }}">{{$study->study_code.' - '.$study->study_short_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Sponsor</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->sponsor }}" readonly="" name="sponsor" id="sponsor" class="form-control" required="required">
                                </div>


                              <!--//////////////// row  ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site ID<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->Site_ID }}</span>
                                    <select name="Site_ID" id="Site_ID" class="form-control required-data">
                                        @php
                                            $getStudySites = ($transmissionStudy != null) ? $transmissionStudy->sites : [];
                                        @endphp
                                        <option value="">Select Site</option>
                                        @foreach($getStudySites as $site)
                                        <option @if($site->site_code == $findTransmission->Site_ID) selected @endif value="{{$site->id.'__/__'.$site->site_code}}">{{$site->site_code.' - '.$site->site_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Name</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_Name }}" readonly="" name="Site_Name" id="Site_Name" class="form-control" required="required">
                                </div>

                              <!--//////////////// row  ///////////////////////// -->


                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site St Address</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_st_address }}" readonly="" name="Site_st_address" id="Site_st_address" class="form-control" required="required">
                                </div>


                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site City</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Site_city" readonly="" value="{{ $findTransmission->Site_city }}" id="Site_city" class="form-control" required="required">
                                </div>

                                <!--//////////////// row  ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site State</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_state }}" readonly="" name="Site_state" id="Site_state" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Zip</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Site_Zip" readonly="" value="{{ $findTransmission->Site_Zip }}" id="Site_Zip" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////////// row ////////////////////////////// -->

                                <div class=" form-group col-sm-3">
                                    <label for="Name" class="control-label">Site Country</label>
                                </div>
                              
                                <div class=" form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Site_country }}" readonly="" name="Site_country" id="Site_country" class="form-control remove-readonly" required="required">
                                </div>

                                <!-- <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="PI_Name" readonly="" value="{{ $findTransmission->PI_Name }}" id="PI_Name" class="form-control remove-readonly" required="required">
                                </div> -->

                                <!-- --------------- row --------------------- -->

                                <!-- <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Email</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="PI_email" readonly="" value="{{ $findTransmission->PI_email }}" id="PI_email" class="form-control remove-readonly" required="required">
                                </div> -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Notification Email</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="notification_list" readonly="" value="{{ $findTransmission->notification_list }}" id="notification_list" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row ///////////////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Requested Certification<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-sm-3">

                                    <span class="span-text">{{ $findTransmission->Requested_certification }}</span>
                                    <select name="Requested_certification" id="Requested_certification" class="form-control required-data" required>
                                        <option value="">Select Modality</option>
                                        @foreach($getStudyModalities as $modality)
                                            @php
                                                $matchingAbbreviation = preg_match("~\b$modality->modility_abbreviation\b~", $findTransmission->Requested_certification);
                                            @endphp
                                        <option @if($modality->modility_name == $findTransmission->Requested_certification || $matchingAbbreviation) selected @endif value="{{$modality->id.'__/__'.$modality->modility_name}}">{{$modality->modility_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                               <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Model<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-sm-2">
                                    <span class="span-text">{{ $findTransmission->Device_Model }}</span>
                                    <select name="Device_Model" id="Device_Model" class="form-control required-data">
                                        @php
                                            $getStudyDevices = ($transmissionStudy != null) ? $transmissionStudy->devices : [];
                                        @endphp
                                        <option value="">Select Device</option>
                                        @foreach($getStudyDevices as $device)
                                        <option @if($device->device_model == $findTransmission->Device_Model) selected @endif value="{{ $device->id.'__/__'.$device->device_model }}">{{ $device->device_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-1">
                                <button class="btn btn-primary" style="margin-top: 10px" onclick="transmissionDevice('{{$findTransmission->Device_Model}}')">Add Device</button>
                                </div>

                                <!--/////////////////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Salute</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Salute" readonly="" value="{{ $findTransmission->Salute }}" id="Salute" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer First Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Photographer_First_Name" value="{{ $findTransmission->Photographer_First_Name }}" id="Photographer_First_Name" class="form-control" readonly="">
                                </div>

                                 <!--//////////////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Last Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Photographer_Last_Name" value="{{ $findTransmission->Photographer_Last_Name }}" id="Photographer_Last_Name" class="form-control" readonly="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Email</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Photographer_email }}" name="Photographer_email" id="Photographer_email" class="form-control" readonly="">
                                </div>

                                <!--//////////////////////////// row //////////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer Phone</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Photographer_phone" value="{{ $findTransmission->Photographer_phone }}" id="Photographer_phone" class="form-control" readonly="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Photographer OIRRC ID</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Photographer_OIRRCID" readonly="" value="{{ $findTransmission->Photographer_OIRRCID }}" id="Photographer_OIRRCID" class="form-control" required="required">
                                </div>

                                @if($findTransmission->Certification_Type == 'Certificate for grandfathering')
                                 <!--//////////////////////// row ///////////////////////// -->

                                 <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Certification Type</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Certification_Type" value="{{ $findTransmission->Certification_Type }}" id="Certification_Type" class="form-control" readonly="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Previous Status</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->previous_certification_status }}" name="previous_certification_status" id="previous_certification_status" class="form-control" readonly="">
                                </div>

                                <!--//////////////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">GF Modality</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="gfModality" value="{{ $findTransmission->gfModality }}" id="gfModality" class="form-control" readonly="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">GF Certifying Study</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->gfCertifying_Study }}" name="gfCertifying_Study" id="gfCertifying_Study" class="form-control" readonly="">
                                </div>

                                <!--//////////////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">GF Certifying Center</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="gfCertifying_center" value="{{ $findTransmission->gfCertifying_center }}" id="gfCertifying_center" class="form-control" readonly="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">GF Certificate Date</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->gfCertificate_date }}" name="gfCertificate_date" id="gfCertificate_date" class="form-control" readonly="">
                                </div>

                                <!--//////////////////////////// row //////////////////////////// -->
                                @endif

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Transmission Comments</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Comments }}" name="transmission_comments" id="transmission_comments" class="form-control" readonly="">
                                </div>

                                 <div class="form-group col-sm-6">
                                   
                                </div>

                                <div class="col-md-12">
                                    <p class="bg-primary text-center" style="color: #fff; margin: 10px; font-size: 20px;">
                                    OIRRC Data
                                    </p>
                                </div>
                                <hr>
      
                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Status<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-md-3">
                                    <select name="status" id="status" class="form-control required-data" required>
                                        <option value="">Select Status</option>
                                        <option @if ($findTransmission->status == 'pending') selected @endif value="pending">Pending</option>
                                        <option  @if ($findTransmission->status == 'accepted') selected @endif value="accepted">Accepted</option>
                                        <option  @if ($findTransmission->status == 'rejected') selected @endif value="rejected">Rejected</option>
                                        <option  @if ($findTransmission->status == 'deficient') selected @endif value="deficient">Deficient</option>
                                        <option  @if ($findTransmission->status == 'duplicate') selected @endif value="duplicate">Duplicate</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Date of Capture<span class="field-required">*</span></label>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <input type="date" class="form-control required-data" id="date_of_capture" name="date_of_capture" value="{{$findTransmission->date_of_capture != null ? date('Y-m-d', strtotime($findTransmission->date_of_capture)) : ''}}" required="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Oirrc Comment</label>
                                </div>

                                <div class="form-group col-md-9">
                                    <textarea class="form-control" name="oirrc_comment" id="oirrc_comment" rows="4">{{ $findTransmission->oirrc_comment}}</textarea>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Reason for change<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-md-9">
                                    <textarea class="form-control required-data" required="required" name="reason_for_change" id="reason_for_change" rows="4">{{ $findTransmission->status}}</textarea>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Pathology<span class="field-required">*</span></label>
                                </div>
                             
                                 <div class="form-group col-md-9">
                                    <label>Yes</label>
                                    <input type="radio" class="pathology" name="pathology" value="yes" @if($findTransmission->pathology == 'yes') checked @endif>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>No</label>
                                    <input type="radio" class="pathology" name="pathology" value="no" @if($findTransmission->pathology == 'no') checked @endif>
                                </div>


                            <!-- ///////////////////////////// row ///////////////////// -->

                                <div class="col-md-10"></div>
                                <div class="col-md-2 edit-section" style="padding-top: 15px;">
                                    <!-- <a href="javascript:void(0)" class="btn btn-success edit-transmission">
                                        Edit
                                    </a> -->

                                    <button class="btn btn-success update-transmission"\
                                      type="button" name="submit">Update
                                    </button>

                                    <a href="{{route('certification-photographer.index')}}" class="btn btn-danger">
                                        Close
                                    </a>
                                </div>

                            </div>
                            <!-- row ends -->

                        <!-- transmission status modal  -->
                        <div class="modal fade" id="transmission-status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content" style="border-color: #1e3d73;">
                              <div class="modal-header bg-primary" style="color: #fff">
                                <h5 class="modal-title" id="exampleModalLabel">Update Transmission</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                                
                                  <div class="modal-body">

                                    <div class="form-group col-md-12">
                                        <label class="edit_users">Email To</label>
                                        <Select class="form-control photographer_user_email" name="photographer_user_email" id="photographer_user_email" required>
                                            <option value="{{$findTransmission->Photographer_email}}" selected>
                                                {{$findTransmission->Photographer_email}}
                                            </option>

                                        </Select>
                                    </div>

                                   <div class="form-group col-md-12">
                                        <label class="edit_users">CC Email</label>
                                        <input type="text" class="form-control cc_email" name="cc_email" id="cc_email" value="">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="edit_users">BCC Email</label>
                                        <input type="text" class="form-control bcc_email" name="bcc_email" id="bcc_email" value="">
                                    </div>

                                    <div class="form-group col-md-12">
                                            
                                        <label for="inputState">Templates</label>
                                        <select id="template" name="template" class="form-control">
                                            <option value="">Select Template</option>
                                             @foreach($getTemplates as $template)
                                             <option value="{{ $template->template_id }}">{{ $template->template_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12 comment-div">
                                        <label>Comments</label>
                                        <textarea class="form-control summernote" name="comment" value="" rows="4"></textarea>
                                        <span class="edit-error-field" style="display: none; color: red;">Please fill comment field.</span>
                                    </div>

                                  </div>

                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Transmission</button>
                                  </div>
                               
                            </div>
                          </div>
                        </div>
                        <!-- Modal ends -->
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
                                                {{ $transmissionUpdates->reason_for_change }}
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

 <!-- modal code  -->
<div class="modal fade" id="device-crud-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary" style="color: #fff">
                    <h4 class="modal-title" id="deviceCrudModal">Add New Device</h4>
                </div>
                <form id="deviceForm" name="deviceForm" class="form-horizontal">
                    <div class="modal-body">
                        <input type="hidden" name="device_id" id="device_id">
                            <nav>
                                <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Modalities" role="tab" aria-controls="nav-profile" aria-selected="false">Modalities</a>
                                </div>
                            </nav>
                    <div class="alert alert-danger device-error-message" style="display:none; margin-top:5px;"></div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="device_name" class="col-sm-3">Name</label>
                                <div class="{!! ($errors->has('device_name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_name" name="device_name"
                                           value="{{old('device_name')}}" required>
                                    @error('device_name')
                                    <span class="text-danger small">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="device_model" class="col-sm-3">Device Model</label>
                                <div class="{!! ($errors->has('device_model')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_model" name="device_model" value="{{old('device_model')}}"  required> @error('email')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="device_manufacturer" class="col-sm-3">Manufacturer</label>
                                <div class="{!! ($errors->has('device_manufacturer')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_manufacturer" name="device_manufacturer" value="{{old('device_manufacturer')}}" required> 
                                    @error('device_manufacturer')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="device_manufacturer" class="col-sm-3"></label>
                                    <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <select class="searchable form-control" id="select-modality" multiple="multiple" name="modalities[]" required>
                                            @foreach($getModalities as $modality)
                                                <option value="{{$modality->id}}">{{$modality->modility_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('modalities')
                                    <span class="text-danger small">
                                    {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
<!-- modal code  -->

@endsection
@section('script')

<script src="{{ asset('public/dist/vendors/summernote/summernote-bs4.js') }}"></script>

<!-- tag based input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>

<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

<script type="text/javascript">

    // initialize user email dropdown
    $('.user_email').select2({
        dropdownPosition: 'below'
      
    });

     // initialize summer note
    $('.summernote').summernote({
        height: 150,

    });

/********************* add new device ***********************/
    function transmissionDevice(deviceModel) {
        // hide error message
        $('.device-error-message').css('display', 'none');
        // clear all inputs
        $('#deviceForm')[0].reset();
        // assign value to modal
        $('input[name="device_model"]').val(deviceModel);
        // assign value to form
        $('#device-crud-modal').modal('show');
    }

/******************** Device Form ***************************/
    $('#deviceForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: "{{ route('devices.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success:function(data) {
                if(data.error) {
                    // append/show error message
                    $('.device-error-message').text('Device model already exists.');
                    $('.device-error-message').css('display', 'block');
                } else if (data.success) {
                    // append value to device drop down
                    $('select[name="Device_Model"]').append('<option value="'+data.device.id+'__/__'+data.device.device_model+'">'+data.device.device_name+'</option>').trigger('change');
                    // close modal
                    $('#device-crud-modal').modal('hide');
                }
                
            } // success ends

        }); // ajax ends
    });
/****************************** device submit ends **************************************************/
   
   $('select[name="StudyI_ID"]').select2();
   $('select[name="Site_ID"]').select2();
   $('select[name="Device_Model"]').select2();
   $('select[name="Requested_certification"]').select2();

    // get sites for study on change
    $('#StudyI_ID').change(function() {
       if($(this).val() != '') {
            $.ajax({
                url: '{{ route("get-transmission-study-sites") }}',
                type: 'GET',
                data: {
                    'study_code': $(this).val(),
                },
                success:function(data) {
                    // empty site dropdown
                    $('select[name="Site_ID"]').empty();
                    if(data.study_sites != '') {
                        // default value
                        $('select[name="Site_ID"]').append('<option value="" selected>Select Site</option>');
                        // loop through the sites
                        $.each(data.study_sites, function(index, value) {
                            $('select[name="Site_ID"]').append('<option value="'+value.id+'__/__'+value.site_code+'">'+value.site_code+' - '+value.site_name+'</option>')
                        });
                    } else {
                        // no site found option
                        $('select[name="Site_ID"]').append('<option value="" selected>No Site Found</option>');
                    } // data check ends

                    /************************* Modalities **************************************/
                    // empty site dropdown
                    $('select[name="Requested_certification"]').empty();
                    if(data.study_modalities != '') {
                        // default value
                        $('select[name="Requested_certification"]').append('<option value="" selected>Select Modality</option>');
                        // loop through the sites
                        $.each(data.study_modalities, function(index, value) {
                            $('select[name="Requested_certification"]').append('<option value="'+value.id+'__/__'+value.modility_name+'">'+value.modility_name+'</option>')
                        });
                    } else {
                        // no site found option
                        $('select[name="Requested_certification"]').append('<option value="" selected>No Modality Found</option>');
                    } // data check ends

                    /************************* Devices ****************************************/
                    // empty site dropdown
                    $('select[name="Device_Model"]').empty();
                    if(data.study_devices != '') {
                        // default value
                        $('select[name="Device_Model"]').append('<option value="" selected>Select Device Model</option>');
                        // loop through the sites
                        $.each(data.study_devices, function(index, value) {
                            $('select[name="Device_Model"]').append('<option value="'+value.id+'__/__'+value.device_model+'">'+value.device_name+'</option>')
                        });
                    } else {
                        // no site found option
                        $('select[name="Device_Model"]').append('<option value="" selected>No Device Found</option>');
                    } // data check ends
                } // success ends
            }); // ajax ends
       } // if ends
    });
    /******************************** Modality Change function *****************************************/
    $('select[name="Requested_certification"]').change(function() {
        if($(this).val() != '') {
            $.ajax({
                url: '{{ route("get-modality-devices") }}',
                type: 'GET',
                data: {
                    'study_code': $('#StudyI_ID').val(),
                    'modality_id': $(this).val(),
                },
                success:function(data) {
                     // empty site dropdown
                    $('select[name="Device_Model"]').empty();
                    if(data.study_devices != '') {
                        // default value
                        $('select[name="Device_Model"]').append('<option value="" selected>Select Device Model</option>');
                        // loop through the sites
                        $.each(data.study_devices, function(index, value) {
                            $('select[name="Device_Model"]').append('<option value="'+value.id+'__/__'+value.device_model+'">'+value.device_name+'</option>')
                        });
                    } else {
                        // no site found option
                        $('select[name="Device_Model"]').append('<option value="" selected>No Device Found</option>');
                    } // data check ends
                } // success function ends
            });
        } // null check ends
    });

    // initiallize tags
    $('#cc_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    $('#bcc_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });
   

    $('document').ready(function () {

       // form button click
       $('.update-transmission').click(function(e) {
            e.preventDefault();
            var count = 0;
            $(".required-data").each(function() {

                if ($(this).val() == '') {

                    alert('Please fill required fields.');
                    count++;
                    return false;
                }

            }); // look for required values

            if(count == 0) {

                // hide error message
                $('.edit-error-field').css('display', 'none');
                // reset template dropdown
                $('select[name="template"]').val("").trigger("change");
                // keep text editor empty
                $('.summernote').summernote('code', '');

                // get notification email as CC for this photographer
                var notificationList = $('#notification_list').val();

                $.ajax({
                    url: '{{ route("get-study-setup-emails") }}',
                    type: 'GET',
                    data: {
                        'study_code': $('#StudyI_ID').val(),
                    },
                    success:function(data) {
                        
                        // remove old cc tag
                        removeCCTag($('#cc_email'));
                        // remove old bcc tag
                        removeBCCTag($('#bcc_email'));

                        if(data.userEmails != null) {

                            //put notification list emails as cc
                            if ($('#notification').val() == 'Yes') {
                                
                                data.userEmails.push(notificationList);
                            }
                           
                            $.each(data.userEmails, function(index, value) {

                                //append new value
                                $('#cc_email').addTag(value);
                            });
                            
                        }

                        // check for bcc emails
                        if(data.userBCCEmails != null) {

                            $.each(data.userBCCEmails, function(index, value) {

                                // append new tag
                                $('#bcc_email').addTag(value);
                            });

                        }
                        
                    } // success ends

                }); // ajax ends

                // show modal
                $('#transmission-status-modal').modal('show');
            }

       }); // click button

        // form submit
        $('.transmission-form').submit(function(e) {
            
            if($('.summernote').summernote('isEmpty')) {
                // cancel submit
                e.preventDefault(); 
                $('.edit-error-field').css('display', 'block'); 

            } else {

                e.currentTarget;
            }
        });

    }); // document ready

    // get template data on change
        $('#template').change(function() {

            $.ajax({
                url: '{{ route("get-template-data") }}',
                type: 'GET',
                data: {
                    'template_id': $(this).val(),
                },
                success:function(data) {

                    if(data.getTemplate != null) {

                        // assign body
                        $('.summernote').summernote('code', data.getTemplate.template_body);

                    } else {

                        // assign body
                        $('.summernote').summernote('code', '');
                    }
                    
                } // success ends

            }); // ajax ends

        });  // change function ends

    // update reason for change value based on status
    $('#status').change(function(){

        $('#reason_for_change').val($(this).val());
    });

    /********************************** multi select *************************************************/
    $('#select-modality').multiSelect({
        selectableHeader: "<label for=''>All Modalities</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
        selectionHeader: "<label for=''>Assigned Modalities</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(){
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    function removeCCTag(element) {
        var ccTags = element.val().split(',');

        // remove tags
        $.each(ccTags, function(index, value) {
            //append new value
            element.removeTag(value);
        });
    }

    function removeBCCTag(element) {
        var bccTags = element.val().split(',');

        // remove tags
        $.each(bccTags, function(index, value) {
            //append new value
            element.removeTag(value);
        });

    }

</script>

@endsection




