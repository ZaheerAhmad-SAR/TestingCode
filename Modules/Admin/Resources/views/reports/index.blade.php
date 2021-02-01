@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Report Turnaround Time of visits completion</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Reports</li>
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
                                    <label for="trans_id">Transmission#</label>
                                    <input type="text" name="trans_id" id="trans_id" class="form-control filter-form-data" value="{{ request()->trans_id }}" placeholder="Transmission#">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="inputState"> Users </label>
                                    <select id="study_id" name="study_id" class="form-control filter-form-data">
                                        <option value="">All Users</option>
                                        @foreach($getStudies as $study)
                                        <option @if ($study->study_code == request()->study_id) selected @endif value="{{ $study->study_code}}"> {{ $study->study_short_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputState"> Study </label>
                                    <select id="study_id" name="study_id" class="form-control filter-form-data">
                                        <option value="">All Studies</option>
                                        @foreach($getStudies as $study)
                                        <option @if ($study->study_code == request()->study_id) selected @endif value="{{ $study->study_code}}"> {{ $study->study_short_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="suject_id">Subject ID</label>
                                    <input type="text" name="subject_id" id="subject_id" class="form-control filter-form-data" value="{{ request()->subject_id }}" placeholder="Subject ID">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="visit_name">Visit Name</label>
                                    <input type="text" name="visit_name" id="visit_name" class="form-control filter-form-data" value="{{ request()->visit_name }}" placeholder="Visit Name">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="dt">Visit Date</label>
                                    <input type="text" name="visit_date" id="visit_date" class="form-control visit_date filter-form-data" value="{{ request()->visit_date }}">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="imagine_modality">Imagine Modality</label>
                                    <input type="text" name="imagine_modality" id="imagine_modality" class="form-control filter-form-data" value="{{ request()->imagine_modality }}" placeholder="Imagine Modality">
                                </div>
                                <div class="form-group col-md-3 mt-4">
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
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">  
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead class="table-secondary">
                                    <tr>
                                        <th onclick="changeSort('Transmission_Number');">Transmission # <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('Site_ID');"> Site ID <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('Subject_ID');"> Subject ID <i class="fas fa-sort float-mrg"></i></th>
                                        <th onclick="changeSort('visit_name');" style="width: 10%;"> Visit <i class="fas fa-sort float-mrg"></i></th>
                                        <th>TAT QC</th>
                                        <th>TAT Grading</th>
                                        <th>TAT Adjudication</th>
                                        <th>Modality</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                    @php
                                       $form_data_qc = get_tat_of_visit_complete($transmission->Subject_ID,$transmission->phase_id,'1','complete',$transmission->modility_id);
                                      $form_data_grading = get_tat_of_visit_complete($transmission->Subject_ID,$transmission->phase_id,'2','complete',$transmission->modility_id);
                                      $form_data_adj = get_tat_of_visit_complete($transmission->Subject_ID,$transmission->phase_id,'2','complete',$transmission->modility_id);
                                      
                                    @endphp
                                      <tr>
                                        <td>{{$transmission->Transmission_Number}}</td>
                                        <td>{{$transmission->Site_ID}}</td>
                                        <td>{{$transmission->Subject_ID}}</td>
                                        <td>{{$transmission->visit_name}}</td>
                                        <td>
                                            @if($form_data_qc !=null)
                                            <span data-toggle="tooltip" data-placement="top" title="{{$form_data_qc->user->name}}">
                                            {{ get_date_differnce($transmission->created_at,$form_data_qc->created_at) }}
                                            </span>
                                            @else
                                                Null
                                            @endif
                                            
                                        </td>
                                        <td>
                                            @if($form_data_grading !=null)
                                            <span data-toggle="tooltip" data-placement="top" title="{{$form_data_qc->user->name}}">
                                                {{ get_date_differnce($transmission->created_at,$form_data_grading->created_at) }}
                                            </span>
                                            @else
                                                Null
                                            @endif
                                        </td>
                                        <td>
                                            @if($form_data_adj !=null)
                                            <span data-toggle="tooltip" data-placement="top" title="{{$form_data_qc->user->name}}">
                                                {{ get_date_differnce($transmission->created_at,$form_data_adj->created_at) }}
                                            </span>
                                            @else
                                                Null
                                            @endif
                                        </td>
                                        <td>{{$transmission->ImageModality}}</td>
                                    </tr> 
                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="9" style="text-align: center">No record found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{ $getTransmissions->appends(['trans_id' => \Request::get('trans_id'), 'study_id' => \Request::get('study_id'), 'subject_id' => \Request::get('subject_id'), 'visit_name' => \Request::get('visit_name'), 'visit_date' => \Request::get('visit_date'), 'imagine_modality' => \Request::get('imagine_modality'), 'is_read' => \Request::get('is_read'), 'status' => \Request::get('status') ])->links() }}

                        </div>
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
@stop
