@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width" style="padding-bottom: 10px;">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12 align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0"></h4>
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
    <div class="card">
        <div class="card-header  justify-content-between align-items-center">
            <h4 class="card-title">Validations ON Textbox</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <button type="button" class="btn-primary" style="border-radius: 50%;height: 20px;width: 20px;border-color: black;"></button> Steps
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn-secondry" style="border-radius: 50%;height: 20px;width: 20px;border-color: black;"></button> Sections
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn-info" style="border-radius: 50%;height: 20px;width: 20px;border-color: black;"></button> Questions
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn-info" style="border-radius: 50%;height: 20px;width: 20px;border-color: black;background-color:white;"></button> Options
                </div>
            </div>
        </div>
    </div>
    <!-- START: Card Data-->
    <form action="{{route('skipNumber.apply_skip_logic_text')}}" enctype="multipart/form-data" method="POST">
        @php
        $q_id = request('id');
        $index = 0;
        @endphp
        @csrf
        <input type="hidden" name="question_id" value="{{request('id')}}">
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{$num_values->question_text}}</label>
                                <input type="text" class="form-control" name="textbox_value[]" placeholder="Enter Text" value="" required>
                            </div>
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
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom:0px;background-color: #1E3D73;color: white;">
                                    <tbody>
                                        <tr>
                                            <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                            <td style="text-align: center;width: 15%">
                                              <div class="btn-group btn-group-sm" role="group">
                                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->step_id}}-ac-{{$key}}" style="font-size: 20px; "></i>
                                              </div>
                                            </td>
                                            <td colspan="5"> <input type="checkbox" name="activate_forms[{{$index}}][]" value="{{$value->step_id}}" class="activate_step_{{$value->step_id}}_{{$key}}" onclick="disabled_opposite('{{$value->step_id}}','deactivate_step_','{{$key}}','activate_step_')"> &nbsp;&nbsp;{{$value->step_name}}({{$value->formType->form_type }}- {{ $value->modility->modility_name}})</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card collapse row-{{$value->step_id}}-ac-{{$key}} sections_list_{{$value->step_id}}_{{$key}}">
                        @include('admin::forms.skiplogic_by_text_and_number.activate_sections')
                    </div>
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
                    <div class="card">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="laravel_crud" style="margin-bottom:0px;background-color: #1E3D73;color: white;">
                                    <tbody>
                                        <tr>
                                            <td class="step_id" style="display: none;">{{$value->step_id}}</td>
                                            <td style="text-align: center;width: 15%">
                                              <div class="btn-group btn-group-sm" role="group">
                                                <i class="fas h5 mr-2 fa-chevron-circle-right detail-icon" title="Log Details" data-toggle="collapse" data-target=".row-{{$value->step_id}}-de-{{$key}}" style="font-size: 20px;"></i>
                                              </div>
                                            </td>
                                            <td colspan="5"><input type="checkbox" name="deactivate_forms[{{$index}}][]" value="{{$value->step_id}}" class="deactivate_step_{{$value->step_id}}_{{$key}}" onclick="disabled_opposite('{{$value->step_id}}','activate_step_','{{$key}}','deactivate_step_')"> &nbsp;&nbsp;{{$value->step_name}}({{$value->formType->form_type }}- {{ $value->modility->modility_name}})</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card collapse row-{{$value->step_id}}-de-{{$key}} de_sections_list_{{$value->step_id}}_{{$key}}">
                        @include('admin::forms.skiplogic_by_text_and_number.deactivate_sections')
                    </div>
                @endforeach
            </div>
        </div>
    </div>
        <div class="modal-footer">
             <a href="{{route('forms.index')}}">
                <button type="button" class="btn btn-outline-danger"><i class="far fa-arrow-alt-circle-left"></i> Back to Listing</button>
            </a>
            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
        </div>
    </form>
         {{-- listing here for logics --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Value</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                          @foreach($num_values->skiplogic as $key => $value)
                            <tr>
                                <td>{{$value->textbox_value}}</td>
                                <td>
                                   <div class="d-flex mt-3 mt-md-0 ml-auto">
                                        <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                            <span class="dropdown-item"><a href="{{route('skipNumber.updateSkipText',$value['id'])}}"><i class="far fa-edit"></i>&nbsp; Edit </a></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 {{-- listing here for logics --}}
@endsection
@include('admin::forms.common_script_skip_logic')
    @section('styles')
        <link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
    @endsection
@section('script')
    <script type="text/javascript">
        $('.detail-icon').click(function(e){
            $(this).toggleClass("fa-chevron-circle-right fa-chevron-circle-down");
        });
    </script>
@endsection
