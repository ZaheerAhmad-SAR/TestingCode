@extends ('layouts.home')
@section('title')
<title> Lock Forms | {{ config('app.name', 'Laravel') }}</title>
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
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Lock Forms</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Lock Forms</li>
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
                    <button type="button" class="btn btn-primary lock-data" data-url="{{ route('studySite.update') }}">Lock Forms</button>
                    <button type="button" class="btn btn-primary unlock-data" data-url="{{route('studySite.removeAssignedSites')}}">Unlock forms</button>
                    @if (!$getCompletedForms->isEmpty())
                    <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                        {{ $getCompletedForms->count().' out of '.$getCompletedForms->total() }}
                    </span>
                    @endif
                </div>
                <hr>
            
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="laravel_crud">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="width: 11%;"> <input type="checkbox" class="select_all" name="select_all" id="select_all"> &nbsp; Select All
                                </th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$getCompletedForms->isEmpty())
                            @foreach($getCompletedForms as $key => $completedForms)
                            <tr>
                                <td>
                                    <input type="checkbox" class="check_forms" name="completed_forms[{{ $completedForms->id}}]" >
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                               
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" style="text-align: center;"> No record found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
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
<script src="{{ asset("dist/vendors/typeahead/handlebars-v4.5.3.js") }}"></script>
<script src="{{ asset("dist/vendors/typeahead/typeahead.bundle.js") }}"></script>
<script src="{{ asset("dist/js/typeahead.script.js") }}"></script>
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
    
</script>

@endsection
