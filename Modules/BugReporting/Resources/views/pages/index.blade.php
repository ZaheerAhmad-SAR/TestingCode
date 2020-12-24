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
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#reportabugmodel">
                                <i class="fa fa-plus"></i> Report a Bug
                            </button>
{{--                        @endif--}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Title</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Url</th>
                                    <th style="width: 5%;">Action</th>
                                </tr>
                                       @foreach($records as $record)

                                        <tr>
                                            <td>{{$record['bug_title']}}</td>
                                            <td>{{$record['bug_message']}}</td>
                                            <td>{{$record['bug_status']}}</td>
                                            <td>{{$record['bug_priority']}}</td>
                                            <td style="cursor: pointer;">{{$record['bug_url']}}</td>
                                            <td>
                                                <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                        <span class="dropdown-item"><a href="#" class="editOptions" ><i class="far fa-edit"></i>&nbsp; Edit </a></span>
                                                        <span class="dropdown-item"><a href="#" class="deleteOptions"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>


    <!-- Modal To add Option Groups -->
    <div class="modal fade" tabindex="-1" role="dialog" id="reportabugmodel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Report a Bug</p>
                </div>
                <form name="bugReportingForm" id="bugReportingForm">
                    <div class="modal-body">
                            <div class="tab-content clearfix">

                                <div class="form-group row">
                                    <div class="col-md-3">Short Title</div>
                                    <div class="form-group col-md-9">
                                        <input type="text" name="shortTitle" id="shortTitle" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">Enter Your Message</div>
                                    <div class="form-group col-md-9">
                                        <textarea class="form-control" name="yourMessage" id="yourMessage"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">Attach a File</div>
                                    <div class="form-group col-md-9">
                                        <input type="file" class="form-control" id="attachFile" name="attachFile">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Name" class="col-md-3 col-form-label">Severity/Priority</label>
                                    <div class="col-md-9">
                                        <label class="radio-inline  col-form-label"><input type="radio" id="severity" name="severity" value="low"> Low</label> &nbsp;
                                        <label class="radio-inline  col-form-label"><input type="radio" id="severity" name="severity" value="medium"> Medium</label>
                                        <label class="radio-inline  col-form-label"><input type="radio" id="severity" name="severity" value="high"> High</label>
                                    </div>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button id="bug-close-btn" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End -->

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
    <script type="text/javascript">


        $("#bugReportingForm").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var shortTitle  = $("#shortTitle").val();
            var yourMessage = $("#yourMessage").val();
            var query_url   =  document.URL;
            var severity    = $("#severity").val();
            var formData = new FormData();
            formData.append('shortTitle', shortTitle);
            formData.append('yourMessage', yourMessage);
            formData.append('query_url', query_url);
            formData.append('severity', severity);

            // Attach file
            formData.append("attachFile", $("#attachFile")[0].files[0]);

            $.ajax({
                url: "{{route('bug-reporting.store')}}",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function (results) {
                    console.log(results);
                    $('#bugReportingForm').trigger("reset");
                    location.reload();
                },
                error: function (results) {
                    console.log('Error:', results);
                }
            });
        });
    </script>
@stop
