@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0">Phase: {{$phase->name}} / {{$step->step_name}}</h4>
                </div>

                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Forms</li>
                    <li class="breadcrumb-item active"><a href="#">Form Type Here</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-sm-12">
            @php
            $firstStep = true;
            @endphp
            @php
            $sections = $step->sections;
            @endphp
            <div class="tab-pane fade {{ ($firstStep) ? 'active show' : '' }}" id="tab{{$step->step_id}}">
                @include('admin::forms.section_loop', ['step'=>$step, 'sections'=> $sections])
            </div>
            @php
            $firstStep = false;
            @endphp
        </div>
    </div>
    <!-- END: Card DATA-->
</div>
@stop

@section('styles')
@include('admin::forms.form_css')
@stop