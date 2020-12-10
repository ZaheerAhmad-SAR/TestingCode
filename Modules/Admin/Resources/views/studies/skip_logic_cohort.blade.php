@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                   
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
                <h4 class="card-title">Skip Logic Via Cohort based</h4>
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
                        <button type="button" class="btn-danger" style="border-radius: 50%;height: 20px;width: 20px;border-color: black;"></button> Questions
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn-info" style="border-radius: 50%;height: 20px;width: 20px;border-color: black;background-color:white;"></button> Options
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <form action="" enctype="multipart/form-data" method="POST">
            @csrf
            {{-- {{dd(request('id'))}} --}}
          
            <input type="hidden" name="question_id" value="{{request('id')}}">

            @foreach($disease_cohorts as $key => $value)
            <div class="row">
               <div class="col-12 col-sm-12 mt-3">
                   <div class="card">
                       <div class="card-body">
                            <input type="checkbox" name="option_value[]" onclick="git_steps_for_checks_deactivate_cohort('{{$value->id}}','{{$key}}','{{request('id')}}','{{$value->name}}')" value="">  {{$value->name}}
                       </div>
                   </div>
               </div>
            </div>
            <div class="row append_data_{{$value->id}}">
            </div>
            @push('script_last')
           
            @endpush
            @endforeach
            </div>
            <div class="modal-footer">
                {{-- <a href="{{route('studies.index')}}">
                    <button type="button" class="btn btn-outline-danger"><i class="far fa-arrow-alt-circle-left"></i> Back to Listing</button>
                </a> --}}
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </form>
        <!-- END: Card DATA-->
    </div>
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
 
@push('script_last')
    
@endpush
@endsection
