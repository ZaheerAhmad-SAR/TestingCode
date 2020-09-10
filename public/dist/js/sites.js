
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
function addPrimaryInvestigator()
{
    $("#primaryInvestigatorForm").submit(function(e) {
        var first_name        = $('#pi_first_name').val();
        var p_mid_name        = $('#pi_mid_name').val();
        var p_last_name       = $('#pi_last_name').val();
        var p_phone           = $('#pi_phone').val();
        var p_email           = $('#pi_email').val();
        var pi_id             = $('#pi_id').val();
        var pi_submit_actions = $('#pi_submit_actions').val();
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
                var primary_investigator_id = results[0].id;

                var html    =   '';

                if(pi_submit_actions == 'Add') {

                    html    += '<tr id='+primary_investigator_id+'>\n'+
                        '<td>'+first_name + '   '.repeat(4)+p_last_name+'</td>\n'+
                        '<td>'+p_phone+'</td>\n' +
                        '<td>'+p_email+'</td>\n' +
                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+primary_investigator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editprimaryinvestigator" data-id ='+primary_investigator_id+'></i>'+
                        '</td>\n' +
                        '</tr>';

                    $('.primaryInvestigatorTableAppend tbody').prepend(html);
                }
                else{
                    $.each(results, function(index,row)
                    {
                        html    += '<tr id='+row.id+'>\n'+
                            '<td>'+row.first_name + '  '.repeat(4)+row.last_name+'</td>\n'+
                            '<td>'+row.phone+'</td>\n' +
                            '<td>'+row.email+'</td>\n' +
                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editprimaryinvestigator" data-id ='+row.id+'></i>'+
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


// Primary investigator Delete function
function primaryinvestigatorDestroy()
{
    $('body').on('click', '.deleteprimaryinvestigator', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var primary_investigator_id = $(this).data("id");
        var url = "{{URL('primaryinvestigator')}}";
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

function resetprimaryinvestigatorForm()
{
    $("#rest_pi_button").click(function(){
        $("#pi_submit_actions").attr('value', 'Add');
        $("#primaryInvestigatorForm").trigger("reset");
    });
}

resetprimaryinvestigatorForm();

function resetcoordinatorForm()
{
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

//// show Primary Investigator function

function showPrimaryInvestigator()
{
    $('body').on('click', '.editprimaryinvestigator', function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id =($(this).attr("data-id"));
        var url = "{{URL('/ocap/primaryinvestigator')}}";
        var newPath = url+ "/"+ id+"/edit/";
        $.ajax({
            type:"GET",
            dataType: 'html',
            url:newPath,
            success : function(results) {
                var parsedata = JSON.parse(results)[0];
                $('#pi_id').val(parsedata.id);
                $('#site_id').val(parsedata.site_id);
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
                        '<td><i style="color: #EA4335;"  class="fa fa-trash deletePhotographer" data-id = '+photographer_id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editPhotographer" data-id = '+photographer_id+'></i></td>\n' +
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
                            '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editPhotographer" data-id = '+row.id+'></i></td>\n' +
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
        var url = "{{URL('/ocap/photographers')}}";;
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
                var coordinator_id = results[0].id;
                console.log(coordinator_id);
                var html    =   '';
                if(c_submit_actions == 'Add')
                {
                    html    += '<tr id= '+coordinator_id+'>\n' +
                        '<td>'+c_first_name + '   '.repeat(4)+c_last_name+'</td>\n'+
                        '<td>'+c_phone+'</td>\n' +
                        '<td>'+c_email+'</td>\n' +
                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+coordinator_id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editCoordinator" data-id ='+coordinator_id+'></i></td>\n' +
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
                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editCoordinator" data-id ='+row.id+'></i></td>\n' +
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

//// show Coordinator function

function showCoordinator()
{
    $('body').on('click', '.editCoordinator', function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id =($(this).attr("data-id"));
        var url = "{{URL('/ocap/coordinator')}}";
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

function showOthers()
{
    $('body').on('click', '.editOthers', function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id =($(this).attr("data-id"));
        var url = "{{URL('/ocap/others')}}";
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
                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + others_id + '></i>&nbsp;&nbsp;<i style="color: #34A853;" data-id = ' + others_id + ' class="fa fa-pencil-square-o editOthers"></i></td>\n' +
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
                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853;" data-id = ' + row.id + ' class="fa fa-pencil-square-o editOthers"></i></td>\n' +
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
                $("#siteInfoForm :input").prop("disabled", true);
                $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after insert
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
        console.log(siteCode);
    });
}

checkIfSiteCodeExist();


function editSiteInfo() {

    $('body').on('click', '.editsiterecord', function (e) {
        $('.modal-title').text('Edit Site');
        var id =($(this).attr("data-id"));

        var url = "{{URL('/ocap/sites')}}";
        var newPath = url+ "/"+ id+"/edit/";

        var pi_url = "{{URL('/ocap/primaryinvestigator')}}";
        var new_pi_url = pi_url+ "/"+ id+"/showSiteId/";

        var co_url = "{{URL('/ocap/coordinator')}}";
        var new_co_url = co_url+ "/"+ id+"/showCoordinatorBySiteId/";

        var ph_url = "{{URL('/ocap/photographers')}}";
        var new_ph_url = ph_url+ "/"+ id+"/showPhotographerBySiteId/";

        var other_url = "{{URL('/ocap/others')}}";
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
                $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after insert
                var parsedata = JSON.parse(results)[0];
                $('#site_id').val(parsedata.id);
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
                        var parsedata = JSON.parse(results)[0];
                        var html    =   '';
                        $.each(parsedata, function(index,row)
                        {
                            html    += '<tr id='+row.id+'>\n'+
                                '<td>'+row.first_name + '  '.repeat(4)+row.last_name+'</td>\n'+
                                '<td>'+row.phone+'</td>\n' +
                                '<td>'+row.email+'</td>\n' +
                                '<td><i style="color: #EA4335;" class="fa fa-trash deleteprimaryinvestigator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editprimaryinvestigator" data-id ='+row.id+'></i>'+
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
                                var parsedata = JSON.parse(results)[0];
                                var html    =   '';
                                $.each(parsedata, function(index,row)
                                {
                                    html    += '<tr id= '+row.id+'>\n' +
                                        '<td>'+row.first_name + '   '.repeat(4)+row.last_name+'</td>\n'+
                                        '<td>'+row.phone+'</td>\n' +
                                        '<td>'+row.email+'</td>\n' +
                                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteCoordinator" data-id ='+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editCoordinator" data-id ='+row.id+'></i></td>\n' +
                                        '</tr>';
                                });
                                $('.CtableAppend tbody').html(html);
                                $.ajax({
                                    data: $('#photographerForm').serialize(),
                                    url: new_ph_url,
                                    type: "GET",
                                    dataType: 'html',
                                    success: function (results) {
                                        var parsedata = JSON.parse(results)[0];
                                        $.each(parsedata, function(index,row)
                                        {
                                            //console.log(results[0].index);
                                            html    += '<tr id='+row.id+'>\n' +
                                                '<td>'+row.first_name+ '   '.repeat(4)+row.last_name+'</td>\n'+
                                                '<td>'+row.phone+'</td>\n' +
                                                '<td>'+row.email+'</td>\n' +
                                                '<td><i style="color: #EA4335;" class="fa fa-trash deletePhotographer" data-id = '+row.id+'></i>&nbsp;&nbsp;<i style="color: #34A853;" class="fa fa-pencil-square-o editPhotographer" data-id = '+row.id+'></i></td>\n' +
                                                '</tr>';
                                        });
                                        $('.photographertableAppend tbody').html(html);

                                        $('#photographerForm').trigger("reset");
                                        $.ajax({
                                            type:"GET",
                                            dataType: 'html',
                                            url:new_other_url,
                                            success : function(results) {
                                                var parsedata = JSON.parse(results)[0];

                                                $.each(parsedata, function(index,row)
                                                {
                                                    html += '<tr id=' + row.id + '>\n' +
                                                        '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                                        '<td>' + row.phone + '</td>\n' +
                                                        '<td>' + row.email + '</td>\n' +
                                                        '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853;" data-id = ' + row.id + ' class="fa fa-pencil-square-o editOthers"></i></td>\n' +
                                                        '</tr>';
                                                });

                                                $('.otherstableAppend tbody').html(html);

                                            }
                                        });
                                    },
                                    error: function (results) {
                                        console.log('Error:', results);
                                    }
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

}
editSiteInfo();



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
                $("#siteInfoForm :input").prop("disabled", true);

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


        var url = "{{URL('coordinator/')}}";
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


        var url = "{{URL('photographers/')}}";
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


        var url = "{{URL('others/')}}";
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

