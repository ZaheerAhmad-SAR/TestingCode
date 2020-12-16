@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Validations ON Number</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Skip Logic</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 success-alert" style="display: none;">
                <div class="alert alert-primary success-msg" role="alert">
                </div>
            </div>
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        {{-- {{dd($num_values->number_value)}} --}}
        <form action="{{route('skipNumber.updateSkipNumberChecks')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="question_id" value="{{request('id')}}">
            <div class="row">
               <div class="col-12 col-sm-12 mt-3">
                   <div class="card">
                       <div class="card-body">
                            <div class="col-md-6" style="display: inline-block;">
                                <div class="">
                                    <label>{{$num_values->question_text}}</label>
                                    <input type="text" class="form-control" name="number_value[]" placeholder="Enter Number values " value="{{$num_values->number_value}}" required>
                                </div>
                            </div>
                            <div class="col-md-5" style="display: inline-block;">    
                                <select class="form-control" name="operator[]">
                                    @php
                                    $operatorArray = [
                                         '==' => 'Equal to',
                                         '>=' => 'Greater than or Equal to',
                                         '<=' => 'Less than or Equal to',
                                         '>' => 'Greater than',
                                         '<' => 'Less than',
                                        ];
                                        $optionStr = '<option value="">--Select--</option>';
                                        foreach ($operatorArray as $operator => $text) {
                                         $selected = ($num_values->operator ==$operator) ? 'selected="selected"' : '';
                                         $optionStr .= '<option value="' . $operator . '" ' . $selected . '>' . $text . '</option>';
                                        }
                                        echo $optionStr;
                                    @endphp
                                </select>
                            </div>
                       </div>
                   </div>
               </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 mt-3 current_div_ac">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Activate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>           
        @foreach ($all_study_steps as $key => $value)
        @foreach($value->studySteps as $index => $value)
        @php 
            $q_id = request('id');
            $activate_forms_array = explode(',', $num_values->activate_forms);
            if(in_array($value->step_id, $activate_forms_array)){ $checked = 'checked'; }else{ $checked = ''; }
        @endphp
            {{-- @if(in_array($value->step_id, $activate_forms_array)){ $checked = 'checked'; }@else{ $checked = ''; }@endif --}}
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                <tbody>
                                    <tr>
                                        <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                        <td style="text-align: center;width: 15%">
                                          <div class="btn-group btn-group-sm" role="group">
                                            <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" onclick="activate_checks('{{$value->step_id}}','sections_list_','{{$key}}','{{request('id')}}','q_type_num');" data-target=".row-{{$value->step_id}}-ac-{{$key}}" style="font-size: 20px; color: #1e3d73;"></i>
                                          </div>
                                        </td>
                                        <td colspan="5"> <input type="checkbox" name="activate_forms[{{$key}}][]" value="{{$value->step_id}}" {{$checked}}> &nbsp;&nbsp;{{$value->step_name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card collapse row-{{$value->step_id}}-ac-{{$key}} sections_list_{{$value->step_id}}_{{$key}}">
                </div>
                 @push('script_last')
                    <script>
                     $(document).ready(function() {
                     @php
                         echo "activate_checks('$value->step_id','sections_list_','$key','$q_id','q_type_num','');";
                     @endphp
                     })
                 </script>
                @endpush
        @endforeach
        @endforeach
        </div>
        <div class="col-12 col-sm-6 mt-3 current_div_de">
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%">Expand</th>
                                            <th colspan="5">Deactivate Modality,Sections,Question</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
        @foreach ($all_study_steps as $key => $value)
        @foreach($value->studySteps as $index => $value)
         @php 
            $q_id = request('id');
            $deactivate_forms_array = explode(',', $num_values->deactivate_forms);
            if(in_array($value->step_id, $deactivate_forms_array)){ $checked = 'checked'; }else{ $checked = ''; }
        @endphp
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                <td style="text-align: center;width: 15%">
                                  <div class="btn-group btn-group-sm" role="group">
                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->step_id}}-de-{{$key}}" onclick="deactivate_checks('{{$value->step_id}}','de_sections_list_','{{$key}}','{{request('id')}}','q_type_num');" style="font-size: 20px; color: #1e3d73;"></i>
                                  </div>
                                </td>
                                <td colspan="5"><input type="checkbox" name="deactivate_forms[{{$key}}][]" value="'{{$value->step_id}}" {{$checked}}> &nbsp;&nbsp;{{$value->step_name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card collapse row-{{$value->step_id}}-de-{{$key}} de_sections_list_{{$value->step_id}}_{{$key}}">
        </div>
            @push('script_last')
             <script>
                 $(document).ready(function() {
                 @php
                     echo "deactivate_checks('$value->step_id','de_sections_list_','$key','$q_id','q_type_num','');";
                 @endphp
                 })
             </script>
            @endpush
        @endforeach
        @endforeach
        </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </form>
        <!-- END: Card DATA-->

@endsection
@include('admin::forms.edit_crf')
@include('admin::forms.script_skip_logic')
@include('admin::forms.common_script_skip_logic')
    @section('styles')
    <style type="text/css">
            /*.table{table-layout: fixed;}*/
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
        </style>
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
        <!-- select2 -->
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
    @endsection
    @section('script')
    <script src="{{ asset('public/dist/vendors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/mail.script.js') }}"></script>
    <!-- select2 -->
    <script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

@endsection
