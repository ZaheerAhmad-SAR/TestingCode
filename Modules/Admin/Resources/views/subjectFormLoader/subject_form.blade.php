@extends ('layouts.home')
@section('content')
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <h4 class="mb-0">Subject Phases</h4>
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
            <div class="col-12 col-md-12 mt-3">

                <div class="card">
                    <div class="card-header  justify-content-between align-items-center">
                        <h4 class="card-title">Visits/Phases</h4>
                    </div>
                    <div class="card-body">
                        @php
                        $firstPhase = true;
                        @endphp
                        @foreach ($visitPhases as $phase)
                        <p>
                            <a class="btn btn-primary collapsed" data-toggle="collapse" href="#collapse{{$phase->id}}"
                                role="button" aria-expanded="false" aria-controls="collapseExample">
                                {{$phase->name}}
                            </a>
                        </p>
                        <div class="collapse" id="collapse{{$phase->id}}" style="">
                            <div class="card-body">
                                <div class="card bg-primary">
                                    <div class="card-body wizard-dark">
                                        <div class="row p-3">
                                            <ul class="col-sm-3 nav nav-tabs d-block d-sm-flex mb-4">
                                                @php
                                                $firstStep = true;
                                                @endphp
                                                @foreach ($phase->phases as $step)
                                                @include('admin::subjectFormLoader.step_li_loop', ['step'=>$step])
                                                @php
                                                $firstStep = false;
                                                @endphp
                                                @endforeach
                                            </ul>
                                            <div class="tab-content col-sm-9">
                                                @php
                                                $firstStep = true;
                                                @endphp
                                                @foreach ($phase->phases as $step)
                                                @php
                                                $sections = $step->sections;
                                                @endphp
                                                <div class="tab-pane fade {{ ($firstStep) ? 'active show' : '' }}"
                                                    id="tab{{$step->step_id}}">
                                                    @include('admin::forms.section_loop', ['step'=>$step,
                                                    'sections'=> $sections])
                                                </div>
                                                @php
                                                $firstStep = false;
                                                @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        @php
                        $firstPhase = false;
                        @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
    @stop

@section('styles')
@include('admin::forms.form_css')
@stop