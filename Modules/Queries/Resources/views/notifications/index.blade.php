@extends('layouts.home')

@section('title')
    <title> CRFs | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Notification</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Notification</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

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

{{--                                            @if(!empty($records))--}}
                                                @if(!$records->isEmpty())
                                                @foreach($records as $record)

                                                @php

                                                    $userData ='';

                                                    $result = '';
                                                    $result      = \Modules\Queries\Entities\Query::where('id','=',$record->query_id)->where('query_status','open')->first();
                                                    $userData  = App\User::where('id',$result->queried_remarked_by_id)->first();

                                                @endphp

                                                @php
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

    <script type="text/javascript">
        $('.markAsReadNotification').click(function () {
            var id  = $(this).attr('data-id');
            $.ajax({
                url:"{{route('queries.markAsRead')}}",
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
                    url:"{{route('queries.deletenotification')}}",
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
