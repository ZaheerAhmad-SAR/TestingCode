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
                                        <th>Transmission#</th>
                                        <th>Study</th>
                                        <th>Device Category</th>
                                        <th>Device Serial</th>
                                        <th>Site</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                        <tr>
                                            <td>  
                                                <a href="#" id="view-transmission" class="" data-id="" title="Edit Certifaction Device Details" data-url="" style="color: #17a2b8 !important">
                                                    {{$transmission->Transmission_Number}}
                                                </a>
                                            </td>
                                            <td> {{$transmission->Study_Name}} </td>
                                            <td> {{$transmission->Device_Category}} </td>
                                            <td> {{$transmission->Device_Serial}}</td>
                                            <td> {{$transmission->Site_Name}} </td>
                                            <td> 
                                                @if($transmission->status == 'accepted')

                                                    <span class="badge badge-success">{{$transmission->status}}
                                                    </span>

                                                @elseif($transmission->status == 'pending')

                                                    <span class="badge badge-primary">{{$transmission->status}}
                                                    </span>

                                                @elseif($transmission->status == 'rejected')

                                                    <span class="badge badge-danger">{{$transmission->status}}
                                                    </span>

                                                @elseif($transmission->status == 'deficient')

                                                    <span class="badge badge-warning">{{$transmission->status}}
                                                    </span>

                                                @elseif($transmission->status == 'duplicate')

                                                    <span class="badge badge-dark">{{$transmission->status}}
                                                    </span>


                                                @endif 
                                            </td>

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
        <!-- END: Card DATA-->
    </div>


    <!-- transmission status modal  -->
    <!-- Modal -->
    <div class="modal fade" id="transmission-status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Change Transmission Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="#" method="POST" class="transmission-status-form">
                @csrf
              <div class="modal-body">
                    <input type="hidden" name="hidden_transmission_id" value="">
                    <div class="form-group col-md-12">
                        <label>Change Status</label>
                        <select name="status" id="status" class="form-control" required="required">
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Reject</option>
                            <option value="onhold">On-Hold</option>
                            <option value="query_opened">Open Query</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Comments / Query Text for site coordinator</label>
                        <textarea class="form-control" name="comment" value="" rows="4" required=""></textarea>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Change Status</button>
              </div>
            </form>
        </div>
      </div>
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

 

    // initialize date range picker
    $('input[name="visit_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="visit_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="visit_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        $('.filter-form').submit();
    });

    // function transmissionStatus(transmissionId, transmissionStatus) {

    //     // assign transmission id
    //     $('.transmission-status-form').find($('input[name="hidden_transmission_id"]')).val(transmissionId);
    //     // assign status
    //     $('.transmission-status-form').find($('select[name="status"]')).val(transmissionStatus);
    //     $('.transmission-status-form').find($('textarea[name="comment"]')).val('');
    //     $('#transmission-status-modal').modal('show');
    // }

</script>

@endsection




