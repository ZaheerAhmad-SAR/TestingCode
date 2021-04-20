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
        div#cc_email_tagsinput {
            width: 631px !important;
            min-height: 42px !important;
            height: 30px !important;
            overflow: hidden !important;
        }
    </style>
    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css" rel="stylesheet">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    <!-- select2-->


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

                    <form action="{{route('transmissions.study-transmissions')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">
                            <input type="hidden" name="sort_by_field" id="sort_by_field" value="{{ request()->sort_by_field }}">
                            <input type="hidden" name="sort_by_field_name" id="sort_by_field_name" value="{{ request()->sort_by_field_name }}">
                            <div class="form-group col-md-3">

                                <label for="trans_id">Transmission#</label>
                                <input type="text" name="trans_id" id="trans_id" class="form-control filter-form-data" value="{{ request()->trans_id }}" placeholder="Transmission#">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="suject_id">Subject ID</label>
                                <input type="text" name="subject_id" id="subject_id" class="form-control filter-form-data" value="{{ request()->subject_id }}" placeholder="Subject ID">
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
                            {{--
                             <div class="form-group col-md-2">
                                <label for="inputState"> Modality </label>
                                <select id="modility_id" name="modility_id" class="form-control filter-form-data">
                                    <option value="">All Modality</option>
                                    @foreach($getModalities as $modality)
                                    <option @if ($modality->id == request()->modility_id) selected @endif value="{{ $modality->id}}"> {{ $modality->modility_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            --}}

                            <div class="form-group col-md-3">
                                <label for="inputState"> Processed Status</label>
                                <select id="is_read" name="is_read" class="form-control filter-form-data">
                                    <option value="">All Processed Status</option>
                                    <option @if(request()->is_read == 'no') selected @endif value="no">Not Processed</option>
                                    <option @if(request()->is_read == 'yes') selected @endif  value="yes">Processed</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Transmission Status</label>
                                <select id="status" name="status" class="form-control filter-form-data">
                                    <option value="">All Status</option>
                                    <option @if(request()->status == 'pending') selected @endif value="pending">Pending</option>
                                    <option @if(request()->status == 'accepted') selected @endif  value="accepted">Accepted</option>
                                    <option @if(request()->status == 'rejected') selected @endif  value="rejected">Rejected</option>
                                    <option  @if (request()->status == 'onhold') selected @endif value="onhold">On-Hold</option>
                                    <option  @if (request()->status == 'query_opened') selected @endif value="query_opened">Open Query</option>
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
                                        <th onclick="changeSort('Transmission_Number');">Transmission# <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('Site_ID');">Site ID <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('Subject_ID');">Subject ID <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('visit_name');">Visit <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('visit_date');">Date <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('ImageModality');">Modality <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('is_read');">Processed Status <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('status');">Status <i class="fas fa-sort float-mrg"></i></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                        <tr>
                                            <td>
                                                <a href="{{route('transmissions-study-edit', encrypt($transmission->id))}}" id="view-transmission" class="" data-id="" title="Edit Transmission Details" data-url="" style="color: #17a2b8 !important">
                                                    {{$transmission->Transmission_Number}}
                                                </a>
                                            </td>
                                            <td>{{$transmission->Site_ID}}</td>
                                            <td>{{$transmission->Subject_ID}}</td>
                                            <td>{{$transmission->visit_name}}</td>
                                            <td>{{ date('d-M-Y', strtotime($transmission->visit_date))}}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{$transmission->ImageModality}}
                                                </span>
                                            </td>
                                            <td>
                                                @if($transmission->is_read == 'yes')
                                                    <span class="badge badge-danger">
                                                        Processed
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        Not Processed
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transmission->status == 'accepted')

                                                    <span class="badge badge-success">{{$transmission->status}}
                                                    </span>

                                                @else

                                                    <span class="badge badge-warning">{{$transmission->status}}
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
                                                        <a href="javascript:void(0)" data-id="{{$transmission->Transmission_Number}}" onclick="creatNewTransmissionsForQueries('{{$transmission->Transmission_Number}}');">
                                                            <i class="fas fa-question-circle" aria-hidden="true">
                                                            </i> Queries</a>
                                                    </span>
                                                        @endif
                                                    </div>
                                                    @php
                                                        $studyHaveTransimision = Modules\Queries\Entities\QueryNotification::where('transmission_number','=',$transmission->Transmission_Number)->where('notifications_status','=','open')->first();
                                                    @endphp
                                                    @if(null !== $studyHaveTransimision)
                                                        <div class="transmissionQuery">
                                                    <span class="ml-3" style="cursor: pointer;">
                                                        <i class="fas fa-question-circle showAlltransmissionQuery" data-id="{{$studyHaveTransimision->transmission_number}}"  style="margin-top: 3px;"></i></span>
                                                        </div>
                                                    @endif

                                            </div>
                                                 <!-- gear dropdown -->
                                            </td>
                                        </tr>

                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="9" style="text-align: center">No record found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{ $getTransmissions->appends(['trans_id' => \Request::get('trans_id'), 'subject_id' => \Request::get('subject_id'), 'visit_name' => \Request::get('visit_name'), 'visit_date' => \Request::get('visit_date'), 'imagine_modality' => \Request::get('imagine_modality'), 'is_read' => \Request::get('is_read'), 'status' => \Request::get('status') ])->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="transmissonQueryModal" aria-labelledby="exampleModalQueries" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header ">
                    <p class="modal-title">Transmisson Query</p>
                </div>
                <form id="queriesTransmissionForm" name="queriesTransmissionForm">
                    <div class="modal-body">
                        <div class="text-center">
                        <div class="spinner-border" id="defaultloader" role="status" style="display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                @csrf
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-2 col-form-label"> Sites :</label>
                                    <div class="col-sm-4">
                                        <select class="form-control sitesChange siteShowUp" name="site_name" id="site_name">
                                        </select>
                                    </div>
                                    <label for="Name" id="usersList" class="col-sm-2 col-form-label" style="display: none;"> Select Users :</label>
                                    <div class="col-sm-4 primaryList">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-2 col-form-label">CC:</label>
                                    @php
                                        $study_email = Modules\Admin\Entities\Preference::getPreference('STUDY_EMAIL');
                                        $study_cc_email = Modules\Admin\Entities\Preference::getPreference('STUDY_CC_EMAILS');
                                    @endphp
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="cc_email" id="cc_email" value="{{$study_email}},{{$study_cc_email}}">
                                        @error('cc_email')
                                        <div class="text-danger text-xl-center">{{$message}}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label for="Name" class="col-sm-2 col-form-label">Subject:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" name="query_subject" minlength="6" maxlength="70" id="query_subject" required>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <label for="Name" class="col-sm-2 col-form-label">Email Body</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control"  name="remarks"  id="remarks" required ></textarea>
                                    </div>
                                </div>

                                <div class="form-group row queryAttachments">
                                    <label for="Attachment" class="col-sm-2 col-form-label">Attachment:</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" name="query_file[]"  id="query_file" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="sendEmail-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" name="sendEmail" class="btn btn-outline-primary" id="sendEmail"><i class="fa fa-save"></i> Send Email</button>
                        </div>
                        @if(session('message'))
                            <div class="alert-success">{{session('message')}}</div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
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
            <form action="{{ route('transmissions-status') }}" method="POST" class="transmission-status-form">
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



    <!-- transmission query model start-->

    <div class="modal fade" tabindex="-1" role="dialog" id="transmissonQueryTableView" aria-labelledby="exampleModalQueries" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 850px;" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header ">
                    <p class="modal-title">Transmission Table View</p>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="example" class="display table dataTable table-striped table-bordered" >
                            <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Submitted By</th>
                                <th>Assigned To</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="queriesList"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="responseView" aria-labelledby="exampleModalQueries" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 1000px;">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header ">
                    <p class="modal-title">Response View</p>
                </div>
                <div class="modal-body ">
                    <form id="responseReplyViewForm" name="responseReplyViewForm">
                        <div class="tab-content clearfix">
                            @csrf
                            <div class="dataResponse"></div>
                            <div class="col-sm-12">
                                <div class="replyClickButtonResponse" style="text-align: right;">
                                    <span style="cursor: pointer;">
                                        <i class="fa fa-reply"></i> &nbsp; reply
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="addqueries-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit"  name="submit" class="btn btn-outline-primary" id="submit"><i class="fa fa-save"></i> Send</button>
                        </div>
                    </form>
                </div>
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
    // sorting gride
    function changeSort(field_name){
        var sort_by_field = $('#sort_by_field').val();
        if(sort_by_field =='' || sort_by_field =='ASC'){
           $('#sort_by_field').val('DESC');
           $('#sort_by_field_name').val(field_name);
        }else if(sort_by_field =='DESC'){
           $('#sort_by_field').val('ASC');
           $('#sort_by_field_name').val(field_name);
        }
        $('.filter-form').submit();
    }
    // Transmission Query  Work start
    function creatNewTransmissionsForQueries(transmission_Id)
    {
        $('#transmissonQueryModal').modal('show');
        loadSiteByTransmissionId(transmission_Id);
        //loadQueryByTransmissionId(transmission_Id);
    }


    $('.showAlltransmissionQuery').click(function () {
        var transmission_Id = $(this).attr('data-id');
        $('#transmissonQueryTableView').modal('show');
        loadQueryByTransmissionId(transmission_Id);
    });

    $('body').on('click', '.replyClickButtonResponse', function () {
        $('.replyResponseInput').css('display','');
        $('.responseAttachmentInput').css('display','');
        $('.replyClickButtonResponse').css('display','none');
    });


    function loadSiteByTransmissionId(trans_id) {
        $.ajax({
            url:"{{route('transmissions.getSiteByTransmissionId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                'trans_id'      :trans_id,
            },
            success: function(response)
            {
                $('.siteShowUp').html('');
                $('.siteShowUp').html(response);
            }
        });
    }

    function loadQueryByTransmissionId(transmission_Id) {
        $.ajax({
            url:"{{route('transmissions.getQueryByTransmissionId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'transmission_Id'      :transmission_Id,
            },
            success: function(response)
            {
                $('.queriesList').html('');
                $('.queriesList').html(response);
            }
        });
    }


    $('body').on('click', '.checkTransmissionResponse', function () {
        var id     = $(this).attr('data-id');
        $('#transmissonQueryTableView').modal('hide');
        $('#responseView').modal('show');
        showTransmissionResponse(id);
    });

    function showTransmissionResponse(id) {
        $.ajax({
            url:"{{route('transmissions.showResponseById')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'id'      :id,
            },
            success: function(response)
            {
                $('.dataResponse').html('');
                $('.dataResponse').html(response);
            }
        });
    }


    $(".sitesChange").change(function () {
        var selectedText = $(this).find("option:selected").text();
        var selectedValue = $(this).val();
        getSitesUsers(selectedValue);
    });

    function getSitesUsers(selectedValue) {
        var transmissionNumber = selectedValue;
        $.ajax({
            url:"{{route('transmissions.getAllPIBySiteId')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": 'POST',
                'transmissionNumber' :transmissionNumber,
            },
            success: function(response)
            {
                $('.primaryList').html('');
                $('#usersList').css('display','');
                $('.primaryList').html(response);
            }
        });
    }



    $("#responseReplyViewForm").on('submit', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        var transmission_number_response = $('#transmission_number_response').val();
        var notification_remarked_id     = $('#notification_remarked_id').val();
        var query_id_response            = $('#query_id_response').val();
        var cc_email_response            = $('#cc_email_response').val();
        var email_subject_response       = $('#email_subject_response').val();
        var study_id_response            = $('#study_id_response').val();
        var subject_id_response          = $('#subject_id_response').val();
        var vist_name_response           = $('#vist_name_response').val();
        var reply_response               = $('#reply_response').val();
        var study_short_name_response    = $('#study_short_name_response').val();
        var site_name_response           = $('#site_name_response').val();
        var mailToUserAddress            = $('#mailToUserAddress').val();

        var formDataResponse             = new FormData();
        formDataResponse.append('transmission_number_response', transmission_number_response);
        formDataResponse.append('notification_remarked_id', notification_remarked_id);
        formDataResponse.append('query_id_response', query_id_response);
        formDataResponse.append('cc_email_response', cc_email_response);
        formDataResponse.append('email_subject_response', email_subject_response);
        formDataResponse.append('study_id_response', study_id_response);
        formDataResponse.append('subject_id_response', subject_id_response);
        formDataResponse.append('vist_name_response', vist_name_response);
        formDataResponse.append('reply_response', reply_response);
        formDataResponse.append('study_short_name_response', study_short_name_response);
        formDataResponse.append('site_name_response', site_name_response);
        formDataResponse.append('mailToUserAddress', mailToUserAddress);
        // Attach file
        formDataResponse.append("responseAttachment", $("#attachment_R")[0].files[0]);

        $.ajax({

            url:"{{route('transmissions.queryTransmissionMailResponse')}}",
            type: "POST",
            data: formDataResponse,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response)
            {
                 var id = response.Status.parent_notification_id;
                 showTransmissionResponse(id);
                $('.replyClickButtonResponse').css('display','');
            }
        });
    });


    $("#queriesTransmissionForm").on('submit', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        var site_name           = $('#site_name option:selected').text();
        var users               = $('#users').val();
        var StudyI_ID           = $('#StudyI_ID').val();
        var remarks             = $('#remarks').val();
        var cc_email            = $('#cc_email').val();
        console.log(cc_email)
        console.log(typeof cc_email);
        var visitName           = $('#visitName').val();
        var Subject_ID          = $('#Subject_ID').val();
        var Transmission_Number = $('#Transmission_Number').val();
        var query_subject       = $('#query_subject').val();
        var studyShortName      = $('#studyShortName').val();

        var formData      = new FormData();
        formData.append('users', users);
        formData.append('site_name', site_name);
        formData.append('StudyI_ID', StudyI_ID);
        formData.append('remarks', remarks);
        formData.append('cc_email', cc_email);
        formData.append('visitName', visitName);
        formData.append('Subject_ID', Subject_ID);
        formData.append('Transmission_Number', Transmission_Number);
        formData.append('query_subject', query_subject);
        formData.append('studyShortName', studyShortName);
        // Attach file

        //formData.append('query_file', $('input[type=file]')[0].files[0]);

        var files =$('input[type=file]')[0].files;

        for(var i=0;i<files.length;i++){
            formData.append("query_file[]", files[i], files[i]['name']);
        }

        $('#defaultloader').css('display','');
        $.ajax({

            url:"{{route('transmissions.queryTransmissionMail')}}",
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response)
            {
                console.log(response);
                $('#transmissonQueryModal').modal('hide');
                $("#queriesTransmissionForm")[0].reset();
                $('#defaultloader').css('display','');
                location.reload();
            }
        });
    });

    $('#transmissonQueryModal').on('hidden.bs.modal', function () {
        //$(this).find('form').trigger('reset');
        location.reload();
    })

    // Transmission End Work

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

    function transmissionStatus(transmissionId, transmissionStatus) {

        // assign transmission id
        $('.transmission-status-form').find($('input[name="hidden_transmission_id"]')).val(transmissionId);
        // assign status
        $('.transmission-status-form').find($('select[name="status"]')).val(transmissionStatus);
        $('.transmission-status-form').find($('textarea[name="comment"]')).val('');
        $('#transmission-status-modal').modal('show');
    }

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
<script type="text/javascript">

    $('#cc_email').tagsInput();
</script>

@endsection




