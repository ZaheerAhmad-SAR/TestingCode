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
                                    <th>Status</th>
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
                                            <td>{{$record['bug_status']}}</td>
                                            <td>{{$record['bug_priority']}}</td>
                                            @php
                                            $str = '';
                                            $str = $record['bug_url'];
                                            $segment = explode("/",$str);

                                            @endphp
                                             <td style="cursor: pointer;">
                                                <a target="_blank" href=" {{$record['bug_url']}}">{{ucwords($segment[3])}}</a>

                                            </td>
                                            <td>
                                                <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                        <span class="dropdown-item"><a href="javascript:void(0);" class="deletebugReporting" data-id="{{$record['id']}}"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span>
                                                        <span class="dropdown-item"><a href="javascript:void(0);" class="EditbugReporting" data-id="{{$record['id']}}"><i class="far fa-edit"></i>&nbsp; Edit </a></span>
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

    <!-- Modal Report a bug -->
    <div class="modal fade" tabindex="-1" role="dialog" id="editReportBugModel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title"> Edit Report a Bug</p>
                </div>
                <form name="editBugReportingForm" id="editBugReportingForm">
                    <div class="modal-body">
                        <div class="tab-content clearfix">
                            <div class="garbageData">
                                <input type="hidden" name="editBugId" id="editBugId" value="">
                                <input type="hidden" name="editBugUrl" id="editBugUrl" value="">
                                <input type="hidden" name="editBugStatus" id="editBugStatus" value="">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">Short Title</div>
                                <div class="form-group col-md-9">
                                    <input type="text" name="editShortTitle" id="editShortTitle" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">Enter Your Message</div>
                                <div class="form-group col-md-9">
                                    <textarea class="form-control" name="editYourMessage" id="editYourMessage"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">Attach a File</div>
                                <div class="form-group col-md-9">
                                    <input type="file" class="form-control" id="editAttachFile" name="edutAttachFile" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-md-3 col-form-label">Severity/Priority</label>
                                <div class="col-md-9">
                                    <label class="radio-inline  col-form-label"><input type="radio" id="editSeverity" name="editSeverity" value="low"> Low</label> &nbsp;
                                    <label class="radio-inline  col-form-label"><input type="radio" id="editSeverity" name="editSeverity" value="medium"> Medium</label>
                                    <label class="radio-inline  col-form-label"><input type="radio" id="editSeverity" name="editSeverity" value="high"> High</label>
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

    <script type="text/javascript">


        // Bug Delete function
        $('body').on('click', '.deletebugReporting', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var parent_id = $(this).data("id");
            var url = "{{URL('/bug-reporting')}}";
            var newPath = url+ "/"+ parent_id+"/destroy/";
            if( confirm("Are You sure want to delete !") ==true)
            {
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }

        });


        $('body').on('click', '.EditbugReporting', function (e) {
            $('#editBugReportingForm').trigger('reset');
            //$('.appendDataOptions_edit').html('');
            $('#editReportBugModel').modal('show');
            var id =($(this).attr("data-id"));
            var url = "{{URL('bug-reporting')}}";
            var newPath = url+ "/"+ id+"/edit/";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results) {
                    console.log(results);
                    var parsedata = JSON.parse(results)[0];
                    $('#editShortTitle').val(parsedata.bug_title);
                    $('#editYourMessage').val(parsedata.bug_message);
                    $('#editBugUrl').val(parsedata.bug_url);
                    $('#editBugStatus').val(parsedata.bug_status);
                    $('#editAttachFile').val(parsedata.bug_attachments);
                    $('#editBugId').val(parsedata.id);

                    if (parsedata.bug_priority =='low')
                    {
                        $("input[name=editSeverity][value=" + parsedata.bug_priority + "]").prop('checked', true);
                    }
                    if (parsedata.bug_priority =='medium')
                    {
                        $("input[name=editSeverity][value=" + parsedata.bug_priority + "]").prop('checked', true);
                    }
                    if (parsedata.bug_priority =='high')
                    {
                        $("input[name=editSeverity][value=" + parsedata.bug_priority + "]").prop('checked', true);
                    }


                }
            });
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
