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
                    <button type="button" class="btn btn-primary lock-forms" data-url="{{ route('subjectFormLoader.lock-from-data') }}">Lock Forms</button>
                    <button type="button" class="btn btn-primary unlock-forms" data-url="{{route('subjectFormLoader.unlock-form-data')}}">Unlock Forms</button>
                    @if (!$getPhaseModalities->isEmpty())
                    <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                        {{ $getPhaseModalities->count().' out of '.$getPhaseModalities->total() }}
                    </span>
                    @endif
                </div>
                
                     <hr>

                    <form action="{{route('subjectFormLoader.lock-data')}}" method="get" class="form-1 filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <input type="hidden" name="form_1" value="1" class="form-control">

                            <div class="form-group col-md-4">
                                <label for="inputState">Phase</label>
                                <select id="phase" name="phase" class="form-control filter-form-data">
                                    <option value="">All Phases</option>
                                    @foreach($filterPhases as $phase)
                                    <option @if(request()->phase == $phase->id) selected @endif value="{{ $phase->id }}">{{ $phase->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputState">Modality</label>
                                <select id="modality" name="modality" class="form-control filter-form-data">
                                    <option value="">All Modalities</option>
                                    @foreach($filetrModalities as $modality)
                                    <option @if(request()->modality == $modality->id) selected @endif value="{{ $modality->id }}">{{ $modality->modility_name }}</option>
                                    @endforeach
                                </select>
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
                        <form method="POST" action="" class="modality-form">
                        @csrf
                            <table class="table table-bordered" id="laravel_crud">
                                <thead>
                                    <tr class="table-secondary">
                                        <th style="width: 11%;"> <input type="checkbox" class="select_all" name="select_all" id="select_all"> &nbsp; Select All
                                    </th>
                                    <th>Subject</th>
                                    <th>Phase Name</th>
                                    <th>Modality Name</th>
                                    <th>Modality Abbreviation</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$getPhaseModalities->isEmpty())
                                @foreach($getPhaseModalities as $key => $phase)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="check_modality" name="check_modality[{{$phase->phase_id.'__/__'.$phase->modility_id}}]" value="{{$phase->phase_id.'__/__'.$phase->modility_id}}">
                                    </td>
                                    <td>{{$phase->subject_id}}</td>
                                    <td>{{$phase->phase_name}}</td>
                                    <td>{{$phase->modility_name}}</td>
                                    <td>{{$phase->modility_abbreviation}}</td>
                                    <td>
                                        @php
                                            $getLockFormStatusArray = [
                                                'study_id' => \Session::get('current_study'),
                                                'study_structures_id' => $phase->phase_id,
                                                'modility_id' => $phase->modility_id,
                                            ];

                                            $lockFromStatus = 0;
                                            $lockFormStatusObj = \Modules\FormSubmission\Entities\FormStatus::getFormStatusObj($getLockFormStatusArray);
                                            if(null !== $lockFormStatusObj) {
                                                $lockFromStatus = $lockFormStatusObj->is_data_locked == 1 ? $lockFormStatusObj->is_data_locked : 0;
                                            }

                                            if ($lockFromStatus == 0) {
                                                echo "<span class='btn btn-primary'>N/A</span>";
                                            } else {
                                                echo "<span class='btn btn-danger'>Locked</span>";
                                            }
                                        @endphp
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
                        {{ $getPhaseModalities->appends(['phase' => \Request::get('phase'), 'modality' => \Request::get('modality')])->links() }}
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

    $('select[name="modality"]').select2();
    $('select[name="phase"]').select2();

    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change");
        // submit the filter form
        window.location.reload();
    });

    $('.lock-forms').click(function() {
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

    $('.unlock-forms').click(function() {
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
