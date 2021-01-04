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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Bug Reporting</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Bug Reporting</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
{{--                        @if(hasPermission(auth()->user(),'optionsGroup.create'))--}}
                           <!--  <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#reportabugmodel">
                                <i class="fa fa-plus"></i> Report a Bug
                            </button> -->
{{--                        @endif--}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Message</th>
{{--                                    <th>Status</th>--}}
                                    <th>Priority</th>
                                    <th>Section Name</th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                                <tbody>
                                @if(!$records->isEmpty())
                                    @php $count = 1; @endphp
                                @foreach($records as $record)

                                        <tr>
                                            <td>{{$count++}}</td>
                                            <td>{{$record['bug_title']}}</td>
                                            <td>{{$record['bug_message']}}</td>
{{--                                            <td>{{$record['bug_status']}}</td>--}}
                                            <td>{{$record['bug_priority']}}</td>
                                            @php
                                            $str = '';
                                            $str = $record['bug_url'];
                                            $segment = explode("/",$str);

                                            @endphp
                                             <td style="cursor: pointer;">
                                                <a target="_blank" href=" {{$record['bug_url']}}">{{ucwords($segment[4])}}</a>

                                            </td>
                                            <td>
                                                <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                        <span class="dropdown-item"><a href="#" class="deleteOptions"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="100" style="text-align: center">No record found.</td>
                                    </tr>

                                @endif
                                </tbody>
                            </table>
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
