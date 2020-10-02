@extends ('layouts.home')

@section('title')
    <title> Sites | {{ config('app.name', 'Laravel') }}</title>
@stop


@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">App Query</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Sites</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                        <div class="chat-screen">
                            <a href="#" class="chat-contact round-button d-inline-block d-lg-none"><i class="icon-menu"></i></a>
                            <a href="#" class="chat-profile d-inline-block d-lg-none"><img class="img-fluid  rounded-circle" src="dist/images/team-3.jpg" width="30" alt=""></a>
                            <div class="row row-eq-height">
                                <div class="col-12 col-lg-4 col-xl-3 mt-lg-3 pr-lg-0">
                                    <div class="card border h-100 chat-contact-list">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active font-weight-bold" id="tabs-day-tab" data-toggle="tab" href="#tabs-day" role="tab" aria-controls="tabs-day" aria-selected="true">Chat</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link font-weight-bold" id="tabs-week-tab" data-toggle="tab" href="#tabs-week" role="tab" aria-controls="tabs-week" aria-selected="false">Contacts</a>
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="tabs-day" role="tabpanel" aria-labelledby="tabs-day-tab">
                                                <ul class="nav flex-column chat-menu" id="myTab" role="tablist">
                                                    <li class="nav-item active px-3">
                                                        <a class="nav-link online-status green" data-toggle="tab" href="#tab1" role="tab" aria-selected="true">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid mr-3 rounded-circle" src="{{asset("dist/images/author2.jpg")}}" alt="">
                                                                <div class="media-body align-self-center mt-0 color-primary d-flex">
                                                                    <div class="message-content"> <b class="mb-1 font-weight-bold d-flex">Waseem</b>
                                                                        How are you?
                                                                        <br>
                                                                        <small class="body-color">23 hours ago</small></div>
                                                                    <div class="new-message ml-auto bg-primary text-white">3</div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-week" role="tabpanel" aria-labelledby="tabs-week-tab">
                                                <ul class="nav flex-column chat-menu" id="myTab1" role="tablist">
                                                    @foreach($users as $user)
                                                    <li class="nav-item active px-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab1-{{$user->id}}" role="tab" aria-selected="true">
                                                            <div class="media d-block d-flex text-left py-3">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="{{asset("dist/images/author2.jpg")}}" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">{{$user->name}}</b><br>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 col-xl-6 mt-3 pl-lg-0 pr-lg-0">
                                    <div class="card border h-100 rounded-0">
                                        <div class="card-body p-0">
                                            <div class="tab-content" id="myTabContent">
                                                <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                                                    <ul class="nav flex-column chat-menu" id="myTab3" role="tablist">
                                                        <li class="nav-item active px-3 px-md-1 px-xl-3">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/team-3.jpg" width="54" alt="">
                                                                <div class="media-body align-self-center mt-0  d-flex">
                                                                    <div class="message-content"> <h6 class="mb-1 font-weight-bold d-flex">Harry Jones</h6>
                                                                        typing ...
                                                                        <br>
                                                                    </div>
                                                                    <div class="call-button ml-auto">
                                                                        <a href="#" class="call h4 mb-0" data-toggle="modal" data-target="#call1"><i class="icon-phone"></i></a>
                                                                        <a href="#" class="video-call h4 mb-0" data-toggle="modal" data-target="#call1"><i class="icon-camrecorder"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>

                                                    <div class="scrollerchat p-3">

                                                        <div class="media d-flex  mb-4">
                                                            <div class="p-3 ml-auto speech-bubble">
                                                                Hello John, how can I help you today ?
                                                            </div>
                                                            <div class="ml-4"><a href="#"><img src="dist/images/author2.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                        </div>
                                                        <div class="media d-flex mb-4">
                                                            <div class="mr-4 thumb-img"><a href="#"><img src="dist/images/author3.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                            <div class="p-3 mr-auto speech-bubble alt">
                                                                Hi, I want to buy a new shoes.
                                                            </div>
                                                        </div>


                                                        <div class="media d-flex mb-4">
                                                            <div class="mr-4 thumb-img"><a href="#"><img src="dist/images/author3.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                            <div class="p-3 mr-auto speech-bubble alt">
                                                                Wow that's great!
                                                            </div>
                                                        </div>
                                                        <div class="media d-flex mb-4">
                                                            <div class="p-3 ml-auto speech-bubble">
                                                                You are welcome!
                                                            </div>
                                                            <div class="ml-4"><a href="#"><img src="dist/images/author2.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                        </div>

                                                    </div>
                                                    <div class="border-top theme-border px-2 py-3 d-flex position-relative chat-box">
                                                        <input type="text" class="form-control mr-2" placeholder="Type message here ..." />
                                                        <a href="#" class="p-2 ml-2 rounded line-height-21 bg-primary text-white"><i class="icon-cursor align-middle"></i></a>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>


@endsection
@section('script')
    <script type="text/javascript" src="{{asset('public/dist/js/queries.js')}}"></script>
@endsection




