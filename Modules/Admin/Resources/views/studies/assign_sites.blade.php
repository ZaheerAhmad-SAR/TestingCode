@extends ('layouts.home')

@section('title')
    <title> Assign Modality | {{ config('app.name', 'Laravel') }}</title>
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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Assign Sites</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Assign Sites</li>
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
                        <button type="button" class="btn btn-primary assign-sites" data-url="{{ route('studySite.update') }}">Assign Sites</button>

                            <button type="button" class="btn btn-primary remove-sites" data-url="{{route('studySite.removeAssignedSites')}}">Remove Sites</button>

                        @if (!$sites->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $sites->count().' out of '.$sites->total() }}
                        </span>
                        @endif
                    </div>

                     <hr>
                            @php
                                $current_study =  \Session::get('current_study');
                            @endphp
                     <form action="{{route('studySite.update')}}" method="get" class="form-1 filter-form">

                        <div class="form-row" style="padding: 10px;">

                            <input type="hidden" name="form_1" value="1" class="form-control">

                            <div class="form-group col-md-5">
                                <label for="inputState">Site Code</label>
                                <select id="site_code" name="site_code" class="form-control filter-form-data">
                                    <option value="">All Site Code</option>
                                    @foreach($sites as $site)
                                    <option @if(request()->site_code == $site->id) selected @endif value="{{ $site->id }}">{{ $site->site_code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="inputState">Site Name</label>
                                <select id="site_name" name="site_name" class="form-control filter-form-data">
                                    <option value="">All Site Name</option>
                                    @foreach($sites as $site)
                                    <option @if(request()->site_name == $site->id) selected @endif value="{{ $site->id }}">{{ $site->site_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="inputState">Site Address</label>
                                <select id="site_address" name="site_address" class="form-control filter-form-data">
                                    <option value="">All Site Address</option>
                                    @php
                                        $addressSites = Modules\Admin\Entities\Site::select('id','site_address')->groupBy('site_address')->get();
                                    @endphp
                                    @foreach($addressSites as $addressSite)
                                    <option @if(request()->site_address == $addressSite->id) selected @endif value="{{ $addressSite->id }}">{{ $addressSite->site_address }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="inputState">Site city</label>
                                <select id="site_city" name="site_city" class="form-control filter-form-data">
                                    <option value="">All Site City</option>
                                    @php
                                        $citySites = Modules\Admin\Entities\Site::select('id','site_city')->groupBy('site_city')->get();
                                    @endphp
                                    @foreach($citySites as $citySite)
                                    <option @if(request()->site_city == $citySite->id) selected @endif value="{{ $citySite->id }}">{{ $citySite->site_city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="inputState">Site State</label>
                                <select id="site_state" name="site_state" class="form-control filter-form-data">
                                    <option value="">All Site State</option>
                                    @php
                                        $stateSites = Modules\Admin\Entities\Site::select('id','site_state')->groupBy('site_state')->get();
                                    @endphp
                                    @foreach($stateSites as $stateSite)
                                    <option @if(request()->site_state == $stateSite->id) selected @endif value="{{ $stateSite->id }}">{{ $stateSite->site_state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="inputState">Site Country</label>
                                <select id="site_country" name="site_country" class="form-control filter-form-data">
                                    <option value="">All Site Country</option>
                                    @php
                                        $countrySites = Modules\Admin\Entities\Site::select('id','site_country')->groupBy('site_country')->get();
                                    @endphp
                                    @foreach($countrySites as $countrySite)
                                    <option @if(request()->site_country == $countrySite->id) selected @endif value="{{ $countrySite->id }}">{{ $countrySite->site_country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="inputState">Text </label>
                                <div id="bloodhound">
                                    <input class="typeahead form-control rounded" type="text" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-md-2 mt-4">
                               <!--  <button type="button" class="btn btn-primary reset-filter-1">Reset</button> -->
                                <button type="submit" class="btn btn-primary btn-lng"> <i class="fas fa-filter" aria-hidden="true"></i>Filter</button>
                                <button type="button" class="btn btn-outline-warning reset-filter"><i class="fas fa-undo-alt" aria-hidden="true"></i>Reset</button>

                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
{{--                    @if(Session::has('message'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ Session::get('message') }} --}}
{{--                        </div>--}}
{{--                    @endif--}}
                    <div class="card-body">

                        <div class="table-responsive">
                            <form method="POST" action="" class="assign-site-form">
                                @csrf
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
                                    @if(!$sites->isEmpty())

                                        @foreach($sites as $key => $site)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="check_sites" name="check_sites[{{ $site->id}}]" >
                                                </td>
                                                <td>{{ucfirst($site->site_code)}}</td>
                                                <td>{{ucfirst($site->site_name)}}</td>
                                                <td>{{ucfirst($site->site_address)}}</td>
                                                <td>{{ucfirst($site->site_city)}}</td>
                                                <td>{{ucfirst($site->site_state)}}</td>
                                                <td>{{ucfirst($site->site_country)}}</td>
                                                <td>
                                                    {!! \Modules\Admin\Entities\StudySite::checkAssignedStudySite(\Session::get('current_study'), $site->id) !!}
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

                                {{ $sites->links() }}

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

    $('select[name="site_code"]').select2();
    $('select[name="site_name"]').select2();
    $('select[name="site_address"]').select2();
    $('select[name="site_city"]').select2();
    $('select[name="site_state"]').select2();
    $('select[name="site_country"]').select2();

    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change");
        // submit the filter form
        window.location.reload();
    });

    $('.assign-sites').click(function() {
        // any checkbox is checked
        if ($(".check_sites:checked").length > 0) {

            // assign url to action
            $('.assign-site-form').attr('action', $(this).attr('data-url'))
            // submit form
            $('.assign-site-form').submit();

        } else {
           // alert msg
           alert('No modality selected.');
        }

    });

    $('.remove-sites').click(function() {
        // any checkbox is checked
        if ($(".check_sites:checked").length > 0) {

            // assign url to action
            $('.assign-site-form').attr('action', $(this).attr('data-url'))
            // submit form
            $('.assign-site-form').submit();

        } else {
           // alert msg
           alert('No modality selected.');
        }

    });

    // select all change function
    $('.select_all').change(function(){

        if($(this).is(":checked")) {
            // check all checkboxes
            $(".check_sites").each(function() {
                $(this).prop('checked', true);
            });

        } else {

            // un-check all checkboxes
            $(".check_sites").each(function() {
                $(this).prop('checked', false);
            });

        }
    });

    // select/ unselect select all on checkbox event
    $('.check_sites').change(function () {

        if ($('.check_sites:checked').length == $('.check_modality').length) {
            // CHECK SELECT ALL
            $('.select_all').prop('checked',true);

        } else {
            // uncheck select all
            $('.select_all').prop('checked',false);

        }
    });


</script>
@endsection




