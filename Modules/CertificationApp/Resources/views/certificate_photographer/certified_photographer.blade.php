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

       /*Generate Certificate*/
        div#cc_user_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }

        div#bcc_user_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }

        /*Status modal*/
        div#status_cc_user_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }

        div#status_bcc_user_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }

        /*Expiry Date Modal*/
        div#date_cc_user_email_tagsinput {
            width: 100% !important;
            min-height: 42px !important;
            /*height: 30px !important;*/
            overflow: hidden !important;
        }

        div#date_bcc_user_email_tagsinput {
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

    <!-- tag based input -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css" rel="stylesheet">

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

                    <form action="{{route('certified-photographer')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <div class="form-group col-md-3">
                                <label for="certify_id">Certificate ID</label>
                                <input type="text" name="certify_id" id="certify_id" class="form-control filter-form-data" value="{{ request()->certify_id }}" placeholder="Certification ID">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="study">Study Name</label>
                                <input type="text" name="study_name" id="study_name" class="form-control filter-form-data" value="{{ request()->study_name }}" placeholder="Study Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="photographer_name">Photographer Name</label>
                                <input type="text" name="photographer_name" id="photographer_name" class="form-control filter-form-data" value="{{ request()->photographer_name }}" placeholder="Photographer Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="site">Site Name</label>
                                <input type="text" name="site_name" id="site_name" class="form-control filter-form-data" value="{{ request()->site_name }}" placeholder="Site Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Modality</label>
                                <select name="modility_id" id="modility_id" class="form-control filter-form-data">
                                    <option value="">Select Modality</option>
                                    @foreach($getParentModality as $parentModality)
                                    <option value="{{ $parentModality->id }}" @if(request()->modility_id == $parentModality->id) selected @endif >{{ $parentModality->modility_name }}</option>
                                    @endforeach
                                    {{--
                                    @foreach($getChildModality as $childModality)
                                    <option value="{{ $childModality->id }}" @if(request()->modility_id == $childModality->id) selected @endif >{{ $childModality->modility_name }}</option>
                                    @endforeach
                                    --}}
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Certificate Status</label>
                                <select id="certificate_status" name="certificate_status" class="form-control filter-form-data">
                                    <option value="">All Status</option>
                                    <option @if(request()->certificate_status == 'full') selected @endif value="full">Full</option>
                                     <option @if(request()->certificate_status == 'provisional') selected @endif value="provisional">Provisional</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Certificate Type</label>
                                <select id="certificate_type" name="certificate_type" class="form-control filter-form-data">
                                    <option value="">All Type</option>
                                    <option @if(request()->certificate_type == 'original') selected @endif value="original">Original</option>
                                     <option @if(request()->certificate_type == 'grandfathered') selected @endif value="grandfathered">Grandfather</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Validity </label>
                                <select id="validity" name="validity" class="form-control filter-form-data">
                                    <option value="">Both</option>
                                    <option @if(request()->validity == 'no') selected @endif value="no">No</option>
                                     <option @if(request()->validity == 'yes') selected @endif value="yes">Yes</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="dt">Issue Date</label>
                                <input type="text" name="issue_date" id="issue_date" class="form-control issue_date filter-form-data" value="{{ request()->issue_date }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="dt">Expiry Date</label>
                                <input type="text" name="expiry_date" id="expiry_date" class="form-control expiry_date filter-form-data" value="{{ request()->expiry_date }}">
                            </div>

                            <div class="form-group col-md-3 mt-4">
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lng">Filter Record</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>

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
                                        <th>Image Modality</th>
                                        <th>Type</th>
                                        <th>Issue Date</th>
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
                                        <td>{{ date('d-M-Y', strtotime($certifiedPhotographer->issue_date))}}</td>
                                        <td>
                                            <span class="badge badge-primary">
                                            {{ $certifiedPhotographer->certificate_status}}
                                            </span>
                                        </td>

                                        <td>

                                            
                                            @if($certifiedPhotographer->certificate_status == 'full' && $certifiedPhotographer->certificate_type != 'grandfathered')

                                            @if(hasPermission(auth()->user(),'generate-photographer-grandfather-certificate'))
                                                <a href="javascript:void(0)" onClick="generateGrandfatherCertificate('{{$certifiedPhotographer->certificate_id}}', '{{ $certifiedPhotographer->photographer_email}}', '{{ $certifiedPhotographer->cc_emails }}', '{{ $certifiedPhotographer->bcc_emails }}')">
                                                    <i class="fas fa-pen" title="Generate Grandfather Certificate" style="color: #17a2b8 !important;">
                                                    
                                                    </i>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)">
                                                    <i class="fas fa-pen" title="No Permission" style="color: #17a2b8 !important;">
                                                    
                                                    </i>
                                                </a>
                                            @endif

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

                                            &nbsp; | &nbsp;

                                            <a href="javascript:void(0)" onClick="changeCertificateStatus('{{$certifiedPhotographer->certificate_id}}', '{{ $certifiedPhotographer->photographer_email}}', '{{ $certifiedPhotographer->cc_emails }}', '{{ $certifiedPhotographer->bcc_emails }}', '{{ $certifiedPhotographer->certificate_status}}')">
                                                <i class="fas fa-info" title="Change Certificate Status" style="color: #17a2b8 !important;">
                                                
                                                </i>
                                            </a>

                                            &nbsp; | &nbsp;

                                            <a href="javascript:void(0)" onClick="changeCertificateDate('{{$certifiedPhotographer->certificate_id}}', '{{ $certifiedPhotographer->photographer_email}}', '{{ $certifiedPhotographer->cc_emails }}', '{{ $certifiedPhotographer->bcc_emails }}', '{{ date('Y-m-d', strtotime($certifiedPhotographer->expiry_date)) }}')">
                                                <i class="fas fa-clock" title="Change Certificate Date" style="color: #17a2b8 !important;">
                                                
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

                            {{ $getCertifiedPhotographer->appends(['certify_id' => \Request::get('certify_id'), 'study_name' => \Request::get('study_name'), 'photographer_name' => \Request::get('photographer_name'), 'site_name' => \Request::get('site_name'), 'modility_id' => \Request::get('modility_id'), 'certificate_status' => \Request::get('certificate_status'), 'certificate_type' => \Request::get('certificate_type'), 'validity' => \Request::get('validity'), 'issue_date' => \Request::get('issue_date'), 'expiry_date' => \Request::get('expiry_date')])->links() }}

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
            <input type="hidden" name="gf_pdf_key" class="gf_pdf_key" id="gf_pdf_key" value="">

              <div class="modal-body">

                <div class="form-group col-md-12">
                    <label>Study</label>
                    <select name="study" id="study" class="form-control" required="">
                        <option value="">Select Study</option>
                        @foreach($getStudies as $study)
                            <option value="{{ $study->id }}">{{ $study->study_code.' - '. $study->study_short_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">Email To<span class="field-required">*</span></label>
                    <Select class="form-control user_email" name="user_email" id="user_email" required>

                    </Select>
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">CC Email</label>
                    <input type="text" class="form-control cc_user_email" name="cc_user_email" id="cc_user_email" value="">
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">BCC Email</label>
                    <input type="text" class="form-control bcc_user_email" name="bcc_user_email" id="bcc_user_email" value="">
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
                    <button type="submit" class="btn btn-success approve-gf-pdf">View Certificate PDF</button>      
                </div>
                    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary generate-gf-pdf" disabled>Generate Certificate</button>

              </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Modal ends -->

    <!-- Status ModAL -->
    <div class="modal fade" id="change-certificate-status-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Change Certificate Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="{{ route('change-certificate-status') }}" method="POST" class="change-certificate-status-form">
                @csrf
            <input type="hidden" name="status_certificate_id" id="status_certificate_id" value="">

              <div class="modal-body">

                <div class="form-group col-md-12">
                    <label>Certifictaion Status<span class="field-required">*</span></label>
                    <select name="certification_status" id="certification_status" class="form-control" required="required">
                        <option value="">Select Status</option>
                        <option value="provisional">Provisionally Certified</option>
                        <option value="full">Full</option>
                        <option value="suspended">Suspended</option>
                        <option value="expired">Expired</option>
                        <option value="audit">In Audit</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">Email To<span class="field-required">*</span></label>
                    <Select class="form-control status_user_email" name="status_user_email" id="status_user_email" required>

                    </Select>
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">CC Email</label>
                    <input type="text" class="form-control status_cc_user_email" name="status_cc_user_email" id="status_cc_user_email" value="">
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">BCC Email</label>
                    <input type="text" class="form-control status_bcc_user_email" name="status_bcc_user_email" id="status_bcc_user_email" value="">
                </div>

                <div class="form-group col-md-12">
                                            
                    <label for="inputState">Templates</label>
                    <select id="status_template" name="status_template" class="form-control">
                        <option value="">Select Template</option>
                         @foreach($getTemplates as $template)
                         <option value="{{ $template->template_id }}">{{ $template->template_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12 comment-div">
                    <label>Comments<span class="field-required">*</span></label>
                    <textarea class="form-control status_summernote" name="status_comment" value="" rows="4"></textarea>
                    <span class="status-edit-error-field" style="display: none; color: red;">Please fill comment field.</span>
                </div>
                    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Change Certificate Status</button>

              </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Modal ends -->

    <!-- Certification Date ModAL -->
    <div class="modal fade" id="change-certificate-date-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Change Certificate date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="{{ route('change-certificate-date') }}" method="POST" class="change-certificate-date-form">
                @csrf
            <input type="hidden" name="date_certificate_id" id="date_certificate_id" value="">
            <input type="hidden" name="date_certificate_approve_status" id="date_certificate_approve_status" value="">


              <div class="modal-body">

                <div class="form-group col-md-12">
                    <label class="edit_users">Email To<span class="field-required">*</span></label>
                    <Select class="form-control date_user_email" name="date_user_email" id="date_user_email" required>

                    </Select>
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">CC Email</label>
                    <input type="text" class="form-control date_cc_user_email" name="date_cc_user_email" id="date_cc_user_email" value="">
                </div>

                <div class="form-group col-md-12">
                    <label class="edit_users">BCC Email</label>
                    <input type="text" class="form-control date_bcc_user_email" name="date_bcc_user_email" id="date_bcc_user_email" value="">
                </div>

                <div class="form-group col-md-12">
                                            
                    <label for="inputState">Templates</label>
                    <select id="date_template" name="date_template" class="form-control">
                        <option value="">Select Template</option>
                         @foreach($getTemplates as $template)
                         <option value="{{ $template->template_id }}">{{ $template->template_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-12 comment-div">
                    <label>Comments<span class="field-required">*</span></label>
                    <textarea class="form-control date_summernote" name="date_comment" value="" rows="4"></textarea>
                    <span class="date-edit-error-field" style="display: none; color: red;">Please fill comment field.</span>
                </div>

                <div class="form-group col-md-12">
                    <label>Expiry Date<span class="field-required">*</span></label>
                    <input type="date" class="form-control data-required" id="certificate_expiry_date" name="certificate_expiry_date" value="" required>
                </div>

                <div class="form-group col-md-12 suspend-certificate-div"> 
                    <button type="submit" class="btn btn-success approve-date-certificate-pdf">View Certificate PDF</button>      
                </div>
                    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary generate-date-certificate-pdf" disabled>Change Certificate Expiry</button>

              </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Modal ends -->

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

<!-- sweet alert -->
<script src="{{ asset('public/dist/vendors/sweetalert/sweetalert.min.js') }}"></script>   

<script type="text/javascript">
    
    $('#study').select2();
    $('#modility_id').select2();

     // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        $('.filter-form').submit();
    });

    /****************** Date Range Date picker *********************/

    // initialize date range picker
    $('input[name="issue_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="issue_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="issue_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('input[name="expiry_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="expiry_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="expiry_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // initialize summer note
    $('.summernote').summernote({
        height: 150,

    });

    /********************* show Details ************************************/

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

    /************************** Generate Certificate *********************************************/ 

    // initiallize tags
    $('#cc_user_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    $('#bcc_user_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    // grandfathering function
    function generateGrandfatherCertificate(certificateID, photographerEmail, ccEmail, bccEmail) {

        // remove old cc tag
        removeCCTag($('#cc_user_email'));
        // remove old bcc tag
        removeBCCTag($('#bcc_user_email'));

        // assign email to email To input
        $('.user_email').append('<option value="'+photographerEmail+'">'+photographerEmail+'</option>');

        // assign cc and bcc emails
        $.each(JSON.parse(ccEmail), function(index, value) {
                                    
            //append new value
            $('#cc_user_email').addTag(value);
        });

        $.each(JSON.parse(bccEmail), function(index, value) {
                                    
            // append new tag
            $('#bcc_user_email').addTag(value);
        });

        // unselect study
        $('#study').val("").trigger("change");
        // unselect templete
        $('.template').val(''); 
        // empty text editor
        $('.summernote').summernote('code', '');

        // assign Certificate ID
        $('#certificate_id').val(certificateID);
        // enable approve pdf button
        $('.approve-gf-pdf').attr('disabled', false);
        // assign file key
        $('.gf_pdf_key').val('view pdf');
        // disable generate button
        $('.generate-gf-pdf').attr('disabled', true);
        // make form target blank
        $('.certificate-grandfather-form').attr('target', '_blank');
        // show modal
        $('#certificate-grandfather-modal').modal('show');
    }

    $('.certificate-grandfather-form').submit(function(e) {

        e.preventDefault();

        if($('.gf_pdf_key').val() == 'view pdf') {

            // diable approve pdf button
            $('.approve-gf-pdf').attr('disabled', true);
            // enable generate button
            $('.generate-gf-pdf').attr('disabled', false);
            // submit the form
            e.currentTarget.submit();

        } else {

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

        } // approve status check ends
 
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

    $('.generate-gf-pdf').click(function(){

        // make form target blank
        $('.certificate-grandfather-form').removeAttr('target');

       // assign file key
        $('.gf_pdf_key').val('generate pdf');

        $('.certificate-grandfather-form').submit();

    });

    /******************************* change status modal *********************************/
    
    $('.status_summernote').summernote({
        height: 150,

    });

    // initiallize tags
    $('#status_cc_user_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    $('#status_bcc_user_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    // status change function
    function changeCertificateStatus(certificateID, photographerEmail, ccEmail, bccEmail, status) {

        // remove old cc tag
        removeCCTag($('#status_cc_user_email'));
        // remove old bcc tag
        removeBCCTag($('#status_bcc_user_email'));

        // assign email to email To input
        $('.status_user_email').append('<option value="'+photographerEmail+'">'+photographerEmail+'</option>');

         // assign cc and bcc emails
        $.each(JSON.parse(ccEmail), function(index, value) {
                                    
            //append new value
            $('#status_cc_user_email').addTag(value);
        });

        $.each(JSON.parse(bccEmail), function(index, value) {
                                    
            //append new value
            $('#status_bcc_user_email').addTag(value);
        });

        // assign status
        $('#certification_status').val(status);

        // unselect templete
        $('#status_template').val(''); 
        // empty text editor
        $('.status_summernote').summernote('code', '');

        // hide error message
        $('.status-edit-error-field').css('display', 'none');

        // assign Certificate ID
        $('#status_certificate_id').val(certificateID);
        // show modal
        $('#change-certificate-status-modal').modal('show');
    }

    // form submit
    $('.change-certificate-status-form').submit(function(e) {
        
        if($('.status_summernote').summernote('isEmpty')) {
            // cancel submit
            e.preventDefault(); 
            $('.status-edit-error-field').css('display', 'block');

        } else {

            e.currentTarget;
        }
    });


    $('#status_template').change(function() {

        $.ajax({
            url: '{{ route("get-template-data") }}',
            type: 'GET',
            data: {
                'template_id': $(this).val(),
            },
            success:function(data) {

                if(data.getTemplate != null) {

                    // assign body
                    $('.status_summernote').summernote('code', data.getTemplate.template_body);

                } else {

                    // assign body
                    $('.status_summernote').summernote('code', '');
                }
                
            } // success ends

        }); // ajax ends

    });  // change function ends

    /**************************** Change Certificate Date Modal *************************************/

    $('.date_summernote').summernote({
        height: 150,

    });

    // initiallize tags
    $('#date_cc_user_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    $('#date_bcc_user_email').tagsInput({
        'defaultText':'add email',
        'removeWithBackspace' : true,
    });

    // status change function
    function changeCertificateDate(certificateID, photographerEmail, ccEmail, bccEmail, date) {

        // remove old cc tag
        removeCCTag($('#date_cc_user_email'));
        // remove old bcc tag
        removeBCCTag($('#date_bcc_user_email'));

        // assign email to email To input
        $('.date_user_email').append('<option value="'+photographerEmail+'">'+photographerEmail+'</option>');

        // assign cc and bcc emails
        $.each(JSON.parse(ccEmail), function(index, value) {
                                    
            //append new value
            $('#date_cc_user_email').addTag(value);
        });

        $.each(JSON.parse(bccEmail), function(index, value) {
                                    
            //append new value
            $('#date_bcc_user_email').addTag(value);
        });

        // assign date
        $('#certificate_expiry_date').val(date);

        // unselect templete
        $('#date_template').val(''); 
        // empty text editor
        $('.date_summernote').summernote('code', '');

        // hide error message
        $('.date-edit-error-field').css('display', 'none');

        // assign Certificate ID
        $('#date_certificate_id').val(certificateID);
         // make date approve status to null
        $('#date_certificate_approve_status').val('');
        // enable approve pdf button
        $('.approve-date-certificate-pdf').attr('disabled', false);
        // disable generate button
        $('.generate-date-certificate-pdf').attr('disabled', true);
        // make form target blank
        $('.change-certificate-date-form').attr('target', '_blank');
        // give default url
         $('.change-certificate-date-form').attr("action", "{{ route('change-certificate-date')}}");

        // show modal
        $('#change-certificate-date-modal').modal('show');
    }

    // form submit
    $('.change-certificate-date-form').submit(function(e) {
        
        if($('.date_summernote').summernote('isEmpty')) {
            // cancel submit
            e.preventDefault(); 
            $('.date-edit-error-field').css('display', 'block');

        } else {

            e.currentTarget;

            // enable approve pdf button
            $('.approve-date-certificate-pdf').attr('disabled', true);
            // disable generate button
            $('.generate-date-certificate-pdf').attr('disabled', false);
        }
    });

     $('#date_template').change(function() {

        $.ajax({
            url: '{{ route("get-template-data") }}',
            type: 'GET',
            data: {
                'template_id': $(this).val(),
            },
            success:function(data) {

                if(data.getTemplate != null) {

                    // assign body
                    $('.date_summernote').summernote('code', data.getTemplate.template_body);

                } else {

                    // assign body
                    $('.date_summernote').summernote('code', '');
                }
                
            } // success ends

        }); // ajax ends

    });  // change function ends

    $('.generate-date-certificate-pdf').click(function(){

        // make form target blank
        $('.change-certificate-date-form').removeAttr('target');

        // set approve status to yes
        $('#date_certificate_approve_status').val('yes');

        $('.change-certificate-date-form').submit();

    });

    /************************ Grand fathering cc, bcc_emails *****************************/
    /************************ Grand fathering cc, bcc_emails *****************************/
    $('#study').change(function() {
        if ($(this).val() != '') {
            
            $.ajax({
                url: '{{ route("get-grandfather-certifictae-emails") }}',
                type: 'GET',
                data: {
                    'study_id': $(this).val(),
                },
                success:function(data) {

                    // remove old cc tag
                    removeCCTag($('#cc_user_email'));
                    // remove old bcc tag
                    removeBCCTag($('#bcc_user_email'));

                    // assign cc and bcc emails
                    $.each(data.userEmails, function(index, value) {

                        //append new value
                        $('#cc_user_email').addTag(value);
                    });

                    $.each(data.userBCCEmails, function(index, value) {
                                                
                        //append new value
                        $('#bcc_user_email').addTag(value);
                    });
                    
                } // success ends

            }); // ajax ends
        } // null check ends
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




