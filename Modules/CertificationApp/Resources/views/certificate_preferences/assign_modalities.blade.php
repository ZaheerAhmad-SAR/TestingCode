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

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid black 1px;
            outline: 0;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #485e9029 !important; 
            border-radius: 4px;
            cursor: text;
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

                        <button type="button" class="btn btn-primary assign-work">Assign Modality</button>

                        @if (!$getModalities->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $getModalities->count().' out of '.$getModalities->total() }}
                        </span>
                        @endif

                    </div>

                     <hr>
                   
                    <div class="card-body">

                        <div class="table-responsive">
                        <form method="POST" action="{{ route('preferences.save-assign-modality', request()->study_id) }}" class="modality-form">
                            @csrf
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>

                                    <tr class="table-secondary">
                                        <th>Select All 
                                            <input type="checkbox" class="select_all" name="select_all" id="select_all">
                                        </th>
                                        <th>Parent Modality</th>
                                        <th>Child Modality</th>
                                        <th>Assign By</th>
                                        <th>Assign Status</th>
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

    $('.assign-work').click(function() {
        // any checkbox is checked
        if ($(".check_modality:checked").length > 0) {
            
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




