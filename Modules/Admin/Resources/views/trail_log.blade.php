@extends ('layouts.home')

@section('title')
    <title> Sites | {{ config('app.name', 'Laravel') }}</title>
@stop


@section('content')

    <style type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
        .required:after {
            content:" *";
            color: red;
        }

        .pac-container {
            z-index: 10000 !important;
        }
    </style>
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
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Add</th>
                                    <th>Update</th>
                                    <th>Event Note</th>
                                    <th>IP Address</th>
                                    <th>Study ID</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(!$getLogs->isEmpty())
                                    @foreach($getLogs as $log)
                                    <tr>
                                        <td>{{$log->user_name}}</td>
                                        <td>{{$log->event_add}}</td>
                                        <td>{{$log->event_update}}</td>
                                        <td>{{$log->event_message}}</td>
                                        <td>{{$log->ip_address}}</td>
                                        <td>{{$log->study_id}}</td>
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
                          {{$getLogs->links()}} 
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>

   
@endsection
@section('script')

</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEELbGoxVU_nvp6ayr2roHHnjN3hM_uec&libraries=places&callback=initAutocomplete"
            defer></script>
@endsection




