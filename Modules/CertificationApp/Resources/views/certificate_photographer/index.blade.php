@extends ('layouts.home')

@section('title')
    <title> Certification Photographer | {{ config('app.name', 'Laravel') }}</title>
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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Certification Photographer</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certification Photographer</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                   
                    <form action="{{route('certification-photographer.index')}}" method="get" class="filter-form">
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
                                <label for="photographer_name">Photographer Name</label>
                                <input type="text" name="photographer_name" id="photographer_name" class="form-control filter-form-data" value="{{ request()->photographer_name }}" placeholder="Photographer Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="certification">Certification</label>
                                <input type="text" name="certification" id="certification" class="form-control filter-form-data" value="{{ request()->certification }}" placeholder="Certification">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="site">Site Name</label>
                                <input type="text" name="site" id="site" class="form-control filter-form-data" value="{{ request()->site }}" placeholder="Site Name">
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
                   
                   <hr>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Photographer</th>
                                        <th>Certification</th>
                                        <th>Study</th>
                                        <th>Site</th>
                                        <th>Certification Status</th>
                                        <th>Transmission#</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                        <tr style="background: {{ $transmission->rowColor }}">
                                            <td> 
                                                {{$transmission->Photographer_First_Name}} 
                                            </td>
                                            <td> {{$transmission->Requested_certification}}</td>
                                           
                                            <td> {{$transmission->Study_Name}} </td>
                                           
                                            <td> {{$transmission->Site_Name}} </td>
                                            
                                            <td> 
                                                <a href="javascript:void()" id="generate-certification" data-id="" title="Generate Certificate" class="badge badge-dark" onClick="generateCertificate('{{$transmission->id}}')">
                                                    Generate Certificate
                                                </a>
                                            </td>
                                            
                                            <td>

                                            @if ($transmission->linkedTransmission != null)

                                            @foreach($transmission->linkedTransmission as $linkedTransmission)

                                                <a href="{{ route('certification-photographer.edit', encrypt($linkedTransmission['id']))}}" id="view-transmission" class="" data-id="" title="Edit Certifaction Photographer Details" data-url="" style="color: #17a2b8 !important;">
                                                    <strong>
                                                    {{ $linkedTransmission['Transmission_Number'] }}
                                                    </strong>
                                                </a>

                                                &nbsp; | &nbsp;

                                                <span class="text-dark">
                                                    <strong> {{$linkedTransmission['status']}} </strong>
                                                </span>

                                                {{--

                                                @if($linkedTransmission['status'] == 'accepted')

                                                    <span class="badge badge-success" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'pending')

                                                    <span class="badge badge-primary" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'rejected')

                                                    <span class="badge badge-danger" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'deficient')

                                                    <span class="badge badge-warning" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'duplicate')

                                                    <span class="badge badge-dark" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @endif
                                                --}}
                                                <br>
                                                <br>
                                            @endforeach

                                            @else
                                                N/A
                                            @endif
                                                <!-- |
                                                <i class="fas fa-edit"> </i> -->

                                            </td>

                                            <!--  <td>
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
                                                
                                            </td>  -->
                                            
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="7" style="text-align: center">No record found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                             {{ $getTransmissions->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA -->
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
            <form action="" method="POST" class="generate_certificate-form">
                @csrf
              <div class="modal-body">
                    <input type="hidden" name="hidden_transmission_id" value="">
                    
                    <div class="form-group col-md-12">
                        <label>Change Status<span class="field-required">*</span></label>
                        <select name="certification_status" id="certification_status" class="form-control" required="required">
                            <option value="">Select Status</option>
                            <option value="provisional">Provisionally Certified</option>
                            <option value="full">Full</option>
                            <option value="suspended">Suspended</option>
                            <option value="expired">Expired</option>
                            <option value="audit">In Audit</option>
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
                        <label>Select Transmission</label><br>
                        <input type="checkbox" name="transmissions[]" value="20201216-071102359">
                        <input type="checkbox" name="transmissions[]" value="20201216-071102359">
                    </div>

                    <!-- ------------------------------------ grand father one -------------------------->
                    <div class="form-group col-md-12 grandfather-div" style="display: none;">
                        <label>GrandFather Certificate ID<span class="field-required">*</span></label>
                        <textarea name="grandfather_id" id="grandfather_id" rows="3" class="form-control data-required"></textarea>
                    </div>

                    <!-- ------------------------------------------------------------------------------------- -->

                    <div class="form-group col-md-12">
                        <label class="edit_users">Email To<span class="field-required">*</span></label>
                        <Select class="form-control user_email" name="user_email" id="user_email" required>

                        </Select>
                    </div>

                     <div class="form-group col-md-12 suspend-certificate-div">
                        <label class="edit_users">CC Email<span class="field-required">*</span></label>
                        <Select class="form-control cc_user_email data-required" name="cc_user_email" id="cc_user_email" required>

                        </Select>
                    </div>

                    <div class="form-group col-md-12 comment-div">
                        <label>Comments<span class="field-required">*</span></label>
                        <textarea class="form-control summernote" name="comment" value="" rows="4"></textarea>
                        <span class="edit-error-field" style="display: none; color: red;">Please fill comment field.</span>
                    </div>

                    <div class="form-group col-md-12 suspend-certificate-div">
                        <label>Issue Date</label>
                        <input type="date" class="form-control data-required" id="issue_date" name="issue_date" value="">
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

<script type="text/javascript">

    // on status change
    $('#certification_status').change(function() {
        // if this is the value show all div other vice hide the other divs
        if($(this).val() == 'provisional' || $(this).val() == 'full') {
            
            // show div
            $('.suspend-certificate-div').css('display', 'block');
            // and apply required
            $('.data-required').attr('required', true);
        
        } else {

            // show div
            $('.suspend-certificate-div').css('display', 'none');
            // and apply required
            $('.data-required').attr('required', false);
        }
    });

    // initialize summer note
    $('.summernote').summernote({
        height: 150,

    });

    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        $('.filter-form').submit();
    });

    function generateCertificate(transmissionID){
        
        // show modal
        $('#generate-certificate-modal').modal("show");

        // ajax call to bring data

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

        } else {

            // hide original div
            $('.original-div').css('display', 'none');
            // show grandfather div
            $('.grandfather-div').css('display', 'block');
            // apply required for this div
            $('#grandfather_id').attr('required', true);

        }

    });

</script>

@endsection




