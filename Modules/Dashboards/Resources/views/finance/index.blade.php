@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Finance Indexes</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Finance</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('finance.index')}}" method="get" class="filter-form">
                            <div class="form-row" style="padding: 10px;">
                                <input type="hidden" name="sort_by_field" id="sort_by_field" value="{{ request()->sort_by_field }}">
                                <input type="hidden" name="sort_by_field_name" id="sort_by_field_name" value="{{ request()->sort_by_field_name }}">
                               <div class="form-group col-md-3">
                                    <label for="inputState"> Study </label>
                                    <select id="study_id" name="study_id" class="form-control filter-form-data">
                                        <option value="">All Studies</option>
                                        @foreach($getStudies as $study)
                                        <option @if ($study->id == request()->study_id) selected @endif value="{{ $study->id}}"> {{ $study->study_short_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputState"> Users </label>
                                    <select id="user_id" name="user_id" class="form-control filter-form-data">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                        <option @if ($user->id == request()->user_id) selected @endif value="{{ $user->id}}"> {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="dt">Date Range</label>
                                    <input type="text" name="visit_date" id="visit_date" class="form-control visit_date filter-form-data" value="{{ request()->visit_date }}">
                                </div>
                                <div class="form-group col-md-3 mt-4" style="text-align: right;">
                                    <button type="button" class="btn  btn-outline-warning reset-filter"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                                     <button type="submit" class="btn btn-primary btn-lng"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                                </div>
                            </div>
                            <!-- row ends -->
                        </form>
                    </div>    
                </div>
            </div>
        </div>
@php
    if(request()->visit_date != '') {
       $visitDate = explode('-', request()->visit_date);
       $from = \Carbon\Carbon::parse($visitDate[0]); // 2018-09-29 00:00:00
       $to = \Carbon\Carbon::parse($visitDate[1]); // 2018-09-29 23:59:59
    }
@endphp
        <div class="row">
            <div class="col-12  col-lg-12 mt-3">
                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h6 class="card-title"> Completed Visits </h6>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table font-w-600 mb-0">
                            <thead>
                                <tr>
                                    <th>Expand</th>
                                    <th>User Name</th>
                                    <th>Study Name</th>
                                    <th>Role</th>
                                    <th>QC</th>
                                    <th>Eligibility</th>
                                    <th>Grading</th>
                                    <th>Adjudication</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($study_users))
                                    {{-- {{dd($study_users)}} --}}
                                    @foreach($study_users as $record)
                                    <tr>
                                        <td style="text-align: left;width:10%">
                                          <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$record->id}}" style="font-size: 20px; color: #1e3d73;"></i>
                                          </div>
                                        </td>
                                        <td>{{$record->user->name}}</td>
                                        <td>{{$record->study->study_short_name}}</td>
                                        <td>{{$record->role->name}}</td>
                                        <td>
                                            @php
                                                $total_qc_count = Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'complete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ));
                                                if(request()->visit_date != ''){
                                                    $total_qc_count = $total_qc_count->whereDate('created_at', '>=', $from);
                                                    $total_qc_count = $total_qc_count->whereDate('created_at', '<=', $to);
                                                }
                                                $total_qc_count = $total_qc_count->count();
                                            @endphp
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ $total_qc_count }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $total_qc_eli = Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'complete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ));
                                                if(request()->visit_date != ''){
                                                    $total_qc_eli = $total_qc_eli->whereDate('created_at', '>=', $from);
                                                    $total_qc_eli = $total_qc_eli->whereDate('created_at', '<=', $to);
                                                }
                                                $total_qc_eli = $total_qc_eli->count();
                                            @endphp
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ $total_qc_eli }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $total_qc_grading = Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ));
                                                if(request()->visit_date != ''){
                                                    $total_qc_grading = $total_qc_grading->whereDate('created_at', '>=', $from);
                                                    $total_qc_grading = $total_qc_grading->whereDate('created_at', '<=', $to);
                                                }
                                                $total_qc_grading = $total_qc_grading->count();
                                            @endphp
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ $total_qc_grading }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $total_qc_adj = Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ));
                                                if(request()->visit_date != ''){
                                                    $total_qc_adj = $total_qc_adj->whereDate('created_at', '>=', $from);
                                                    $total_qc_adj = $total_qc_adj->whereDate('created_at', '<=', $to);
                                                }
                                                $total_qc_adj = $total_qc_adj->count();
                                            @endphp
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'complete','study_id' =>$record->study_id,'form_adjudicated_by_id' =>$record->user_id ))->count() }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="collapse row-{{$record->id}}">
                                        <td colspan="8">
                                           <table class="table table-hover" style="width: 100%">
                                                <thead class="table-info">
                                                    <th>Modality Short Name</th>
                                                    <th>Completed QC</th>
                                                    <th>Completed Eligibility</th>
                                                    <th>Completed Grading</th>
                                                    <th>Completed Adjudication</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($getModalities as $key => $value)
                                                    @php 
                                                    $where_total_qc = array(
                                                        'form_type_id' => 1,
                                                        'form_status' => 'complete',
                                                        'modility_id' => $value->id,
                                                        'form_filled_by_user_id' => $record->user_id,
                                                        'study_id' => $record->study_id
                                                    );
                                                    $where_total_eligibility = array(
                                                        'form_type_id' => 3,
                                                        'form_status' => 'complete',
                                                        'modility_id' => $value->id,
                                                        'form_filled_by_user_id' => $record->user_id,
                                                        'study_id' => $record->study_id
                                                    );
                                                    $where_total_grading = array(
                                                        'form_type_id' => 2,
                                                        'form_status' => 'complete',
                                                        'modility_id' => $value->id,
                                                        'form_filled_by_user_id' => $record->user_id,
                                                        'study_id' => $record->study_id
                                                    );
                                                    $where_total_adj = array(
                                                        'form_type_id' => 2,
                                                        'adjudication_status' => 'complete',
                                                        'modility_id' => $value->id,
                                                        'form_adjudicated_by_id' => $record->user_id,
                                                        'study_id' => $record->study_id
                                                    );

                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <span data-toggle="tooltip" data-placement="top" title="{{$value->modility_name}}">
                                                                {{ $value->modility_abbreviation }}
                                                            </span>
                                                        </td>
                                                        {{-- count of QC --}}
                                                        @php
                                                        // count of qc
                                                           $qc_count = Modules\FormSubmission\Entities\FormStatus::where($where_total_qc);
                                                           if(request()->visit_date != ''){
                                                                $qc_count = $qc_count->whereDate('created_at', '>=', $from);
                                                                $qc_count = $qc_count->whereDate('created_at', '<=', $to);
                                                           }
                                                           $qc_count = $qc_count->count();
                                                        // count of eligibility
                                                           $eligibility_count = Modules\FormSubmission\Entities\FormStatus::where($where_total_eligibility);
                                                           if(request()->visit_date != ''){
                                                                $eligibility_count = $eligibility_count->whereDate('created_at', '>=', $from);
                                                                $eligibility_count = $eligibility_count->whereDate('created_at', '<=', $to);
                                                           }
                                                           $eligibility_count = $eligibility_count->count();
                                                        // count of Grading
                                                           $grading_count = Modules\FormSubmission\Entities\FormStatus::where($where_total_grading);
                                                           if(request()->visit_date != ''){
                                                                $grading_count = $grading_count->whereDate('created_at', '>=', $from);
                                                                $grading_count = $grading_count->whereDate('created_at', '<=', $to);
                                                           }
                                                           $grading_count = $grading_count->count();
                                                        // count of qc
                                                           $adj_count = Modules\FormSubmission\Entities\AdjudicationFormStatus::where($where_total_adj);
                                                           if(request()->visit_date != ''){
                                                                $adj_count = $adj_count->whereDate('created_at', '>=', $from);
                                                                $adj_count = $adj_count->whereDate('created_at', '<=', $to);
                                                           }
                                                           $adj_count = $adj_count->count();
                                                        
                                                        @endphp
                                                        <th>{{ $qc_count }}</th>
                                                        {{-- count of Eligibility --}}
                                                        <th>{{ $eligibility_count }} </th>
                                                        {{-- count of Grading --}}
                                                        <th>{{ $grading_count }}
                                                        </th>
                                                        {{-- count of Adjudication --}}
                                                        <th>{{ $adj_count }}</th>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="5" style="text-align: center;font-weight: bold;font-size: 16px;color: red;">
                                       Please Select Filters
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $study_users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css" rel="stylesheet">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    <!-- select2-->
@stop
@section('script')
    <!-- date range picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- select2 -->
    <script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
    <script type="text/javascript">
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
    </script> 
    <script type="text/javascript">

        $('.detail-icon').click(function(e){
            $(this).toggleClass("fa-chevron-circle-right fa-chevron-circle-down");
        });

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

    </script> 

@stop
