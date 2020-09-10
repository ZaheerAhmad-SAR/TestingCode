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
            <div class="row row-eq-height">
                <div class="col-12 col-lg-2 mt-3 todo-menu-bar flip-menu pr-lg-0">
                    <a href="#" class="d-inline-block d-lg-none mt-1 flip-menu-close"><i class="icon-close"></i></a>
                    <div class="card border h-100 contact-menu-section">
                        <div id="accordion">
                        @php
                                        $firstPhase = true;
                                        @endphp
                            @foreach ($visitPhases as $phase)
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapse{{$phase->id}}" aria-expanded="{{ ($firstPhase) ? 'true' : 'false' }}"
                                            aria-controls="collapseOne">
                                            {{$phase->name}}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse{{$phase->id}}" class="collapse {{ ($firstPhase) ? 'show' : '' }}" aria-labelledby="headingOne"
                                    data-parent="#accordion" style="">

                                    <ul class="nav flex-column contact-menu">
                                        @php
                                        $firstStep = true;
                                        @endphp
                                        @foreach ($phase->phases as $step)

                                        <li class="nav-item">
                                            <a class="nav-link {{ ($firstStep) ? 'active' : '' }}"
                                                href="javascript:void(0);"
                                                data-contacttype="contact-{{$step->step_id}}">
                                                {{$step->step_name}}
                                            </a>
                                        </li>
                                        @php
                                        $firstStep = false;
                                        @endphp
                                        @endforeach
                                    </ul>

                                </div>
                            </div>
                            @php
                                        $firstPhase = false;
                                        @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-10 mt-3 pl-lg-0">
                    <div class="card border h-100 contact-list-section">

                        @php
                        $firstStep = true;
                        @endphp
                        @foreach ($phase->phases as $step)
                        @php
                        $sections = $step->sections;
                        @endphp
                        <div class="card-body p-0">
                            <div class="contacts list">
                                <div class="contact contact-{{$step->step_id}}">
                                    @include('admin::forms.section_loop', ['step'=>$step,
                                    'sections'=> $sections])
                                </div>
                            </div>
                        </div>
                        @php
                        $firstStep = false;
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

    @section('script')
    <script>
    $('.contact-menu a').on('click', function() {
        $('.contact-menu a').removeClass('active');
        $(this).addClass('active');
        $('.contact').hide();
        $('.' + $(this).data("contacttype")).show(500);
        return false;
    });
    </script>
    @stop