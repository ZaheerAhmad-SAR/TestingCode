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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Bug Reports</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Bug Reports</li>
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
                                    <th style="width: 1%;">ID</th>
                                    <th style="width: 20%;">Title</th>
                                    <th style="width: 2%;">Reported by</th>
                                    <th style="width: 1%;">Priority</th>
                                    <th style="width: 1%;">Status</th>
                                    @if(hasPermission(auth()->user(),'bug-reporting.edit') && hasPermission(auth()->user(),'bug-reporting.destroy'))
                                        <th style="width: 1%;">Bug Location </th>
                                    @endif
                                    <th style="width: 1%;">created_at</th>
                                    <th style="width: 1%;">Action</th>
                                </tr>
                                <tbody>
                                @if(!$records->isEmpty())
                                    @php $count = 1; @endphp
                                @foreach($records as $record)

                                        <tr>
                                            <td>{{$count++}}</td>
                                            <td class="showBugDetails" style="cursor: pointer;" data-value="{{$record['id']}}">{{$record['bug_title']}}</td>
                                            @php $row = App\User::where('id','=',$record['bug_reporter_by_id'])->first();@endphp
                                            <td>{{$row->name}}</td>
                                            <td>{{$record['bug_priority']}}</td>
                                            <td>{{ ($record['status'] && $record['open_status']) ? $record['status'] .'-'. lcfirst($record['closed_status']) : '' }}</td>
                                            @php
                                            $str = '';
                                            $str = $record['bug_url'];
                                            $segment = explode("/",$str);

                                            @endphp
                                            @if(hasPermission(auth()->user(),'bug-reporting.edit') && hasPermission(auth()->user(),'bug-reporting.destroy'))
                                             <td style="cursor: pointer;">
                                                <a target="_blank" href=" {{$record['bug_url']}}">{{ucwords($segment[3])}}</a>

                                            </td>
                                            @endif
                                            <td>{{Carbon\Carbon::parse($record['created_at'])->diffForHumans()}}</td>
                                            @if(hasPermission(auth()->user(),'bug-reporting.edit') && hasPermission(auth()->user(),'bug-reporting.destroy'))
                                            <td>
                                                <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>

                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                        <span class="dropdown-item"><a href="javascript:void(0);" class="EditbugReporting" data-id="{{$record['id']}}"><i class="far fa-edit"></i>&nbsp; Edit </a></span>
                                                        <span class="dropdown-item"><a href="javascript:void(0);" class="deletebugReporting" data-id="{{$record['id']}}"><i class="far fa-trash-alt"></i>&nbsp; Delete </a></span>
                                                    </div>

                                                </div>
                                            </td>
                                            @else
                                                <td style="cursor: not-allowed;"><i class="fas fa-ban" aria-hidden="true"></i></td>
                                            @endif
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
                                <input type="hidden" name="editBugTitle" id="editBugTitle" value="">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-3">Developer Comment</div>
                                <div class="form-group col-md-9">
                                    <textarea class="form-control" name="developerComment" id="developerComment"></textarea>
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
                            <div class="form-group row">
                                <label for="Name" class="col-md-3 col-form-label">Status</label>
                                <div class="col-md-9">
                                    <label class="radio-inline  col-form-label"><input type="radio" id="editStatus" name="editStatus" value="open"> open</label> &nbsp;
                                    <label class="radio-inline  col-form-label"><input type="radio" id="editStatus" name="editStatus" value="close"> close</label>
                                </div>
                            </div>

                            <div class="form-group row openStatusList" style="display: none;">
                                <label for="Name" class="col-md-3 col-form-label">Open Status</label>
                                <div class="col-md-9">
                                    <select name="openStatus" id="openStatus" class="form-control">
                                        <option value="Unconfirmed">Unconfirmed</option>
                                        <option value="Untriaged">Untriaged</option>
                                        <option value="Available">Available</option>
                                        <option value="Assigned">Assigned</option>
                                        <option value="Started">Started</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row closeStatusList" style="display: none;">
                                <label for="Name" class="col-md-3 col-form-label">Close Status</label>
                                <div class="col-md-9">
                                    <select name="closeStatus" id="closeStatus" class="form-control">
                                        <option value="Fixed">Fixed</option>
                                        <option value="Verified">Verified</option>
                                        <option value="Duplicate">Duplicate</option>
                                        <option value="WontFix">WontFix</option>
                                        <option value="ExternalDependency">ExternalDependency</option>
                                        <option value="FixUnreleased">FixUnreleased</option>
                                        <option value="Invalid">Invalid</option>
                                    </select>
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


    <!-- Modal Report a bug -->
    <div class="modal fade" tabindex="-1" role="dialog" id="showBugDetailsModel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title"> Report a Bug</p>
                    <p class="modal-title-status">  </p>
                </div>
                <form name="showBugDetailsForm" id="showBugDetailsForm">
                    <div class="modal-body">
                        <div class="bugdataResponse"></div>
                        <div class="modal-footer">
                            <button id="bug-close-btn" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
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


        $('body').on('click','.showBugDetails',function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var currentRow = $(this).data("value");
            $('#showBugDetailsModel').modal('show');
            getCurrentBugData(currentRow);

        });

        function getCurrentBugData(currentRow)
        {
            $.ajax({
                url:"{{route('bug-reports.getCurrentRowData')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'currentRow' :currentRow,
                },
                success: function(response)
                {
                    console.log(response);
                    $('.bugdataResponse').html('');
                    $('.bugdataResponse').html(response);
                    var bugCurrentStatus  = $('#bugStatus').val();
                    $('.modal-title-status').text('Status :'+' '+bugCurrentStatus);

                }
            });
        }


        // Bug Delete function
        $('body').on('click', '.deletebugReporting', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var parent_id = $(this).data("id");
            var url = "{{URL('/bug-reports')}}";
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
            var url = "{{URL('bug-reports')}}";
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
                    $('#editBugUrl').val(parsedata.bug_url);
                    $('#editBugTitle').val(parsedata.bug_title);
                    $('#editBugStatus').val(parsedata.bug_status);
                    $('#editBugId').val(parsedata.id);

                    // console.log(parsedata.open_status);
                    // console.log(parsedata.closed_status);

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

                    if (parsedata.status =='open')
                    {
                        $("input[name=editStatus][value=" + parsedata.status + "]").prop('checked', true);
                        $('.openStatusList').css('display','');
                        $('.closeStatusList').css('display','none');
                    }

                    if (parsedata.status =='close')
                    {
                        $("input[name=editStatus][value=" + parsedata.status + "]").prop('checked', true);
                        $('.openStatusList').css('display','none');
                        $('.closeStatusList').css('display','');

                    }
                    /// Open status dropdownvalue

                    if (parsedata.open_status =='Unconfirmed')
                    {
                        $( "#openStatus").val(parsedata.open_status);
                    }
                    if (parsedata.open_status =='Untriaged')
                    {
                        $( "#openStatus").val(parsedata.open_status);
                    }

                    if (parsedata.open_status =='Available')
                    {
                        $( "#openStatus").val(parsedata.open_status);
                    }
                    if (parsedata.open_status =='Assigned')
                    {
                        $( "#openStatus").val(parsedata.open_status);
                    }
                    if (parsedata.open_status =='Started')
                    {
                        $( "#openStatus").val(parsedata.open_status);
                    }

                        ///close status dropdownvalue

                    if (parsedata.closed_status =='Fixed')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }
                    if (parsedata.closed_status =='Verified')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }

                    if (parsedata.closed_status =='Duplicate')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }

                    if (parsedata.closed_status =='WontFix')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }

                    if (parsedata.closed_status =='ExternalDependency')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }

                    if (parsedata.closed_status =='FixUnreleased')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }

                    if (parsedata.closed_status =='Invalid')
                    {
                        $( "#closeStatus").val(parsedata.closed_status);
                    }
                }
            });
        });

        $("input[name='editStatus']").click(function() {

            var statusValue = $(this).val();

            if (statusValue == 'open')
            {
                $('.openStatusList').css('display','');
                $('.closeStatusList').css('display','none');
            }

            if (statusValue == 'close')
            {
                $('.openStatusList').css('display','none');
                $('.closeStatusList').css('display','');
            }
        });

        $("#editBugReportingForm").submit(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                data: $('#editBugReportingForm').serialize(),
                url: "{{ route('bug-reports.update') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#editReportBugModel').modal('hide');
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
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
