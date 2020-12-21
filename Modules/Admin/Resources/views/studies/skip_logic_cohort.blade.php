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
                <h4 class="card-title">Skip Logic Using Cohort based</h4>
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
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <form action="{{route('skiplogic.apply_skip_logic_cohort_based')}}" enctype="multipart/form-data" method="POST">
            @php 
                $questionsType ='cohort';
                $check_value = [];
                $studyId = request('id');
            @endphp
            @foreach($cohort_skiplogic as $cohort)
                @if($cohort->cohort_id !='')
                   <?php $check_value[] = $cohort->cohort_id; ?>
                @endif
            @endforeach
            @csrf
            <input type="hidden" name="study_id" value="{{request('id')}}">
            @foreach($disease_cohorts as $index => $value)
            @php 
                $diseaseName = $value->name;
                $diseaseid = $value->id;
            @endphp
            <div class="row">
               <div class="col-12 col-sm-12 mt-3">
                   <div class="card">
                       <div class="card-body">
                            <input type="hidden" name="cohort_name[]" value="{{$value->name}}">
                            <input type="checkbox" name="cohort_id[]" value="{{$value->id}}" @if(in_array($value->id, $check_value)) checked="checked" @endif>  {{$value->name}}
                       </div>
                   </div>
               </div>
            </div>
            <div class="row append_data_{{$value->id}}">
                @include('admin::forms.skip_logic_view_cohort.deactivate_forms')
            </div>
            @endforeach
            </div>
            <div class="modal-footer">
                <a href="{{route('studies.index')}}">
                    <button type="button" class="btn btn-outline-danger"><i class="far fa-arrow-alt-circle-left"></i> Back to Listing</button>
                </a>
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </form>
        <!-- END: Card DATA-->
    </div>
@endsection
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
