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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Assign Modality</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Assign Modality</li>
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
                        <button type="button" class="btn btn-primary assign-modality" data-url="{{ route('preferences.save-assign-modality', request()->study_id) }}">Assign Modality</button>

                        <button type="button" class="btn btn-primary remove-modality" data-url="{{ route('preferences.remove-assign-modality', request()->study_id) }}">Remove Modality</button>

                        @if (!$getModalities->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $getModalities->count().' out of '.$getModalities->total() }}
                        </span>
                        @endif
                    </div>

                     <hr>

                     <form action="{{route('preferences.assign-modality', request()->study_id)}}" method="get" class="form-1 filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <input type="hidden" name="form_1" value="1" class="form-control">

                            <div class="form-group col-md-5">
                                <label for="inputState">Parent Modality</label>
                                <select id="parent_modility" name="parent_modility" class="form-control filter-form-data">
                                    <option value="">All Parent Modality</option>
                                    @foreach($getParentModalities as $parentModality)
                                    <option @if(request()->parent_modility == $parentModality->id) selected @endif value="{{ $parentModality->id }}">{{ $parentModality->modility_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-5">
                                <label for="inputState">Child Modality</label>
                                <select id="child_modility" name="child_modility" class="form-control filter-form-data">
                                    <option value="">All Child Modality</option>
                                    @foreach($getChildModalities as $childModality)
                                    <option @if(request()->child_modility == $childModality->id) selected @endif value="{{ $childModality->id }}">{{ $childModality->modility_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2 mt-4">        
                               <!--  <button type="button" class="btn btn-primary reset-filter-1">Reset</button> -->
                                <button type="submit" class="btn btn-primary btn-lng">Filter Records</button>
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>

                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                   
                    <div class="card-body">

                        <div class="table-responsive">
                        <form method="POST" action="" class="modality-form">
                            @csrf
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>

                                    <tr class="table-secondary">
                                        <th>Select All 
                                            <input type="checkbox" class="select_all" name="select_all" id="select_all">
                                        </th>
                                        <th>Parent Modality</th>
                                        <th>Child Modality</th>
                                        <th>Assigned By</th>
                                        <th>Assigned Status</th>
                                    </tr>

                                </thead>

                                <tbody>
                                    @if(!$getModalities->isEmpty())

                                        @foreach($getModalities as $key => $modality)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="check_modality" name="check_modality[{{ $modality->parent_modility_id .'_'. $modality->child_modility_id}}]" >
                                            </td>

                                            <td>
                                                <input type="hidden" name="parent_modility_id[]" value="{{ $modality->parent_modility_id }}">
                                                {{$modality->parent_modility_name}} 
                                            </td>

                                            <td> 
                                                <input type="hidden" name="child_modility_id[]" value="{{ $modality->child_modility_id }}">
                                                {{$modality->child_modility_name}} 
                                            </td> 

                                            <td>
                                                {!! \Modules\CertificationApp\Entities\StudyModility::checkAssignedUser( $modality->parent_modility_id ,  $modality->child_modility_id , request()->study_id) !!}
                                            </td> 

                                            <td> 
                                                {!! \Modules\CertificationApp\Entities\StudyModility::checkAssignedModilities( $modality->parent_modility_id ,  $modality->child_modility_id , request()->study_id) !!}
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

                            {{ $getModalities->links() }}

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

    $('select[name="parent_modility"]').select2();
    $('select[name="child_modility"]').select2();

    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change");
        // submit the filter form
        window.location.reload();
    });

    $('.assign-modality').click(function() {
        // any checkbox is checked
        if ($(".check_modality:checked").length > 0) {
            
            // assign url to action
            $('.modality-form').attr('action', $(this).attr('data-url'))
            // submit form
            $('.modality-form').submit();

        } else {
           // alert msg
           alert('No modality selected.');
        }
       
    });

    $('.remove-modality').click(function() {
        // any checkbox is checked
        if ($(".check_modality:checked").length > 0) {
            
            // assign url to action
            $('.modality-form').attr('action', $(this).attr('data-url'))
            // submit form
            $('.modality-form').submit();

        } else {
           // alert msg
           alert('No modality selected.');
        }
       
    });

    // select all change function
    $('.select_all').change(function(){
        
        if($(this).is(":checked")) {
            // check all checkboxes
            $(".check_modality").each(function() {
                $(this).prop('checked', true);
            });

        } else {

            // un-check all checkboxes
            $(".check_modality").each(function() {
                $(this).prop('checked', false);
            });

        }
    });

    // select/ unselect select all on checkbox event
    $('.check_modality').change(function () {

        if ($('.check_modality:checked').length == $('.check_modality').length) {
            // CHECK SELECT ALL
            $('.select_all').prop('checked',true);

        } else {
            // uncheck select all
            $('.select_all').prop('checked',false);

        }
    });

   
</script>
@endsection




