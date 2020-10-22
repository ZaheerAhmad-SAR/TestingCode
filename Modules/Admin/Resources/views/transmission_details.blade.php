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

                    <form action="{{route('transmissions.index')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">
                            
                            <div class="form-group col-md-3">
                                <label for="trans_id">Transmission#</label>
                                <input type="text" name="trans_id" id="trans_id" class="form-control filter-form-data" value="{{ request()->trans_id }}" placeholder="Transmission#">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="suject_id">Subject ID</label>
                                <input type="text" name="suject_id" id="suject_id" class="form-control filter-form-data" value="{{ request()->suject_id }}" placeholder="Subject ID">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="visit_name">Visit Name</label>
                                <input type="text" name="visit_name" id="visit_name" class="form-control filter-form-data" value="{{ request()->visit_name }}" placeholder="Visit Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="dt">Visit Date</label>
                                <input type="text" name="visit_date" id="visit_date" class="form-control visit_date filter-form-data" value="{{ request()->visit_date }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="imagine_modality">Imagine Modality</label>
                                <input type="text" name="imagine_modality" id="imagine_modality" class="form-control filter-form-data" value="{{ request()->imagine_modality }}" placeholder="Imagine Modality">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Transmission Status</label>
                                <select id="status" name="status" class="form-control filter-form-data">
                                    <option value="">All Status</option>
                                    <option @if(request()->status == 'pending') selected @endif value="pending">Pending</option>
                                    <option @if(request()->status == 'accepted') selected @endif  value="accepted">Accepted</option>
                                    <option @if(request()->status == 'rejected') selected @endif  value="rejected">Rejected</option>
                                </select>
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
                                        <th>Transmission#</th>
                                        <th>Site ID</th>
                                        <th>Subject ID</th>
                                        <th>Visit</th>
                                        <th>Date</th>
                                        <th>QC Status</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                        <tr>
                                            <td>{{$transmission->Transmission_Number}}</td>
                                            <td>{{$transmission->Site_ID}}</td>
                                            <td>{{$transmission->Subject_ID}}</td>
                                            <td>{{$transmission->visit_name}}</td>
                                            <td>{{$transmission->visit_date}}</td>
                                            <td></td>
                                            <td>
                                                @if($transmission->status == 'accepted')

                                                    <span class="badge badge-success">{{$transmission->status}}
                                                    </span>

                                                @elseif ($transmission->status == 'onhold' || $transmission->status == 'pending')

                                                    <span class="badge badge-warning">{{$transmission->status}}
                                                    </span>
                                                @else

                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" id="edit-subject" class="" data-id="" data-url="" title="Edit Details">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                &nbsp;|&nbsp;
                                                 <a href="{{route('transmissions.show', encrypt($transmission->id))}}" id="view-transmission" class="" data-id="" title="View details" data-url="">
                                                    <i class="far fa-eye"></i>
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
                             {{ $getTransmissions->links() }}
                         
                        </div>
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

</script>

@endsection




