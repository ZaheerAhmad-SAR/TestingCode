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
                    <div class="card-header d-flex justify-content-between align-items-center">
                    </div>
                    <div class="card-body">
                        <!-- START: Card Data-->
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
                                            <a href="#"  class="bg-primary py-1 px-2 rounded ml-auto text-white" data-toggle="modal" data-target="#newcontact">
                                                <span class="d-xl-inline-block">Add New</span>
                                            </a>
                                            <!-- The Modal -->
                                            <div class="modal" id="newcontact">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">
                                                                <i class="icon-user-follow"></i> Add Friends
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <i class="icon-close"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form>
                                                                <div class="form-group">
                                                                    <label for="emails" class="col-form-label">Name</label>
                                                                    <input type="text" class="form-control" id="name">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="emails" class="col-form-label">Email addresses</label>
                                                                    <input type="text" class="form-control" id="emails">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="emails" class="col-form-label">Phone</label>
                                                                    <input type="text" class="form-control" id="phone">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="message" class="col-form-label">Message</label>
                                                                    <textarea class="form-control" id="message"></textarea>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="tabs-day" role="tabpanel" aria-labelledby="tabs-day-tab">
                                                <ul class="nav flex-column chat-menu" id="myTab" role="tablist">
                                                    <li class="nav-item active px-3">
                                                        <a class="nav-link online-status green" data-toggle="tab" href="#tab1" role="tab" aria-selected="true">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid mr-3 rounded-circle" src="dist/images/author2.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0 color-primary d-flex">
                                                                    <div class="message-content"> <b class="mb-1 font-weight-bold d-flex">Harry Jones</b>
                                                                        How are you? ...
                                                                        <br>
                                                                        <small class="body-color">23 hours ago</small></div>
                                                                    <div class="new-message ml-auto bg-primary text-white">3</div>

                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item  px-3">
                                                        <a class="nav-link online-status green" data-toggle="tab" href="#tab2" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/author3.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0 color-primary d-flex">
                                                                    <div class="message-content"> <b class="mb-1 font-weight-bold d-flex">Daniel Taylor</b>
                                                                        I am waiting ...
                                                                        <br>
                                                                        <small class="body-color">14 hours ago</small></div>
                                                                    <div class="new-message ml-auto bg-primary text-white">1</div>

                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item  px-3">
                                                        <a class="nav-link online-status yellow" data-toggle="tab" href="#tab3" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid mr-3 rounded-circle" src="dist/images/author.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Charlotte </b><br>
                                                                    video <i class="fa fa-file-video-o"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item  px-3">
                                                        <a class="nav-link online-status yellow" data-toggle="tab" href="#tab4" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/author7.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Jack Sparrow</b><br>
                                                                    tour pictures <i class="fa fa-photo"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item px-3">
                                                        <a class="nav-link online-status yellow" data-toggle="tab" href="#tab5" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/author6.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Bhaumik</b><br>
                                                                    Lorem Ipsum has been the industry ...
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item px-3">
                                                        <a class="nav-link online-status yellow" data-toggle="tab" href="#tab6" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-2">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/author8.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Wood Walton</b><br>
                                                                    Aldus PageMaker including versions ...
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-week" role="tabpanel" aria-labelledby="tabs-week-tab">
                                                <ul class="nav flex-column chat-menu" id="myTab1" role="tablist">
                                                    <li class="nav-item active px-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab1" role="tab" aria-selected="true">
                                                            <div class="media d-block d-flex text-left py-3">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/author2.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Harry Jones</b><br>
                                                                    Managing Partner at MDD
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item px-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab2" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-3">
                                                                <img class="img-fluid mr-3 rounded-circle" src="dist/images/author3.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Daniel Taylor</b><br>
                                                                    Freelance Web Developer
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item px-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab3" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-3">
                                                                <img class="img-fluid mr-3 rounded-circle" src="dist/images/author.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Charlotte </b><br>
                                                                    Co-Founder &amp; CEO at Pi
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item px-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab4" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-3">
                                                                <img class="img-fluid  mr-3 rounded-circle" src="dist/images/author7.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Jack Sparrow</b><br>
                                                                    Managing Partner at MDD
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item px-3">
                                                        <a class="nav-link" data-toggle="tab" href="#tab5" role="tab" aria-selected="false">
                                                            <div class="media d-block d-flex text-left py-3">
                                                                <img class="img-fluid mr-3 rounded-circle" src="dist/images/author6.jpg" alt="">
                                                                <div class="media-body align-self-center mt-0">
                                                                    <b class="mb-1 font-weight-bold">Bhaumik</b><br>
                                                                    Managing Partner at MDD
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>

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
                                                    <!-- The Modal -->
                                                    <div class="modal" id="call1">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">

                                                                <div class="modal-body p-0">
                                                                    <ul class="nav flex-column chat-menu">
                                                                        <li class="nav-item active px-3 py-4">
                                                                            <div class="media d-block d-flex text-left py-3">
                                                                                <img class="img-fluid mr-3 rounded-circle" src="dist/images/author2.jpg" alt="">
                                                                                <div class="media-body align-self-center mt-0  d-flex">
                                                                                    <div class="message-content"> <h6 class="mb-1 font-weight-bold d-flex">Harry Jones</h6>
                                                                                        calling ...
                                                                                        <br>
                                                                                    </div>
                                                                                    <div class="call-button ml-auto">
                                                                                        <a href="#" class="call h4" data-toggle="modal" data-target="#call1"><i class="icon-phone"></i></a>
                                                                                        <a href="#" class="video-call ml-2 h4 bg-danger"  data-dismiss="modal"><i class="icon-close"></i></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>



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
                                                            <div class="p-3 ml-auto speech-bubble">
                                                                Shipment is free. You'll get your shoes tomorrow!<br/>
                                                                <img src="dist/images/shoes.jpg" alt="" width="300" class="img-fluid mt-2" />
                                                            </div>
                                                            <div class="ml-4"><a href="#"><img src="dist/images/author2.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                        </div>

                                                        <div class="media d-flex mb-4">
                                                            <div class="mr-4 thumb-img"><a href="#"><img src="dist/images/author3.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                            <div class="p-3 mr-auto speech-bubble alt">
                                                                Wow that's great!
                                                            </div>
                                                        </div>
                                                        <div class="media d-flex mb-4">
                                                            <div class="mr-4 thumb-img"><a href="#"><img src="dist/images/author3.jpg" alt="" class="img-fluid rounded-circle" /></a></div>
                                                            <div class="p-3 mr-auto speech-bubble alt">
                                                                Ok. Thanks for the answer. Appreciated.<br/>
                                                                <div class='embed-container mt-2'><iframe src='https://player.vimeo.com/video/66140585' class="border-0" allowFullScreen></iframe></div>
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
                                <div class="col-12 col-lg-4 col-xl-3 mt-lg-3 pl-lg-0">
                                    <div class="card border h-100 chat-user-profile">
                                        <ul class="nav flex-column">
                                            <li class="nav-item active px-3">
                                                <div class="media d-block d-flex text-left py-2">
                                                    <div class="media-body align-self-center mt-0  d-flex">
                                                        <div class="message-content my-1"> <h6 class="mb-1 font-weight-bold d-flex">Harry Jones</h6>
                                                            Lead Web Developer - I can fix anything
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <img class="img-fluid" src="dist/images/team-3.jpg" alt="">
                                        <div class="px-3 py-4">
                                            <b>Display Name</b>
                                            <p>Harry</p>
                                            <b>Local time</b>
                                            <p>3:40AM</p>
                                            <b>Email Address</b>
                                            <p>harry@example.com</p>
                                        </div>
                                        <div class="d-flex outline-badge-primary border-0 mt-1">
                                            <div class="w-50 text-center p-3 border-right"><a href="#" class="font-weight-bold">View Profile <i class="fas fa-arrow-right"></i></a></div>
                                            <div class="w-50 text-center p-3"><a href="#" class="text-danger font-weight-bold">Logout <span class="icon-logout"></span></a></div>
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
{{--    <script src="{{asset('public/dist/js/sites.js')}}"></script>--}}
<script type="text/javascript">

    var placeSearch, autocomplete;

    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name',

    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('autocomplete'), {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }

    // [START region_fillform]
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        var fullAddress =[];
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
            if (addressType == "street_number") {
                fullAddress[0] = val;
            } else if (addressType == "route") {
                fullAddress[1] = val;
            }
        }
        document.getElementById('fullAddr').value = fullAddress.join(" ");
        if (document.getElementById('fullAddr').value !== "") {
            document.getElementById('fullAddr').disabled = false;
        }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

    // Add New Primary Investigator
    function addPrimaryInvestigator(){
        $("#primaryInvestigatorForm").submit(function(e) {
            var first_name        = $('#pi_first_name').val();
            var p_mid_name        = $('#pi_mid_name').val();
            var p_last_name       = $('#pi_last_name').val();
            var p_phone           = $('#pi_phone').val();
            var p_email           = $('#pi_email').val();
            var pi_id             = $('#pi_id').val();
            var pi_submit_actions = $('#pi_submit_actions').val();
            $('#primaryInvestigatorForm').find($('input[name="site_id"]').val($('#site_id').val()));
            if(pi_submit_actions  == 'Add')
            {
                var action_url = "{{ route('primaryinvestigator.store') }}";
            }
            else
            {
                var action_url = "{{ route('updatePrimaryinvestigator') }}";
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });
            e.preventDefault();
            $.ajax({
                data: $('#primaryInvestigatorForm').serialize(),
                url: action_url,
                type: "POST",
                dataType: 'json',
                success: function (results) {
                    //console.log(results);
                    var primary_investigator_id = results.id;
                    var html    =   '';

                    if(pi_submit_actions == 'Add') {

                        html    += '<tr id='+primary_investigator_id+'>\n'+
                            '<td>'+first_name + '   '.repeat(4)+p_last_name+'</td>\n'+
                            '<td>'+p_phone+'</td>\n' +
                            '<td>'+p_email+'</td>\n' +
                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+primary_investigator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editprimaryinvestigator" data-id ='+primary_investigator_id+'></i>'+
                            '</td>\n' +
                            '</tr>';

                        $('.primaryInvestigatorTableAppend tbody').prepend(html);
                    }
                    else{

                        $.each(results, function(index,row)
                        {
                            //console.log(results);
                            html    += '<tr id='+row.id+'>\n'+
                                '<td>'+row.first_name + '  '.repeat(4)+row.last_name+'</td>\n'+
                                '<td>'+row.phone+'</td>\n' +
                                '<td>'+row.email+'</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editprimaryinvestigator" data-id ='+row.id+'></i>'+
                                '</td>\n' +
                                '</tr>';
                        });

                        $('.primaryInvestigatorTableAppend tbody').html(html);

                    }

                    $('#primaryInvestigatorForm').trigger("reset");
                },
                error: function (results) {
                    console.log('Error:', results);
                    //$('#saveChild').html('Save Changes');
                }
            });
        });
    }
    addPrimaryInvestigator();
    // End of primary Investigator

    // Primary Investigator Delete function
    function primaryinvestigatorDestroy(){
        $('body').on('click', '.deleteprimaryinvestigator', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var primary_investigator_id = $(this).data("id");
            var url = "{{URL('/primaryinvestigator')}}";
            var newPath = url+ "/"+ primary_investigator_id+"/destroy/";
            if( confirm("Are You sure want to delete !") ==true)
            {
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        $('#'+primary_investigator_id).remove();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    }
    primaryinvestigatorDestroy();

    function resetprimaryinvestigatorForm() {
        $("#rest_pi_button").click(function(){
            $("#pi_submit_actions").attr('value', 'Add');
            $("#primaryInvestigatorForm").trigger("reset");
        });
    }
    resetprimaryinvestigatorForm();

    function resetcoordinatorForm() {
        $("#reset_c_button").click(function(){
            $("#c_submit_actions").attr('value', 'Add');
            $("#coordinatorForm").trigger("reset");
        });
    }
    resetcoordinatorForm();

    function resetphotographerForm()
    {
        $("#reset_photographer_button").click(function(){
            $("#photographer_submit_actions").attr('value', 'Add');
            $("#photographerForm").trigger("reset");
        });
    }
    resetphotographerForm();

    function resetothersForm()
    {
        $("#reset_others_button").click(function(){
            $("#others_submit_actions").attr('value', 'Add');
            $("#othersForm").trigger("reset");
        });
    }

    resetothersForm();
    //// show Coordinator function

    function showCoordinator() {
        $('body').on('click', '.editCoordinator', function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id =($(this).attr("data-id"));
            var url = "{{URL('/coordinator')}}";
            var newPath = url+ "/"+ id+"/edit/";

            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results) {
                    var parsedata = JSON.parse(results)[0];
                    console.log(parsedata);
                    $('#c_id').val(parsedata.id);
                    $('#c_site_id').val(parsedata.site_id);
                    $('#c_submit_actions').val('Edit');
                    $('#c_first_name').val(parsedata.first_name);
                    $('#c_mid_name').val(parsedata.mid_name);
                    $('#c_last_name').val(parsedata.last_name);
                    $('#c_phone').val(parsedata.phone);
                    $('#c_email').val(parsedata.email);
                }
            });
        });
    }
    showCoordinator();

    //// showOthers function

    //// show Primary Investigator function
    function showPrimaryInvestigator() {
        $('body').on('click', '.editprimaryinvestigator', function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id =($(this).attr("data-id"));
            var url = "{{URL('/primaryinvestigator')}}";
            var newPath = url+ "/"+ id+"/edit/";
            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results) {
                    var parsedata = JSON.parse(results)[0];
                    console.log(parsedata);
                    $('#pi_id').val(parsedata.id);
                    $('#pi_site_id').val(parsedata.site_id);
                    $('#pi_submit_actions').val('Edit');
                    $('#pi_first_name').val(parsedata.first_name);
                    $('#pi_mid_name').val(parsedata.mid_name);
                    $('#pi_last_name').val(parsedata.last_name);
                    $('#pi_phone').val(parsedata.phone);
                    $('#pi_email').val(parsedata.email);
                }
            });
        });
    }
    showPrimaryInvestigator();
    // Add New Photographer
    function addPhotographer()
    {
        $("#photographerForm").submit(function(e) {
            var photographer_first_name     = $('#photographer_first_name').val();
            var photographer_mid_name       = $('#photographer_mid_name').val();
            var photographer_last_name      = $('#photographer_last_name').val();
            var photographer_phone          = $('#photographer_phone').val();
            var photographer_email          = $('#photographer_email').val();
            var photo_id                    = $('#photo_id').val();
            var photographer_submit_actions = $('#photographer_submit_actions').val();
            $('#photographerForm').find($('input[name="site_id"]').val($('#site_id').val()));
            if(photographer_submit_actions  == 'Add')
            {
                var action_url = "{{ route('photographers.store') }}";
            }
            else
            {
                var action_url = "{{ route('updatePhotographers') }}";
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                data: $('#photographerForm').serialize(),
                url: action_url,
                type: "POST",
                dataType: 'json',
                success: function (results) {

                    var photographer_id = results[0].id;
                    var html    =   '';
                    if(photographer_submit_actions == 'Add')
                    {
                        html    += '<tr id='+photographer_id+'>\n' +
                            '<td>'+photographer_first_name + '   '.repeat(4)+photographer_last_name+'</td>\n'+
                            '<td>'+photographer_phone+'</td>\n' +
                            '<td>'+photographer_email+'</td>\n' +
                            '<td><i style="color: #EA4335;"  class="fa fa-trash deletePhotographer" data-id = '+photographer_id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editPhotographer" data-id = '+photographer_id+'></i></td>\n' +
                            '</tr>';
                        $('.photographertableAppend tbody').prepend(html);

                    }
                    else
                    {
                        $.each(results, function(index,row)
                        {
                            //console.log(results[0].index);
                            html    += '<tr id='+row.id+'>\n' +
                                '<td>'+row.first_name+ '   '.repeat(4)+row.last_name+'</td>\n'+
                                '<td>'+row.phone+'</td>\n' +
                                '<td>'+row.email+'</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editPhotographer" data-id = '+row.id+'></i></td>\n' +
                                '</tr>';
                        });
                        $('.photographertableAppend tbody').html(html);
                    }
                    $('#photographerForm').trigger("reset");
                },
                error: function (results) {
                    console.log('Error:', results);
                    //$('#saveChild').html('Save Changes');
                }
            });
        });
    }
    addPhotographer();
    // End of Photographer
    ///////////////////////


    //// show Photographer function

    function showPhotographer()
    {
        $('body').on('click', '.editPhotographer', function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id =($(this).attr("data-id"));
            var url = "{{URL('/photographers')}}";;
            var newPath = url+ "/"+ id+"/edit/";
            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results) {
                    var parsedata = JSON.parse(results)[0];
                    $('#photo_id').val(parsedata.id);
                    $('#photographer_site_id').val(parsedata.site_id);
                    $('#photographer_submit_actions').val('Edit');
                    $('#photographer_first_name').val(parsedata.first_name);
                    $('#photographer_mid_name').val(parsedata.mid_name);
                    $('#photographer_last_name').val(parsedata.last_name);
                    $('#photographer_phone').val(parsedata.phone);
                    $('#photographer_email').val(parsedata.email);
                }
            });
        });
    }
    showPhotographer();

    // Add New Coordinator
    function addCoordinator()
    {
        $("#coordinatorForm").submit(function(e) {
            var c_first_name   = $('#c_first_name').val();
            var c_mid_name   = $('#c_mid_name').val();
            var c_last_name  = $('#c_last_name').val();
            var c_phone      = $('#c_phone').val();
            var c_email      = $('#c_email').val();
            var c_id = $('#c_id').val();
            var c_submit_actions = $('#c_submit_actions').val();
            $('#coordinatorForm').find($('input[name="site_id"]').val($('#site_id').val()));

            if(c_submit_actions == 'Add')
            {
                var action_url = "{{ route('coordinator.store') }}";
            }
            else
            {
                var action_url = "{{ route('updateCoordinator') }}";
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                data: $('#coordinatorForm').serialize(),
                url: action_url,
                type: "POST",
                dataType: 'json',
                success: function (results) {
                    //console.log(results);
                    var coordinator_id = results[0].id;
                    var html    =   '';
                    if(c_submit_actions == 'Add')
                    {
                        html    += '<tr id= '+coordinator_id+'>\n' +
                            '<td>'+c_first_name + '   '.repeat(4)+c_last_name+'</td>\n'+
                            '<td>'+c_phone+'</td>\n' +
                            '<td>'+c_email+'</td>\n' +
                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+coordinator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editCoordinator" data-id ='+coordinator_id+'></i></td>\n' +
                            '</tr>';
                        $('.CtableAppend tbody').prepend(html);
                    }
                    else
                    {
                        $.each(results, function(index,row)
                        {
                            console.log(results[0].index);
                            html    += '<tr id= '+row.id+'>\n' +
                                '<td>'+row.first_name + '   '.repeat(4)+row.last_name+'</td>\n'+
                                '<td>'+row.phone+'</td>\n' +
                                '<td>'+row.email+'</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editCoordinator" data-id ='+row.id+'></i></td>\n' +
                                '</tr>';
                        });
                        $('.CtableAppend tbody').html(html);

                    }
                    $('#coordinatorForm').trigger("reset");
                },
                error: function (results) {
                    console.log('Error:', results);
                    //$('#saveChild').html('Save Changes');
                }
            });
        });
    }
    addCoordinator();
    // End of Coordinator

    function showOthers()
    {
        $('body').on('click', '.editOthers', function (e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id =($(this).attr("data-id"));
            var url = "{{URL('/others')}}";
            var newPath = url+ "/"+ id+"/edit/";

            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results) {
                    var parsedata = JSON.parse(results)[0];
                    console.log(parsedata);
                    $('#others_id').val(parsedata.id);
                    $('#others_site_id').val(parsedata.site_id);
                    $('#others_submit_actions').val('Edit');
                    $('#others_first_name').val(parsedata.first_name);
                    $('#others_mid_name').val(parsedata.mid_name);
                    $('#others_last_name').val(parsedata.last_name);
                    $('#others_phone').val(parsedata.phone);
                    $('#others_email').val(parsedata.email);
                }
            });
        });
    }

    showOthers();

    // Add New Others
    function addOthers()
    {
        $("#othersForm").submit(function(e) {
            var others_first_name = $('#others_first_name').val();
            var others_mid_name   = $('#others_mid_name').val();
            var others_last_name  = $('#others_last_name').val();
            var others_phone      = $('#others_phone').val();
            var others_email      = $('#others_email').val();
            var others_id         = $('#others_id').val();
            var others_submit_actions = $('#others_submit_actions').val();
            $('#othersForm').find($('input[name="site_id"]').val($('#site_id').val()));
            if(others_submit_actions == 'Add')
            {
                var action_url = "{{ route('others.store') }}";
            }
            else
            {
                var action_url = "{{ route('updateOthers') }}";
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                data: $('#othersForm').serialize(),
                url: action_url,
                type: "POST",
                dataType: 'json',
                success: function (results) {
                    var others_id = results[0].id;

                    var html    =   '';

                    if(others_submit_actions == 'Add')
                    {
                        html += '<tr id=' + others_id + '>\n' +
                            '<td>' + others_first_name + '   '.repeat(4) + others_last_name + '</td>\n' +
                            '<td>' + others_phone + '</td>\n' +
                            '<td>' + others_email + '</td>\n' +
                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + others_id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + others_id + ' class="icon-pencil editOthers"></i></td>\n' +
                            '</tr>';

                        $('.otherstableAppend tbody').prepend(html);

                    }
                    else
                    {

                        $.each(results, function(index,row)
                        {
                            //console.log(results[0].index);
                            html += '<tr id=' + row.id + '>\n' +
                                '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                '<td>' + row.phone + '</td>\n' +
                                '<td>' + row.email + '</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editOthers"></i></td>\n' +
                                '</tr>';
                        });

                        $('.otherstableAppend tbody').html(html);

                    }

                    $('#othersForm').trigger("reset");
                },
                error: function (results) {
                    console.log('Error:', results);
                    //$('#saveChild').html('Save Changes');
                }
            });
        });
    }
    addOthers();
    // End of Others


    // Add New Site Info
    function addSiteInfo()
    {
        $("#siteInfoForm").submit(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                data: $('#siteInfoForm').serialize(),
                url: "{{ route('sites.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (results) {
                    if (results.success)
                    {
                        $('.success-msg-sec').html('');
                        $('.success-msg-sec').html(results.success)
                        $('.success-alert-sec').slideDown('slow');
                        tId=setTimeout(function(){
                            $(".success-alert-sec").slideUp('slow');
                        }, 3000);
                        $("#siteInfoForm :input").prop("disabled", true);
                        $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after inserts
                        // $('#primaryInvestigatorForm').find($('input[name="site_id"]').val(results.site_id));
                        $('#site_id').val(results.site_id);
                    }

                    // if (results.code)
                    // {
                    //     $('.success-msg-sec').html('');
                    //     $('.success-msg-sec').html(results.success)
                    //     $('.success-alert-sec').slideDown('slow');
                    //     tId=setTimeout(function(){
                    //         $(".success-alert-sec").slideUp('slow');
                    //     }, 3000);
                    // }
                },
                error: function (results) {
                    console.log('Error:', results);
                    //$('#saveChild').html('Save Changes');
                }
            });
        });
    }
    addSiteInfo();
    // End of Add Site Info


    function checkIfSiteCodeExist()
    {
        $('#site_code').focus(function () {
            var siteCode = $('#site_code').val();
        });
    }

    checkIfSiteCodeExist();

        $('body').on('click', '.editsiterecord', function (e) {
            $('.modal-title').text('Edit Site');
            var id =($(this).attr("data-id"));
            var url = "{{URL('/sites')}}";
            var newPath = url+ "/"+ id+"/edit/";

            var pi_url = "{{URL('/primaryinvestigator')}}";
            var new_pi_url = pi_url+ "/"+ id+"/showSiteId/";

            var co_url = "{{URL('/coordinator')}}";
            var new_co_url = co_url+ "/"+ id+"/showCoordinatorBySiteId/";

            var ph_url = "{{URL('/photographers')}}";
            var new_ph_url = ph_url+ "/"+ id+"/showPhotographerBySiteId/";

            var other_url = "{{URL('/others')}}";
            var new_other_url = other_url+ "/"+ id+"/showOtherBySiteId/";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results) {
                    console.log(results);
                    $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after insert
                    var parsedata = JSON.parse(results)[0];
                    console.log(parsedata);
                    $('#site_id').val(parsedata.id);
                    $('#lastSiteId').val(parsedata.id);
                    $('#site_code').val(parsedata.site_code);
                    $('#site_name').val(parsedata.site_name);
                    $('#fullAddr').val(parsedata.site_address);
                    $('#locality').val(parsedata.site_city);
                    $('#administrative_area_level_1').val(parsedata.site_state);
                    $('#site_phone').val(parsedata.site_phone);
                    $('#country').val(parsedata.site_country);
                    $.ajax({
                        type:"GET",
                        dataType: 'html',
                        url:new_pi_url,
                        success : function(results) {
                            //console.log(results);
                            var parsedata = JSON.parse(results)[0];
                            var html    =   '';
                            $.each(parsedata, function(index,row)
                            {
                                //console.log(parsedata);
                                html    += '<tr id='+row.id+'>\n'+
                                    '<td>'+row.first_name+ '  '.repeat(4)+row.last_name+'</td>\n'+
                                    '<td>'+row.phone+'</td>\n' +
                                    '<td>'+row.email+'</td>\n' +
                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editprimaryinvestigator" data-id ='+row.id+'></i>'+
                                    '</td>\n' +
                                    '</tr>';
                            });
                            $('.primaryInvestigatorTableAppend tbody').html(html);
                            $.ajax({
                                data: $('#coordinatorForm').serialize(),
                                url: new_co_url,
                                type: "GET",
                                dataType: 'html',
                                success: function (results) {
                                    //console.log(results);
                                    var parsedata = JSON.parse(results)[0];
                                    var html    =   '';
                                    $.each(parsedata, function(index,row)
                                    {
                                        html    += '<tr id= '+row.id+'>\n' +
                                            '<td>'+row.first_name + '   '.repeat(4)+row.last_name+'</td>\n'+
                                            '<td>'+row.phone+'</td>\n' +
                                            '<td>'+row.email+'</td>\n' +
                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editCoordinator" data-id ='+row.id+'></i></td>\n' +
                                            '</tr>';
                                    });
                                    $('.CtableAppend tbody').html(html);
                                    $.ajax({
                                        data: $('#photographerForm').serialize(),
                                        url: new_ph_url,
                                        type: "GET",
                                        dataType: 'html',
                                        success: function (results) {
                                            //$('.photographertableAppend tbody tr').remove();
                                            var parsedata = JSON.parse(results)[0];
                                            $.each(parsedata, function(index,row)
                                            {
                                                //console.log(results[0].index);
                                                html    += '<tr id='+row.id+'>\n' +
                                                    '<td>'+row.first_name+ '   '.repeat(4)+row.last_name+'</td>\n'+
                                                    '<td>'+row.phone+'</td>\n' +
                                                    '<td>'+row.email+'</td>\n' +
                                                    '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" class="icon-pencil editPhotographer" data-id = '+row.id+'></i></td>\n' +
                                                    '</tr>';
                                            });
                                            $('.photographertableAppend tbody').html(html);


                                            $('#photographerForm').trigger("reset");
                                            $.ajax({
                                                type:"GET",
                                                dataType: 'html',
                                                url:new_other_url,
                                                success : function(results) {
                                                    //$('.otherstableAppend tbody tr').remove();
                                                    var parsedata = JSON.parse(results)[0];

                                                    $.each(parsedata, function(index,row)
                                                    {
                                                        html += '<tr id=' + row.id + '>\n' +
                                                            '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                                            '<td>' + row.phone + '</td>\n' +
                                                            '<td>' + row.email + '</td>\n' +
                                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editOthers"></i></td>\n' +
                                                            '</tr>';
                                                    });

                                                    $('.otherstableAppend tbody').html(html);

                                                }
                                            });
                                        },
                                    });
                                },
                                error: function (results) {
                                    console.log('Error:', results);
                                }
                            });
                        }
                    });
                }
            });

        });

    function updateSiteInfo()
    {
        $("#siteInfoForm").submit(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            $.ajax({
                data: $('#siteInfoForm').serialize(),
                url: "{{ route('updateSites') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    //$("#siteInfoForm :input").prop("disabled", true);
                    if (data.success)
                    {
                        $('.success-msg-sec').html('');
                        $('.success-msg-sec').html(data.success)
                        $('.success-alert-sec').slideDown('slow');
                        tId=setTimeout(function(){
                            $(".success-alert-sec").slideUp('slow');
                        }, 3000);
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
    }
    updateSiteInfo();

    //  Coordinator Delete function
    function  coordinatorDestroy ()
    {
        $('body').on('click', '.deleteCoordinator', function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var coordinator_id = $(this).data("id");


            var url = "{{URL('/coordinator/')}}";
            var newPath = url+ "/"+ coordinator_id+"/destroy/";
            if( confirm("Are You sure want to delete !") ==true)
            {
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        $('#'+coordinator_id).remove();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    }
    coordinatorDestroy();



    //  Photographer Delete function

    function  photographerDestroy ()
    {
        $('body').on('click', '.deletePhotographer', function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var photographer_id = $(this).data("id");


            var url = "{{URL('/photographers/')}}";
            var newPath = url+ "/"+ photographer_id+"/destroy/";
            if( confirm("Are You sure want to delete !") ==true)
            {
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        $('#'+photographer_id).remove();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    }
    photographerDestroy();


    //  Delete Others function

    function  othersDestroy ()
    {
        $('body').on('click', '.deleteOthers', function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var others_id = $(this).data("id");


            var url = "{{URL('/others/')}}";
            var newPath = url+ "/"+ others_id+"/destroy/";
            if( confirm("Are You sure want to delete !") ==true)
            {
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        $('#'+others_id).remove();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    }
    othersDestroy();




    ///  Options Delete function
    function  sitesDestroy ()
    {
        $('body').on('click','.deletesiterecord',function(){
            var id = $(this).data('id');
            if (confirm("Are you sure to delete?")) {
                $.ajax({
                    url: 'sites/destroy/'+id,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": 'DELETE',
                        'id': id
                    },
                    success:function(result){
                        console.log(result);
                        window.setTimeout(function () {
                            location.href = "{{ route('sites.index') }}";
                        }, 100);
                    }
                })
            }
        });
    }
    sitesDestroy();

    ///  Delete  Specific Row function



</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEELbGoxVU_nvp6ayr2roHHnjN3hM_uec&libraries=places&callback=initAutocomplete"
            defer></script>
@endsection




