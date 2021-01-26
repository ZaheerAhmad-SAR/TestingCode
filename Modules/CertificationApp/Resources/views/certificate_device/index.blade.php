@extends ('layouts.home')

@section('title')
    <title> Certification Devices | {{ config('app.name', 'Laravel') }}</title>
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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Certification Devices</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certification Devices</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="form-group col-md-12 mt-3">        

                        <a href="{{ route('archived-device-transmission-listing')}}" class="btn btn-primary archive-device-transmission">Archived Transmissions</a>

                        @if (!$getTransmissions->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $getTransmissions->count().' out of '.$getTransmissions->total() }}
                        </span>
                        @endif

                    </div>
                    
                    <hr>

                    <form action="{{route('certification-device.index')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <div class="form-group col-md-3">
                                <label for="trans_id">Transmission#</label>
                                <input type="text" name="trans_id" id="trans_id" class="form-control filter-form-data" value="{{ request()->trans_id }}" placeholder="Transmission#">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="study">Study Name</label>
                                <input type="text" name="study" id="study" class="form-control filter-form-data" value="{{ request()->study }}" placeholder="Study Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="device_category">Device Category</label>
                                <input type="text" name="device_category" id="device_category" class="form-control filter-form-data" value="{{ request()->device_category }}" placeholder="Device Category">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="device_serial">Device Serial</label>
                                <input type="text" name="device_serial" id="device_serial" class="form-control filter-form-data" value="{{ request()->device_serial }}" placeholder="Device Serial">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="site">Site Name</label>
                                <input type="text" name="site" id="site" class="form-control filter-form-data" value="{{ request()->site }}" placeholder="Site Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="submitter">Submitter Name</label>
                                <input type="text" name="submitter_name" id="submitter_name" class="form-control filter-form-data" value="{{ request()->submitter_name }}" placeholder="Submitter Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Transmission Status</label>
                                <select id="status" name="status" class="form-control filter-form-data">
                                    <option value="">All Status</option>
                                    <option @if(request()->status == 'pending') selected @endif value="pending">Pending</option>
                                    <option @if(request()->status == 'accepted') selected @endif  value="accepted">Accepted</option>
                                    <option @if(request()->status == 'rejected') selected @endif  value="rejected">Rejected</option>
                                    <option  @if (request()->status == 'deficient') selected @endif value="deficient">Deficient</option>
                                    <option  @if (request()->status == 'duplicate') selected @endif value="duplicate">Duplicate</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2 mt-4">
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lng">Filter Record</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                   
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Submitter Name</th>
                                        <th>Certification</th>
                                        <th>Study</th>
                                        <th>Device Category</th>
                                        <th>Device Serial</th>
                                        <th>Site</th>
                                        <th>Certification Status</th>
                                        <th>Transmission#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                        <tr style="background: {{ $transmission->rowColor }}">
                                            <td> {{ $transmission->Request_MadeBy_FirstName.' '.$transmission->Request_MadeBy_LastName }} </td>
                                            <td> {{$transmission->Requested_certification}} </td>
                                            <td> {{$transmission->Study_Name}} </td>
                                            <td> {{$transmission->Device_Category}} </td>
                                            <td> {{$transmission->Device_Serial}}</td>
                                            <td> {{$transmission->Site_Name}} </td>

                                            <td>
                                                @if ($transmission->certificateStatus['status'] == 'provisional')
                                                
                                                @if(hasPermission(auth()->user(),'generate-device-certificate'))
                                                <a href="javascript:void()" id="generate-certification" data-id="" title="Provisional Certified" class="badge badge-warning" onClick="generateCertificate('{{$transmission->id}}', '{{ $transmission->certificateStatus['certificate_id'] }}', '{{ route('update-device-provisonal-certificate')}}', 'Provisional')">
                                                    Provisional Certified
                                                </a>
                                                @else
                                                    <a href="javascript:void()" title="No Permission" class="badge badge-warning">
                                                        Provisional Certified
                                                    </a>
                                                @endif

                                                @elseif($transmission->certificateStatus['status'] == 'full')

                                                 <a href="javascript:void()" id="generate-certification" data-id="" title="Full Certified" class="badge badge-success">
                                                    Full Certified
                                                </a>

                                                @elseif($transmission->certificateStatus['status'] == 'allowed')

                                                @if(hasPermission(auth()->user(),'generate-device-certificate'))
                                                    <a href="javascript:void()" id="generate-certification" data-id="" title="Generate Certificate" class="badge badge-dark" onClick="generateCertificate('{{$transmission->id}}', '{{ $transmission->certificateStatus['certificate_id'] }}', 'NO URL','Generate')">
                                                        Generate Certificate
                                                    </a>
                                                @else
                                                    <a href="javascript:void()" data-id="" title="No Permission" class="badge badge-dark">
                                                        Generate Certificate
                                                    </a>
                                                @endif

                                                @elseif($transmission->certificateStatus['status'] == 'not_allowed')

                                                <a href="javascript:void()" id="generate-certification" data-id="" title="Atleast one transmission should be accepted" class="badge badge-danger">
                                                    Generate Certificate
                                                </a>

                                                @endif
                                               
                                            </td>

                                            <td>

                                            @if ($transmission->linkedTransmission != null)

                                            @foreach($transmission->linkedTransmission as $linkedTransmission)

                                                <a href="{{ route('certification-device.edit', encrypt($linkedTransmission['id']))}}" id="view-transmission" class="" data-id="" title="Edit Certifaction Device Details" data-url="" style="color: #17a2b8 !important;">
                                                    <strong>
                                                    {{ $linkedTransmission['Transmission_Number'] }}
                                                    </strong>
                                                </a>

                                                &nbsp; | &nbsp;

                                                <span class="text-dark">
                                                    <strong style="color: #17a2b8 !important;"> {{$linkedTransmission['status']}} </strong>
                                                </span>

                                                &nbsp; | &nbsp;
                                                
                                                <span>
                                                    <a href="javascript:void(0)" class="" title="Archive Transmission" data-url="">
                                                        <i class="fas fa-archive" onClick="archiveTransmission('{{encrypt($linkedTransmission['id'])}}', '{{ route('archive-device-transmission', [encrypt($linkedTransmission['id']), 'yes']) }}')" data-url="" style="color: #17a2b8 !important;"></i>
                                                </span>

                                                <br>
                                                <br>
                                            @endforeach

                                            @else
                                                N/A
                                            @endif
                                                <!-- |
                                                <i class="fas fa-edit"> </i> -->

                                            </td>


                                            {{--
                                            <td>

                                                &nbsp; &nbsp;
                                                &nbsp; &nbsp;

                                                <div class="d-flex mt-md-0 ml-auto" style="margin-top: -15px !important;">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right" style="">
                                                        @if($transmission->status !== 'accepted')
                                                        <span class="dropdown-item">
                                                            <a href="javascript:void(0)" data-id="{{$transmission->Transmission_Number}}">
                                                                <i class="fas fa-question-circle" aria-hidden="true">
                                                                </i> Queries</a>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                 <!-- gear dropdown -->
                                            </td>
                                            --}}
                                            
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="7" style="text-align: center">No record found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{ $getTransmissions->appends(['trans_id' => \Request::get('trans_id'), 'study' => \Request::get('study'), 'device_category' => \Request::get('device_category'), 'device_serial' => \Request::get('device_serial'), 'site' => \Request::get('site'), 'submitter_name' => \Request::get('submitter_name'), 'status' => \Request::get('status')])->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Certification modal  -->
    <div class="modal fade" id="generate-certificate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Generate Certification</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="{{ route('approve-device-certificate') }}" method="POST" class="generate-certificate-form">
                @csrf
              <div class="modal-body">
                    <input type="hidden" name="hidden_transmission_id" class="hidden_transmission_id" value="">
                    <input type="hidden" name="pdf_key" class="pdf_key" id="pdf_key" value="">
                    <input type="hidden" name="hidden_device_certification_id" class="hidden_device_certification_id" value="">
                    
                    <div class="form-group col-md-12">
                        <label>Certifictaion Status<span class="field-required">*</span></label>
                        <select name="certification_status" id="certification_status" class="form-control" required="required">
                            <option value="">Select Status</option>
                            <!-- <option value="provisional">Provisionally Certified</option> -->
                            <option value="full" selected>Full</option>
                            <!-- <option value="suspended">Suspended</option>
                            <option value="expired">Expired</option>
                            <option value="audit">In Audit</option> -->
                        </select>
                    </div>

                    <div class="form-group col-md-12 suspend-certificate-div">
                        <label>Certificate Type<span class="field-required">*</span></label>
                        <select name="certificate_type" id="certificate_type" class="form-control data-required" required="required">
                            <option value="">Select Type</option>
                            <option value="original">Original</option>
                            <option value="grandfathered">Grandfathering</option>
                        </select>
                    </div>

                    <!-- -------------------------------- original One ------------------------------- -->
                    <div class="form-group col-md-12 original-div" style="display: none;">
                        <label><strong>Select Transmission:</strong></label><br>
                    </div>

                    <!-- ------------------------------------ grand father one 
                    <div class="form-group col-md-12 grandfather-div" style="display: none;">
                        <label>GrandFather Certificate ID<span class="field-required">*</span></label>
                        <textarea name="grandfather_id" id="grandfather_id" rows="3" class="form-control data-required"></textarea>
                    </div>

                    <--------------------------------------------------------------------------------- -->

                    <div class="form-group col-md-12 suspend-certificate-div">
                        <label>Certificate For<span class="field-required">*</span></label>
                        <select name="certificate_for" id="certificate_for" class="form-control data-required" required="required">
                            

                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Device ID<span class="field-required">*</span></label>
                        <input type="text" name="device_id" id="device_id" class="form-control data-required" required="required">
                        
                    </div>

                    <div class="form-group col-md-12">
                        <label class="edit_users">Email To<span class="field-required">*</span></label>
                        <Select class="form-control user_email" name="user_email" id="user_email" required>

                        </Select>
                    </div>

                     <div class="form-group col-md-12 suspend-certificate-div">
                        <label class="edit_users">CC Email</label>
                        <Select class="form-control cc_user_email data-required" name="cc_user_email[]" id="cc_user_email" multiple>

                        </Select>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="edit_users">BCC Email</label>
                        <Select class="form-control bcc_user_email" name="bcc_user_email[]" id="bcc_user_email" multiple="multiple">

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
                        <label>Comments<span class="field-required">*</span></label>
                        <textarea class="form-control summernote" name="comment" value="" rows="4"></textarea>
                        <span class="edit-error-field" style="display: none; color: red;">Please fill comment field.</span>
                    </div>

                    <div class="form-group col-md-12 suspend-certificate-div">
                        <label>Issue Date</label>
                        <input type="date" class="form-control data-required" id="issue_date" name="issue_date" value="" required>
                    </div>

                    <div class="form-group col-md-12 suspend-certificate-div"> 
                        <button type="submit" class="btn btn-success approve-pdf">View Certificate PDF</button>      
                    </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary generate-pdf" disabled>Generate Certificate</button>
              </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Modal ends -->


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

    // initialize summer note
    $('.summernote').summernote({
        height: 150,

    });

    $('#cc_user_email').select2();
    $('#bcc_user_email').select2();

    // // on status change
    // $('#certification_status').change(function() {
    //     // if this is the value show all div other vice hide the other divs
    //     if($(this).val() == 'provisional' || $(this).val() == 'full') {
            
    //         // show div
    //         $('.suspend-certificate-div').css('display', 'block');
    //         // and apply required
    //         $('.data-required').attr('required', true);
        
    //     } else {

    //         // show div
    //         $('.suspend-certificate-div').css('display', 'none');
    //         // and apply required
    //         $('.data-required').attr('required', false);
    //     }
    // });

    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        $('.filter-form').submit();
    });

     function generateCertificate(transmissionID, certificateID, route, type) {

        if(type == 'Provisional') {
            // chnage form action and assign certification ID
            $('.generate-certificate-form').attr('action', route);
            $('.hidden_device_certification_id').val(certificateID);
        }

        var transmission = '';
        var childModalities = '';

        // assign transmission ID
        $('.hidden_transmission_id').val(transmissionID);
        // assign file key
        $('.pdf_key').val(Math.random().toString(36).substr(2, 16));
        // enable approve pdf button
        $('.approve-pdf').attr('disabled', false);
        // disable generate button
        $('.generate-pdf').attr('disabled', true);
        // make form target blank
        $('.generate-certificate-form').attr('target', '_blank');
        // give default url
         $('.generate-certificate-form').attr("action", "{{ route('approve-device-certificate')}}");

        // by default values
        // $('#certification_status').val('');
        $('#certificate_type').val('');
        $('#issue_date').val('');

        // remove required from grand father
        $('.grandfather-div').css('display', 'none');
        $('#grandfather_id').attr('required', false);

        // remove transmission check boxes
        $('.original-div').css('display', 'none');
        $(".transmission-checkbox :checkbox").parent().remove();
        // remove certification for drop down
        $('#certificate_for').empty();
        // empty user email
        $('.user_email').empty();

        // refresh the select2
        $('#cc_user_email').empty();
        $('#bcc_user_email').empty();

        // hide error message
        $('.edit-error-field').css('display', 'none');
        
        // show modal
        $('#generate-certificate-modal').modal("show");

        // ajax call to bring data
        $.ajax({
            url: '{{ route("get-transmission-data") }}',
            type: 'GET',
            data: {
                'transmission_id': transmissionID,
                'type' : 'device',
            },
            success:function(data) {

                // ------------------------------------- transmission start----------------------//
                if (data.getTransmissions != null) {
                    // loop through transmission#
                    $.each(data.getTransmissions, function(index, value) {
                                    
                        transmission += '<label class="transmission-checkbox" style="display: block"><input type="checkbox"\ name="transmissions[]" \
                        value="'+value.Transmission_Number+'">'+value.Transmission_Number+'</label>';
                    
                    });

                    // append values
                    $('.original-div').append(transmission);

                } else {

                    // remove all check boxes
                    $(".transmission-checkbox :checkbox").parent().remove();
                }

                // ------------------------------------- transmission ends----------------------//

                if(data.getChildModalities != null) {

                    childModalities += '<option value="">Select Certificate</option>';
                    // loop through transmission#
                    $.each(data.getChildModalities, function(index, value) {

                        
                        childModalities += '<option value="'+value.id+'">'+value.modility_name+'</option>';
                    });

                    // append data
                    $('#certificate_for').append(childModalities);

                } else {

                    $('#certificate_for').empty();
                    $('#certificate_for').append('<option value="">Select Certificate</option>');
                }

                // ------------------------------------- modalities ends----------------------//

                if(data.submitterEmail != '') {

                    $('.user_email').append('<option value="'+data.submitterEmail+'">'+data.submitterEmail+'</option>');

                } else {

                    $('.user_email').empty();
                }

                // ------------------------------------- user email ends----------------------//

                if(data.ccEmails != null) {

                    $.each(data.ccEmails, function(index, value) {
                                    
                        $('#cc_user_email').append('<option value="'+value+'" selected>'+value+'</option>')
                    });

                } else {

                    $('#cc_user_email').empty();
                }
                // ------------------------------------- user cc email ends----------------------//

                if(data.bccEmails != null) {

                    $.each(data.bccEmails, function(index, value) {
                                
                        $('#bcc_user_email').append('<option value="'+value+'" selected>'+value+'</option>')
                    });

                } else {

                    $('#bcc_user_email').empty();
                }
                // ------------------------------------- user bcc email ends----------------------//
               

            } // success ends

        }); // ajax ends

    }

        // certification type change
    $('#certificate_type').change(function() {

        if($(this).val() == 'original') {

            // show original div
            $('.original-div').css('display', 'block');
            // hide grandfather div
            $('.grandfather-div').css('display', 'none');
            // remove required for this div
            $('#grandfather_id').attr('required', false);

        } else if ($(this).val() == 'grandfathered') {

            // hide original div
            $('.original-div').css('display', 'none');
            // show grandfather div
            $('.grandfather-div').css('display', 'block');
            // apply required for this div
            $('#grandfather_id').attr('required', true);

        } else {

            // hide original div
            $('.original-div').css('display', 'none');
            // hide grandfather div
            $('.grandfather-div').css('display', 'none');
            // remove required for this div
            $('#grandfather_id').attr('required', false);

        }

    });


    // form submit
    $('.generate-certificate-form').submit(function(e) {
        
        if($('.summernote').summernote('isEmpty')) {
            // cancel submit
            e.preventDefault(); 
            $('.edit-error-field').css('display', 'block'); 

        } else {

            e.currentTarget;
           // disable approve pdf button
            $('.approve-pdf').attr('disabled', true);
            // enable generate button
            $('.generate-pdf').attr('disabled', false);
        }

    });

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

    // to archive a transmission
    function archiveTransmission(transmissionID, transmissionRoute) {

        if (confirm('Do you really wants to move this transmission to archive?')) {

            window.location.href = transmissionRoute;
        } 
    }

    $('.generate-pdf').click(function(){

        // make form target blank
        $('.generate-certificate-form').removeAttr('target');
        // give default url
        $('.generate-certificate-form').attr("action", "{{ route('generate-device-certificate')}}");

        $('.generate-certificate-form').submit();

    });

</script>

@endsection




