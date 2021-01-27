@extends('layouts.home')

@section('title')
    <title> CRFs | {{ config('app.name', 'Laravel') }}</title>
@stop

<!-- date range picker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<!-- select2 -->
<link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Notifications</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Notifications</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <div class="card">
            <div class="card-body">
                <form action="{{route('notifications.index')}}" method="get" class="filter-form">
                    @csrf
                    <div class="form-row" style="padding: 10px;">
{{--                        <input type="hidden" name="sort_by_field" id="sort_by_field" value="">--}}
{{--                        <input type="hidden" name="sort_by_field_name" id="sort_by_field_name" value="">--}}

                        <div class="form-group col-md-3">
                            <label for="inputStatus"> Status </label>
                            <select id="is_read" name="is_read" class="form-control filter-form-data">
                                <option value="">All Status </option>
                                <option @if(request()->is_read =='yes') selected @endif  value="yes">Read</option>
                                <option @if(request()->is_read =='no') selected @endif value="no">Un-Read</option>
                            </select>
                        </div>

{{--                        <div class="form-group col-md-3">--}}
{{--                            <label for="inputState"> Status Date </label>--}}
{{--                            <input type="text" name="status_date" id="status_date" class="form-control status_date filter-form-data" value="">--}}
{{--                        </div>--}}

                        <div class="form-group col-md-2" style="text-align: right; margin-top: 26px;">
                            <button class="btn btn-outline-warning reset-filter"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                            <button type="submit" class="btn btn-primary submit-filter"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="custom" style="padding-bottom: 10px;"></div>
        <!-- START: Card Data-->
        <div class="row">
            <div class="col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title">Notification List</h6>
                    </div>
                    <div class="card-content" style="padding-bottom: 40px;">
                        <div class="card-body p-0">
                            <ul class="list-group list-unstyled">
                                @if(!$records->isEmpty())
                                    @foreach($records as $record)
                                        @php
                                            $userData ='';
                                            $result   = '';
                                            $result   = \Modules\Queries\Entities\Query::where('id','=',$record->query_id)->where('query_status','open')->first();
                                            $userData = App\User::where('id',$result->queried_remarked_by_id)->first();
                                            $studyData = Modules\Admin\Entities\Study::where('id',$result->study_id)->first();
                                        @endphp

                                        <li class="p-2 border-bottom">
                                            <div class="media d-flex w-100">
                                                <div class="transaction-date text-center rounded bg-primary text-white p-2">
                                                    <small class="d-block">{{ date_format($result->created_at,'M')}}</small><span class="h6">{{ date_format($result->created_at,'d')}}</span>
                                                </div>
                                                <div class="media-body align-self-center pl-4">
                                                    @if($record->is_read == 'no')
                                                        <span class="mb-0 font-w-600"> <b>{{$studyData->study_short_name}} </b></span><br>
                                                        <p class="mb-0 font-w-500 tx-s-12"> <b>new query by {{$userData->name}}</b></p>
                                                        <small class="d-block">{{Carbon\Carbon::parse($result->created_at)->diffForHumans()}}</small>
                                                    @else
                                                        <span class="mb-0 font-w-600">{{$studyData->study_short_name}}</span><br>
                                                        <p class="mb-0 font-w-500 tx-s-12"> new query by {{$userData->name}}</p>
                                                        <small class="d-block">{{Carbon\Carbon::parse($result->created_at)->diffForHumans()}}</small>
                                                    @endif
                                                </div>
                                                @if($record->is_read == 'no')
                                                    <div class="ml-auto my-auto font-weight-bold text-right text-success">
                                                        <a href="#" class="mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-options-vertical"></i></a>
                                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right mail-bulk-action">
                                                            <a class="dropdown-item markAsReadNotification" data-id="{{$record->id}}" href="javascript:void(0);" ><i class="icon-book-open"></i> Mark as Read</a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="ml-auto my-auto font-weight-bold text-right text-success">
                                                        <a href="#" class="mr-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-options-vertical"></i></a>
                                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right mail-bulk-action">
                                                            <a class="dropdown-item markAsUnReadNotification" data-id="{{$record->id}}" href="javascript:void(0);"><i class="icon-notebook"></i> Mark as unread</a>
                                                            <a class="dropdown-item readnotificationdelete" data-id="{{$record->id}}" href="javascript:void(0);"><i class="icon-trash"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class=" p-2 border-bottom text-center text-capitalize"> no new notification!!!!</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>


@endsection
@section('styles')
    <style>
        div.dt-buttons{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@stop
@section('script')

    <!-- select2 -->
    <script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

    <!-- date range picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        // initialize date range picker
        $('input[name="status_date"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="status_date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="status_date"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $('select[name="is_read"]').select2();

        $('.reset-filter').click(function(){
            // reset values
            $('.filter-form').trigger("reset");
            $('.filter-form-data').val("").trigger("change");
            // submit the filter form
            window.location.reload();
        });

        $('.markAsReadNotification').click(function () {
            var id  = $(this).attr('data-id');
            $.ajax({
                url:"{{route('notifications.markAsRead')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'id' :id
                },
                success: function(response)
                {
                    console.log(response);
                    location.reload();
                }
            });
        });

        $('.markAsUnReadNotification').click(function () {
            var id  = $(this).attr('data-id');
            $.ajax({
                url:"{{route('notifications.markAsUnRead')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'id' :id
                },
                success: function(response)
                {
                    console.log(response);
                    location.reload();
                }
            });
        });

        $('.readnotificationdelete').click(function () {
            var id  = $(this).attr('data-id');

            if( confirm("Are You sure want to delete !") ==true)
            {
                $.ajax({
                    url:"{{route('notifications.removeNotification')}}",
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'POST',
                        'id' :id
                    },
                    success: function(response)
                    {
                        console.log(response);
                        location.reload();
                    }
                });
            }

        });
    </script>

    <script src="{{ asset('public/dist/vendors/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/datatable.script.js') }}"></script>

@stop
