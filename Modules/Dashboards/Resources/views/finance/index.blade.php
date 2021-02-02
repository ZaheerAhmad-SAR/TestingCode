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
                                    <input type="text" name="date_range" id="date_range" class="form-control filter-form-data" value="{{ request()->visit_date }}">
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
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'incomplete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ))->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'incomplete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ))->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ))->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill badge-primary mb-1" style="font-weight: 400;font-size: 13px;">
                                                {{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'incomplete','study_id' =>$record->study_id,'form_filled_by_user_id' =>$record->user_id ))->count() }}
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
                                                    <tr>
                                                        <td>
                                                            <span data-toggle="tooltip" data-placement="top" title="{{$value->modility_name}}">
                                                                {{ $value->modility_abbreviation }}
                                                            </span>
                                                        </td>
                                                        <th>{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 1,'form_status' => 'complete','modility_id' => $value->id,'form_filled_by_user_id' => $record->user_id))->count() }}</th>
                                                        <th>{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 3,'form_status' => 'complete','modility_id' => $value->id,'form_filled_by_user_id' => $record->user_id))->count() }}</th>
                                                        <th>{{ Modules\FormSubmission\Entities\FormStatus::where(array('form_type_id' => 2,'form_status' => 'complete','modility_id' => $value->id,'form_filled_by_user_id' => $record->user_id))->count() }}</th>
                                                        <th>{{ Modules\FormSubmission\Entities\AdjudicationFormStatus::where(array('form_type_id' => 2,'adjudication_status' => 'complete','modility_id' => $value->id,'form_adjudicated_by_id' => $record->user_id))->count() }}</th>
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
@stop
@section('script')
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
    </script>  
@stop
