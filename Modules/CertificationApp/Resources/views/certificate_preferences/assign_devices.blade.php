@extends ('layouts.home')

@section('title')
    <title> Assign Device | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')

    <style type="text/css">

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

        legend {
          /*background-color: gray;
          color: white;*/
          padding: 5px 10px;
        }

    </style>
    

    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    <!-- sweet alerts -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/sweetalert/sweetalert.css') }}"/>

@endsection

@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Assign Device</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Assign Device</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">

            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="form-group col-md-12 mt-3">
                         
                        <button type="button" class="btn btn-primary assign-device" data-url="{{ route('preferences.save-assign-device', request()->study_id) }}">Assign Device</button>
                     
                        <button type="button" class="btn btn-primary remove-device" data-url="{{ route('preferences.remove-assign-device', request()->study_id) }}">Remove Device</button>
                    
                        @if (!$getDevices->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $getDevices->count().' out of '.$getDevices->total() }}
                        </span>
                        @endif
                   
                    </div>

                     <hr>

                     <form action="{{route('preferences.assign-device', request()->study_id)}}" method="get" class="form-1 filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <input type="hidden" name="form_1" value="1" class="form-control">

                            <div class="form-group col-md-4">
                                <label for="inputState">Device Name</label>
                                <input type="text" name="device_name" value="{{ request()->device_name }}" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputState">Device Manufracturer</label>
                                <input type="text" name="device_manufacturer" value="{{ request()->device_manufacturer }}" class="form-control">
                            </div>


                            <div class="form-group col-md-4 mt-4">        
                               <!--  <button type="button" class="btn btn-primary reset-filter-1">Reset</button> -->
                                <button type="submit" class="btn btn-primary btn-lng">Filter Records</button>
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>

                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                   
                    <div class="card-body">

                        <div class="table-responsive">
                        <form method="POST" action="" class="device-form">
                            @csrf
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>

                                    <tr class="table-secondary">
                                        <th>Select All 
                                            <input type="checkbox" class="select_all" name="select_all" id="select_all">
                                        </th>
                                        <th>Device Name</th>
                                        <th>Device Manufacturer</th>
                                        <th>Assigned By</th>
                                        <th>Assigned Status</th>
                                    </tr>

                                </thead>

                                <tbody>
                                    @if(!$getDevices->isEmpty())

                                        @foreach($getDevices as $key => $device)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="check_device" name="check_device[{{ $device->id }}]" >
                                            </td>

                                            <td>
                                                <input type="hidden" name="device_name[]" value="{{ $device->id }}">
                                                {{ $device->device_name }} 
                                            </td>

                                            <td> 
                                                <input type="hidden" name="device_manufacturer[]" value="{{ $device->id }}">
                                                {{ $device->device_manufacturer }} 
                                            </td> 

                                           <td>
                                                {!! \Modules\CertificationApp\Entities\StudyDevice::checkAssignedUser($device->id, decrypt(request()->study_id)) !!}
                                            </td> 

                                            <td> 
                                                {!! \Modules\CertificationApp\Entities\StudyDevice::checkAssignedDevices($device->id, decrypt(request()->study_id)) !!}
                                            </td> 
                                           
                                        </tr>
                                        @endforeach

                                    @else
                                    <tr>
                                        <td colspan="5" style="text-align: center;"> No record found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{ $getDevices->links() }}

                        </form>
                        <!-- form ends -->
                        </div>
                        <!-- table responsive -->
                    </div>
                </div>
                <!-- Card ends -->
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

<!-- sweet alert -->
<script src="{{ asset('public/dist/vendors/sweetalert/sweetalert.min.js') }}"></script>       
<!-- <script src="{{ asset('public/dist/js/sweetalert.script.js') }}"></script> -->

<script type="text/javascript">

    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change");
        // submit the filter form
        window.location.reload();
    });

    $('.assign-device').click(function() {
        // any checkbox is checked
        if ($(".check_device:checked").length > 0) {
            
            // assign url to action
            $('.device-form').attr('action', $(this).attr('data-url'))
            // submit form
            $('.device-form').submit();

        } else {
           // alert msg
           alert('No device selected.');
        }
       
    });

    $('.remove-device').click(function() {
        // any checkbox is checked
        if ($(".check_device:checked").length > 0) {
            
            // assign url to action
            $('.device-form').attr('action', $(this).attr('data-url'))
            // submit form
            $('.device-form').submit();

        } else {
           // alert msg
           alert('No device selected.');
        }
       
    });

    // select all change function
    $('.select_all').change(function(){
        
        if($(this).is(":checked")) {
            // check all checkboxes
            $(".check_device").each(function() {
                $(this).prop('checked', true);
            });

        } else {

            // un-check all checkboxes
            $(".check_device").each(function() {
                $(this).prop('checked', false);
            });

        }
    });

    // select/ unselect select all on checkbox event
    $('.check_device').change(function () {

        if ($('.check_device:checked').length == $('.check_device').length) {
            // CHECK SELECT ALL
            $('.select_all').prop('checked',true);

        } else {
            // uncheck select all
            $('.select_all').prop('checked',false);

        }
    });

   
</script>
@endsection




