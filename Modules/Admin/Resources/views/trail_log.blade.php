@extends ('layouts.home')

@section('title')
    <title> Sites | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')
    .table{table-layout: fixed;}
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                      
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            {{--
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>
                                <tr>
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
                                        <td>{{$log->user_name}}</td>
                                        <td>{{$log->event_type}}</td>
                                       
                                        <td onclick="getEventDetails('{{$log->event_details}}');">{{$log->event_message}}</td>
                                        
                                        <td>{{$log->ip_address}}</td>
                                        <td>{{$log->created_at}}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                
                                </tbody>
                            </table>
                            --}}

                            {{--
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>
                                <tr>
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
                                    <tr data-toggle="collapse" data-target="#accordion-{{$log->id}}" class="clickable">
                                        <td>{{$log->user_name}}</td>
                                        <td>{{$log->event_type}}</td>
                                       
                                        <td>{{$log->event_message}}</td>
                                        
                                        <td>{{$log->ip_address}}</td>
                                        <td>{{$log->created_at}}</td>
                                    </tr>

                                    <!-- accordian section -->
                                    <tr>
                                        <td colspan="5">
                                            <div id="accordion-{{$log->id}}" class="collapse">
                                                <table class="table" style="width: 100%">
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
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- accordian section ends -->
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                
                                </tbody>
                            </table>
                            --}}

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
                                            <i class="fas  h5 mr-2 fa-chevron-circle-down" data-toggle="collapse" data-target=".row-{{$log->id}}" style="font-size: 20px; color: #1e3d73;"></i>
                                          </div>
                                        </td>
                                        <td>{{$log->user_name}}</td>
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

                          {{$getLogs->links()}} 
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

    <!-- Modal -->
        <div class="modal fade" id="event_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true" style="padding-right: 17px;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="event_detail_title">Event Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Table -->
                        <table class="table event_detail_table">
                            <thead>
                                <tr class="bg-secondary" style="color: #fff;">
                                    <th scope="col">Name</th>
                                    <th scope="col">Value</th>
                                </tr>
                            </thead>
                            <tbody class="event_detail_tbody">
                               
                                
                            </tbody>
                        </table>
                        <!-- Table ends -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Modal ends -->
   
@endsection
@section('script')

<script type="text/javascript">
    function getEventDetails(data) {
        // show modal
        $('#event_detail').modal('show');
        // remove row
        $('.event_detail_tbody tr').remove();
        var eventDetails = JSON.parse(data);
        // loop through data
        for (var key in eventDetails) {
            if (key == "created_at" || key == "updated_at") {
                var d = new Date(eventDetails[key]);
                var curr_day = d.getDate();
                var curr_month = d.getMonth();
                var curr_year = d.getFullYear();

                var curr_hour = d.getHours();
                var curr_min = d.getMinutes();
                var curr_sec = d.getSeconds();

                $('.event_detail_tbody').append('<tr>\
                            <td>'+key+'</td>\
                            <td>'+curr_year+'-'+curr_month+'-'+curr_day+' '+curr_hour+':'+curr_min+':'+curr_sec+'</td>\
                            </tr>');

            } else {
                var value = eventDetails[key] == null ? '' : eventDetails[key];

                $('.event_detail_tbody').append('<tr>\
                            <td>'+key+'</td>\
                            <td>'+value+'</td>\
                            </tr>');

            }
           
        }
        
    }
</script>
@endsection




