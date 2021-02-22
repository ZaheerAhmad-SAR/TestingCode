@extends ('layouts.home')

@section('title')
    <title> Device Transmission Details | {{ config('app.name', 'Laravel') }}</title>
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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Device Transmission Details</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Device Transmission Details</li>
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
                        <form action="{{ route('certification-device.update', encrypt($findTransmission->id))}}" method="POST" class="transmission-form">
                            <input type="hidden" name="notification" id="notification" value="{{$findTransmission->notification}}">
                            <div class="row">
                                @csrf
                                @method('PUT')
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
                                        <option @if($study->study_code == $findTransmission->StudyI_ID) selected @endif value="{{ $study->study_code }}">{{$study->study_code}}</option>
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
                                    <label for="Name" class="control-label">Device Category</label>
                                </div>
                                  
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Device_Category }}" readonly="" name="Device_Category" id="Device_Category" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Manufacturer</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Device_manufacturer }}" readonly="" name="Device_manufacturer" id="Device_manufacturer" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row  ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Model<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->Device_Model }}</span>
                                    <select name="Device_Model" id="Device_Model" class="form-control required-data">
                                        <option value="">Select Device</option>
                                        <option value="add_new">Add New</option>
                                        @foreach($getDevices as $device)
                                        <option @if($device->device_model == $findTransmission->Device_Model) selected @endif value="{{ $device->id.'__/__'.$device->device_model }}">{{ $device->device_model }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Serial</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Device_Serial }}" readonly="" name="Device_Serial" id="Device_Serial" class="form-control remove-readonly" required="required">
                                </div>

                                 <!--//////////////// row  ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device Software Version</label>
                                </div>
                                  
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Device_Software_version }}" readonly="" name="Device_Software_version" id="Device_Software_version" class="form-control remove-readonly" required="required">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Device OIRRC ID</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Device_OIRRCID }}" readonly="" name="Device_OIRRCID" id="Device_OIRRCID" class="form-control remove-readonly" required="required">
                                </div>

                                <!--//////////////// row  ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Site ID<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <span class="span-text">{{ $findTransmission->Site_ID }}</span>
                                    <select name="Site_ID" id="Site_ID" class="form-control required-data">
                                        <option value="">Select Site</option>
                                        <option value="add_new">Add New</option>
                                        @foreach($getSites as $site)
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

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="PI_Name" readonly="" value="{{ $findTransmission->PI_Name }}" id="PI_Name" class="form-control remove-readonly" required="required">
                                </div>

                                <!-- --------------- row --------------------- -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">PI Email</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="PI_email" readonly="" value="{{ $findTransmission->PI_email }}" id="PI_email" class="form-control remove-readonly" required="required">
                                </div>

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
                                        @foreach($getModalities as $modality)
                                            @php
                                                $matchingAbbreviation = preg_match("~\b$modality->modility_abbreviation\b~", $findTransmission->Requested_certification);
                                            @endphp
                                        <option @if($modality->modility_name == $findTransmission->Requested_certification || $matchingAbbreviation) selected @endif value="{{$modality->id.'__/__'.$modality->modility_name}}">{{$modality->modility_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!--/////////////////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter First Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Request_MadeBy_FirstName" value="{{ $findTransmission->Request_MadeBy_FirstName }}" id="Request_MadeBy_FirstName" class="form-control" readonly="">
                                </div>

                                 <!--//////////////////////// row ///////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Last Name</label>
                                </div>

                                <div class="form-group col-sm-3">
                                    <input type="text" name="Request_MadeBy_LastName" value="{{ $findTransmission->Request_MadeBy_LastName }}" id="Request_MadeBy_LastName" class="form-control" readonly="">
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Submitter Email</label>
                                </div>
                              
                                <div class="form-group col-sm-3">
                                    <input type="text" value="{{ $findTransmission->Request_MadeBy_Email }}" name="Request_MadeBy_Email" id="Request_MadeBy_Email" class="form-control" readonly="">
                                </div>

                                <!--//////////////////////////// row //////////////////////////// -->

                                <div class="form-group col-sm-3">
                                    <label for="Name" class="control-label">Status<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-md-9">
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
                                    <label for="Name" class="control-label">Reason for change<span class="field-required">*</span></label>
                                </div>

                                <div class="form-group col-md-9">
                                    <textarea class="form-control required-data" required="required" name="reason_for_change" id="reason_for_change" rows="4">{{ $findTransmission->status}}</textarea>
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
                                            <option value="{{$findTransmission->Request_MadeBy_Email}}" selected>
                                                {{$findTransmission->Request_MadeBy_Email}}
                                            </option>

                                        </Select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="edit_users">CC Email</label>
                                        <Select class="form-control cc_email" name="cc_email[]" id="cc_email" multiple="multiple">

                                        </Select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="edit_users">BCC Email</label>
                                        <Select class="form-control bcc_email" name="bcc_email[]" id="bcc_email" multiple="multiple">

                                        </Select>
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

@endsection
@section('script')

<script src="{{ asset('public/dist/vendors/summernote/summernote-bs4.js') }}"></script>

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
   
   $('select[name="StudyI_ID"]').select2();
   $('select[name="Site_ID"]').select2();
   $('select[name="Device_Model"]').select2();
   $('select[name="Requested_certification"]').select2();

   // model select
   //$('select[name="photographer_user_email"]').select2();
   //$('select[name="template"]').select2();
    $('.cc_email').select2();
    $('.bcc_email').select2();

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
                        
                        // refresh the select2
                        $('#cc_email').empty();
                        $('#bcc_email').empty();

                        if(data.userEmails != null) {

                            $.each(data.userEmails, function(index, value) {
                                    
                                $('#cc_email').append('<option value="'+value+'" selected>'+value+'</option>')
                            });

                            // put notification list emails as cc
                            if ($('#notification').val() == 'Yes') {
                                
                                $('#cc_email').append('<option value="'+notificationList+'" selected>'+notificationList+'</option>');
                            }

                        } else {

                            $('#cc_email').val("").trigger("change");
                        }

                        
                        // check for bcc emails
                        if(data.userBCCEmails != null) {

                            $.each(data.userBCCEmails, function(index, value) {
                                    
                                $('#bcc_email').append('<option value="'+value+'" selected>'+value+'</option>')
                            });

                        } else {

                            $('#bcc_email').val("").trigger("change");

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

</script>

@endsection




