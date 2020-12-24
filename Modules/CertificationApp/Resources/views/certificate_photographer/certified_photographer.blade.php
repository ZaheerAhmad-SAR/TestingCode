@extends ('layouts.home')

@section('title')
    <title> Certified Photographer | {{ config('app.name', 'Laravel') }}</title>
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
     <!-- sweet alerts -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/sweetalert/sweetalert.css') }}"/>
@endsection

@section('content')


    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Certified Photographer</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certified Photographer</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                   {{--
                    <form action="{{route('certified-photographer')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">

                          

                            <div class="form-group col-md-2 mt-4">
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lng">Filter Record</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                   --}}
                   <hr>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Certificate ID</th>
                                        <th>Photographer</th>
                                        <th>Study</th>
                                        <th>Site Name</th>
                                        <th>Image MOdality</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getCertifiedPhotographer->isEmpty())
                                    @foreach($getCertifiedPhotographer as $certifiedPhotographer)

                                    @php
                                        $diffInDays = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($certifiedPhotographer->expiry_date), false);

                                    @endphp

                                    @if($diffInDays <= 0)
                                    <tr style="background: rgba(255, 0, 0, 0.5)">
                                    @elseif($diffInDays > 0 && $diffInDays <= 30)
                                    <tr style="background: rgba(241, 245, 15, 0.5)">
                                    @else
                                    <tr>
                                    @endif
                                        <td>{{ $certifiedPhotographer->certificate_id}}</td>
                                        <td>{{ $certifiedPhotographer->first_name.' '.$certifiedPhotographer->last_name}}</td>
                                        <td>{{ $certifiedPhotographer->study_name}}</td>
                                        <td>{{ $certifiedPhotographer->site_name}}</td>
                                        <td>{{ $certifiedPhotographer->certificate_for}}</td>
                                        <td>
                                            <span class="badge badge-success">
                                            {{ $certifiedPhotographer->certificate_type}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                            {{ $certifiedPhotographer->certificate_status}}
                                            </span>
                                        </td>

                                        <td>

                                            
                                            @if($certifiedPhotographer->certificate_status != 'provisional' && $certifiedPhotographer->certificate_type != 'grandfathered')

                                            <a href="javascript:void(0)" onClick="generateGrandfatherCertificate('{{$certifiedPhotographer->certificate_id}}')">
                                                <i class="fas fa-pen" title="Generate Grandfather Certificate" style="color: #17a2b8 !important;">
                                                
                                                </i>
                                            </a>

                                            &nbsp; | 
                                            @endif

                                            &nbsp;
                                            <a href="javascript:void(0)">
                                                <i class="fas fa-eye" title="View Details" style="color: #17a2b8 !important;" onClick="showDetails('{{$certifiedPhotographer->first_name}}', '{{$certifiedPhotographer->last_name}}', '{{$certifiedPhotographer->photographer_email}}', '{{$certifiedPhotographer->phone}}', '{{$certifiedPhotographer->site_name}}', '{{$certifiedPhotographer->site_code}}', '{{$certifiedPhotographer->study_name}}', '{{$certifiedPhotographer->certificate_for}}', '{{date('d-M-Y h:m:i a', strtotime($certifiedPhotographer->issue_date))}}', '{{date('d-M-Y h:m:i a', strtotime($certifiedPhotographer->expiry_date))}}', '{{$certifiedPhotographer->certification_officer_name}}')">
                                                
                                                </i>
                                            </a>

                                            &nbsp; | &nbsp;

                                            <a href="{{ route('photographer-certificate-pdf', $certifiedPhotographer->certificate_file_name)}}" target="_blank">
                                                <i class="fas fa-file" title="View Certificate" style="color: #17a2b8 !important;">
                                                
                                                </i>
                                            </a>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="8" style="text-align: center">No record found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{ $getCertifiedPhotographer->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA -->
    </div>

    <!-- Certification Details modal  -->
    <div class="modal fade" id="show-certificate-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Certificate Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="#" method="POST" class="show-certificate-details-form">
                
              <div class="modal-body">

                <div style="background-color:#00A8B3;">
                    <h4 style="color: white; text-align: center; padding-top: 5px; padding-bottom: 5px;">Photographer Details</h4><h4></h4>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Photographer First Name</label>
                        <input type="text" class="form-control" name="Photographer_first_name" id="Photographer_first_name" value="" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Photographer Last Name</label>
                        <input type="text" class="form-control" name="Photographer_last_name" id="Photographer_last_name" value="" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Photographer Phone</label>
                        <input type="text" class="form-control" name="Photographer_phone" id="Photographer_phone" value="" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Photographer Email</label>
                        <input type="text" class="form-control" name="Photographer_email" id="Photographer_email" value="" disabled>
                    </div>

                </div>
                <!-- row ends -->

                <div style="background-color:#00A8B3;">
                    <h4 style="color: white; text-align: center; padding-top: 5px; padding-bottom: 5px;">Site Details</h4><h4></h4>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Site Name</label>
                        <input type="text" class="form-control" name="site_name" id="site_name" value="" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Site Code</label>
                        <input type="text" class="form-control" name="site_code" id="site_code" value="" disabled>
                    </div>
                    
                </div>
                <!-- row ends -->

                <div style="background-color:#00A8B3;">
                    <h4 style="color: white; text-align: center; padding-top: 5px; padding-bottom: 5px;">Certification Details</h4><h4></h4>
                </div>

                <div class="row">
                    <table class="table table-bordered" id="laravel_crud">
                        <thead class="table-secondary">
                            <tr>
                                <th>Study Name</th>
                                <th>Certification</th>
                                <th>Issue Date</th>
                                <th>Expiry Date</th>
                                <th>Issued By</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="study_name"></td>
                                <td class="certification_for"></td>
                                <td class="issue_date"></td>
                                <td class="expiry_date"></td>
                                <td class="issued_by"></td>
                            </tr>    
                        </tbody>
                    </table>
                    
                </div>
                <!-- row ends -->
                    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Modal ends -->

    <div class="modal fade" id="certificate-grandfather-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Generate Grandfathering Certificate</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="{{ route('generate-photographer-grandfather-certificate') }}" method="POST" class="certificate-grandfather-form">
                @csrf
            <input type="hidden" name="certificate_id" id="certificate_id" value="">

              <div class="modal-body">

                <div class="form-group col-md-12">
                    <label>Study</label>
                    <select name="study" id="study" class="form-control" required="">
                        <option value="">Select Study</option>
                        @foreach($getStudies as $study)
                            <option value="{{ $study->id }}">{{ $study->study_short_name }}</option>
                        @endforeach
                    </select>
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
                    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Generate Certificate</button>

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

<!-- sweet alert -->
<script src="{{ asset('public/dist/vendors/sweetalert/sweetalert.min.js') }}"></script>   

<script type="text/javascript">

    $('#study').select2();

    // initialize summer note
    $('.summernote').summernote({
        height: 150,

    });

    function showDetails(firstName, lastName, email, phone, siteName, siteCode, studyName, certificationFor, issueDate, expiryDate, issuedBy) {
        
        // show modal
        $('#show-certificate-details-modal').modal('show');
        // assign values
        $('#Photographer_first_name').val(firstName);
        $('#Photographer_last_name').val(lastName);
        $('#Photographer_email').val(email);
        $('#Photographer_phone').val(phone);
        $('#site_name').val(siteName);
        $('#site_code').val(siteCode);
        $('.study_name').text(studyName);
        $('.certification_for').text(certificationFor);
        $('.issue_date').text(issueDate);
        $('.expiry_date').text(expiryDate);
        $('.issued_by').text(issuedBy);

    } // details function ends

    // grandfathering function
    function generateGrandfatherCertificate(certificateID) {

        // assign Certificate ID
        $('#certificate_id').val(certificateID);
        // show modal
        $('#certificate-grandfather-modal').modal('show');
    }

    $('.certificate-grandfather-form').submit(function(e) {

        e.preventDefault();

        if($('.summernote').summernote('isEmpty')) {
            // cancel submit
            e.preventDefault(); 
            $('.edit-error-field').css('display', 'block'); 

        } else {

            $.ajax({
            url: '{{ route("check-grandfather-certificate") }}',
            type: 'GET',
            data: {
                'study_id': $('#study').val(),
                'certificate_id': $('#certificate_id').val(),
                'type': 'photographer',
            },
                success:function(data) {

                   if(data['success'] == 'true') {
                    // submit form
                    e.currentTarget.submit();

                   } else {

                        swal({
                          title: "Certificate Exists",
                          text: "Grandfather Certificate already exists.Do you want to Generate another one?",
                          type: "warning",
                          showCancelButton: true,
                          confirmButtonClass: 'btn-danger',
                          confirmButtonText: 'Yes, please proceed!',
                          cancelButtonText: "No, please cancel!",
                          closeOnConfirm: true,
                          closeOnCancel: true
                        },
                        function(isConfirm) {
                            if (isConfirm) {

                                // submit the form
                                e.currentTarget.submit();

                            } else {
                                // close the model
                                $('#certificate-grandfather-modal').modal('hide');
                            }
                        });
                    
                   }
                    
                } // success ends

            }); // ajax ends

        } // summer note else ends
 
    }); // submit form function ends

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

</script>

@endsection




