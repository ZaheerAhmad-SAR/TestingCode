@extends ('layouts.home')

@section('title')
    <title> Audit Trail | {{ config('app.name', 'Laravel') }}</title>
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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Audit Trail</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Audit Trail</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <form action="{{route('trail_logs.list')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">

                            @if(hasPermission(auth()->user(),'systemtools.index'))
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Name</label>
                                <select class="form-control user_name filter-form-data" name="user_name" id="user_name">
                                    <option value="">All Users</option>
                                    @foreach($getUsers as $filterUser)
                                        <option @if(request()->user_name == $filterUser->user_id) selected @endif value="{{$filterUser->user_id}}">{{$filterUser->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            @if(hasPermission(auth()->user(),'systemtools.index'))
                            <div class="form-group col-md-4">
                            @else
                            <div class="form-group col-md-3">
                            @endif
                                <label for="inputState">Study</label>
                                <select id="event_study" name="event_study" class="form-control filter-form-data">
                                    <option value="">All Studies</option>
                                    @foreach($getStudies as $study)
                                    <option @if(request()->event_study == $study->id) selected @endif value="{{$study->id}}">{{$study->study_short_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(hasPermission(auth()->user(),'systemtools.index'))
                            <div class="form-group col-md-4">
                            @else
                            <div class="form-group col-md-2">
                            @endif
                                <label for="inputState">Event Section</label>
                                <select id="event_section" name="event_section" class="form-control filter-form-data">
                                    <option value="">All Sections</option>
                                    @foreach($eventSection as $key => $section)
                                    <option @if(request()->event_section == $section) selected @endif value="{{$section}}">{{$section}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(hasPermission(auth()->user(),'systemtools.index'))
                            <div class="form-group col-md-4">
                            @else
                            <div class="form-group col-md-2">
                            @endif
                                <label for="inputState">Event Type</label>
                                <select id="event_type" name="event_type" class="form-control filter-form-data">
                                    <option value="">All Types</option>
                                    <option @if(request()->event_type == 'Add') selected @endif vale="Add">Add</option>
                                    <option  @if(request()->event_type == 'Update') selected @endif value="Update">Update</option>
                                </select>
                            </div>

                            @if(hasPermission(auth()->user(),'systemtools.index'))
                            <div class="form-group col-md-4">
                            @else
                            <div class="form-group col-md-3">
                            @endif
                                <label for="dt">Event Date</label>
                                <input type="text" name="event_date" id="event_date" class="form-control event_date filter-form-data" value="{{ request()->event_date }}">
                            </div>

                            @if(hasPermission(auth()->user(),'systemtools.index'))
                            <div class="form-group col-md-2 mt-4">
                            @else
                            <div class="form-group col-md-2 mt-4">
                            @endif
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lng">Filter</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                   <hr>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Event Type</th>
                                        <th>Event Note</th>
                                        <th>IP Address</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getLogs->isEmpty())
                                    @foreach($getLogs as $log)
                                    <tr>
                                        <td style="text-align: center;">
                                          <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$log->id}}" style="font-size: 20px; color: #1e3d73;"></i>
                                          </div>
                                        </td>
                                        <td>{{$log->name}}</td>
                                        <td>{{$log->event_type}}</td>

                                        <td>{{$log->event_message}}</td>

                                        <td>{{$log->ip_address}}</td>
                                        <td>{{$log->created_at}}</td>
                                    </tr>
                                    <tr class="collapse row-{{$log->id}}">
                                        <td colspan="6">
                                           <table class="table table-hover" style="width: 100%">
                                                <thead class="table-secondary">
                                                    @if($log->event_type == 'Add')
                                                        <th>Name</th>
                                                        <th>Value</th>
                                                    @else
                                                         <th>Name</th>
                                                        <th>New Value</th>
                                                        <th>Old Value</th>
                                                    @endif
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $newDetails = json_decode($log->event_details);
                                                        $oldDetails = json_decode($log->event_old_details);
                                                    @endphp

                                                    <!-- for add event -->
                                                    @if($log->event_type == 'Add')
                                                        @foreach($newDetails as $key => $details)
                                                        <tr>
                                                            <td>{{$key}}</td>
                                                            <td>{{$details}}</td>
                                                        </tr>
                                                        @endforeach

                                                        <!-- for update event -->
                                                        @else
                                                        @foreach($newDetails as $key => $details)
                                                        <tr>
                                                            <td>{{$key}}</td>
                                                            <td>{{$details}}</td>
                                                            <td>{{$oldDetails->$key}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                      <!-- // -->
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                          {{$getLogs->appends(['user_name' => \Request::get('user_name'), 'event_section' => \Request::get('event_section'), 'event_type' => \Request::get('event_type'), 'event_date' => \Request::get('event_date')])->links()}}
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

    // toggle class for showing details
    $('.detail-icon').click(function(e){
        $(this).toggleClass("fa-chevron-circle-right fa-chevron-circle-down");
    });

    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        $('.filter-form').submit();
    });

    // initialize date range picker
    $('input[name="event_date"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="event_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="event_date"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // selct initialize
    $('.user_name').select2();
    $('select[name="event_section"]').select2();
    $('select[name="event_study"]').select2();

</script>
@endsection




