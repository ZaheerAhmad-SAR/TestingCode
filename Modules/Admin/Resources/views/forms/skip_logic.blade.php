@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
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
                <h4 class="card-title">Skip Logic</h4>
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
        <form action="{{route('skiplogic.apply_skip_logic')}}" enctype="multipart/form-data" method="POST">
            @csrf
            @php
            $questionsType ='radio';
            $check_value = [];
            $q_id = request('id');
            $options_value = explode(',', $options->optionsGroup->option_value);
            $options_name = explode(',', $options->optionsGroup->option_name);
            @endphp
            <input type="hidden" name="question_id" value="{{request('id')}}">
            @foreach($options_name as $index => $option)
            <div class="row">
               <div class="col-12 col-sm-12 mt-3">
                   <div class="card">
                       <div class="card-body">
                            <input type="hidden" name="option_title[]" value="{{$option}}">
                            @foreach($options->skiplogic as $logic)
                                @if($logic->option_value !='')
                                   <?php $check_value[] = $logic->option_value; ?>
                                @endif
                            @endforeach
                            {{$options->question_text}} &nbsp;
                            <input type="checkbox" name="option_value[]" value="{{$options_value[$index]}}" @if(in_array($options_value[$index], $check_value)) checked="checked" @endif> &nbsp; {{$option}} 
                       </div>
                   </div>
               </div>
            </div>
            <div class="row append_data_{{$options_value[$index]}}">
                @include('admin::forms.skiplogic_by_options.activate_forms')
                @include('admin::forms.skiplogic_by_options.deactivate_forms')
            </div>
            @endforeach
            </div>
            <div class="modal-footer">
                <a href="{{route('forms.index')}}">
                    <button type="button" class="btn btn-outline-danger"><i class="far fa-arrow-alt-circle-left"></i> Back to Listing</button>
                </a>
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
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
@push('script_last')
@endpush
@endsection
