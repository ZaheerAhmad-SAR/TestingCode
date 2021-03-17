
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
        var action_url = "{{ route('primaryinvestigator.update') }}";
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

// End of primary Investigator

// *************************************************************************************** //

// Primary Investigator Delete function

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


// function reset primary investigator Form
$("#rest_pi_button").click(function(){
    $("#pi_submit_actions").attr('value', 'Add');
    $("#primaryInvestigatorForm").trigger("reset");
});


// function reset coordinator Form

$("#reset_c_button").click(function(){
    $("#c_submit_actions").attr('value', 'Add');
    $("#coordinatorForm").trigger("reset");
});


/// function reset photographer Form

$("#reset_photographer_button").click(function(){
    $("#photographer_submit_actions").attr('value', 'Add');
    $("#photographerForm").trigger("reset");
});


/// function reset others Form

$("#reset_others_button").click(function(){
    $("#others_submit_actions").attr('value', 'Add');
    $("#othersForm").trigger("reset");
});


/// function reset Device Site  Form

$("#reset_device_button").click(function(){
    $("#device_submit_actions").attr('value', 'Add');
    $("#devicesForm").trigger("reset");
});


//// show Coordinator function

$('body').on('click', '.editCoordinator', function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id =($(this).attr("data-id"));

    var url = "{{URL('coordinator')}}";

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



//// show Primary Investigator function
$('body').on('click', '.editprimaryinvestigator', function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id =($(this).attr("data-id"));

    var url = "{{URL('primaryinvestigator')}}";

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


// Add New Photographer

$("#photographerForm").submit(function(e) {

    var photographer_first_name     = $('#photographer_first_name').val();
    var photographer_mid_name       = $('#photographer_mid_name').val();
    var photographer_last_name      = $('#photographer_last_name').val();
    var photographer_phone          = $('#photographer_phone').val();
    var photographer_email          = $('#photographer_email').val();
    var photo_id                    = $('#photo_id').val();
    var photographer_submit_actions = $('#photographer_submit_actions').val();
    console.log(photographer_submit_actions);
    $('#photographerForm').find($('input[name="site_id"]').val($('#site_id').val()));
    if(photographer_submit_actions  == 'Add')
    {
        var action_url = "{{ route('photographers.store') }}";
    }
    else
    {
        var action_url = "{{ route('photographers.update') }}";
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

// End of Photographer
///////////////////////


//// show Photographer function

$('body').on('click', '.editPhotographer', function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id =($(this).attr("data-id"));

    var url = "{{URL('photographers')}}";;

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

//// end of show Photographer function

// Add New Coordinator

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
        var action_url = "{{ route('coordinator.update') }}";
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

// End of Add Coordinator


$("#devicesForm").submit(function(e) {
    var device_name = $('#device_name').val();
    var device_serial_no   = $('#device_serial_no').val();
    var device_id         = $('#device_id').val();
    var device_submit_actions = $('#device_submit_actions').val();
    $('#devicesForm').find($('input[name="site_id"]').val($('#site_id').val()));
    if(device_submit_actions == 'Add')
    {
        var action_url = "{{ route('deviceSite.store') }}";
    }
    else
    {
        var action_url = "{{ route('deviceSite.update') }}";
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();
    $.ajax({
        data: $('#devicesForm').serialize(),
        url: action_url,
        type: "POST",
        dataType: 'json',
        success: function (results) {
            var device_id = results[0].id;
            console.log(results);
            var html    =   '';

            if(device_submit_actions == 'Add')
            {
                html += '<tr id=' + device_id + '>\n' +
                    '<td>' + device_name + '</td>\n' +
                    '<td>' + device_serial_no + '</td>\n' +
                    '<td><i style="color: #EA4335;" class="fa fa-trash deletedevice" data-id =' + device_id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + device_id + ' class="icon-pencil editdevice"></i></td>\n' +
                    '</tr>';

                $('.deviceSiteTableAppend tbody').prepend(html);

            }
            else
            {

                $.each(results, function(index,row)
                {
                    console.log(results[0].index);
                    console.log('foreach sa neechay wala console')
                    html += '<tr id=' + row.id + '>\n' +
                        '<td>' + row.device_name + '</td>\n' +
                        '<td>' + row.device_serial_no + '</td>\n' +
                        '<td><i style="color: #EA4335;" class="fa fa-trash deletedevice" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editdevice"></i></td>\n' +
                        '</tr>';
                });

                $('.deviceSiteTableAppend tbody').html(html);

            }

            $('#devicesForm').trigger("reset");
        },
        error: function (results) {
            console.log('Error:', results);
            //$('#saveChild').html('Save Changes');
        }
    });
});

// Edit Others

$('body').on('click', '.editOthers', function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id =($(this).attr("data-id"));

    var url = "{{URL('others')}}";

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


$('body').on('click', '.editdevice', function (e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var id =($(this).attr("data-id"));

    var url = "{{URL('deviceSite')}}";

    var newPath = url+ "/"+ id+"/edit/";

    $.ajax({
        type:"GET",
        dataType: 'html',
        url:newPath,
        success : function(results) {
            var parsedata = JSON.parse(results)[0];
            $('#device_id').val(parsedata.id);
            $('#device_site_id').val(parsedata.site_id);
            $('#device_submit_actions').val('Edit');
            $('#device_name').val(parsedata.device_name);
            $('#device_serial_no').val(parsedata.device_serial_no);

        }
    });
});



// Add New Others

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
        var action_url = "{{ route('others.update') }}";
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

// End of Others

$('.variable_name_ques').keydown(function(e) {
    if (e.keyCode == 32) {
        $('.variable_name_ques').css('border', '1px solid red');
        $('.space_msg').html('Space Not Allowed!!')
        e.preventDefault();
    } else {
        $('.variable_name_ques').css('border', '');
        $('.space_msg').html('');
        return true;
    }
})

function  siteCodeValue(data)
{
    var siteCode  = data.value;


    $.ajax({
        url:"{{route('sites.checkIfSiteIsExist')}}",
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            'siteCode'      :siteCode,
        },
        success: function(results)
        {
            if (results.success)
            {
                $('.success-msg-sec').html('');
                $('.success-msg-sec').html(results.success)
                $('.success-alert-sec').slideDown('slow');
                tId=setTimeout(function(){
                    $(".success-alert-sec").slideUp('slow');
                }, 3000);
                $('#site_code').val('');
                $("#site_code").focus();

            }

        }
    });

}

$("#siteInfoForm").submit(function(e) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();

    var sites_submit_actions = $('#sites_submit_actions').val();
    if(sites_submit_actions == 'Add')
    {
        var action_url = "{{ route('sites.store') }}";
    }
    else
    {
        var action_url = "{{ route('sites.updateSites') }}";
    }
    $.ajax({
        data: $('#siteInfoForm').serialize(),
        url: action_url,
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
                //$("#siteInfoForm :input").prop("disabled", true);
                $('.addTabs').attr("data-toggle","tab"); // Add data-toggle tab after inserts
                // $('#primaryInvestigatorForm').find($('input[name="site_id"]').val(results.site_id));
                $('#site_id').val(results.site_id);
            }
        },
        error: function (results) {
            console.log('Error:', results);
            //$('#saveChild').html('Save Changes');
        }
    });
});

$('#siteModal').on('hidden.bs.modal', function () {
    location.reload();
});


$(document).on('shown.bs.modal', '.modal', function() {
    $(this).find('[autofocus]').focus();
});

$('body').on('click', '.editsiterecord', function (e) {
    $('.modal-title').text('Edit Site');
    $("#sites_submit_actions").attr('value', 'Edit');
    var id =($(this).attr("data-id"));

    var url = "{{URL('sites')}}";
    var newPath = url+ "/"+ id+"/edit/";

    var pi_url = "{{URL('primaryinvestigator')}}";
    var new_pi_url = pi_url+ "/"+ id+"/showSiteId/";

    var co_url = "{{URL('coordinator')}}";
    var new_co_url = co_url+ "/"+ id+"/showCoordinatorBySiteId/";

    var ph_url = "{{URL('photographers')}}";
    var new_ph_url = ph_url+ "/"+ id+"/showPhotographerBySiteId/";

    var other_url = "{{URL('others')}}";

    var new_other_url = other_url+ "/"+ id+"/showOtherBySiteId/";


    var deviceSite = "{{URL('deviceSite')}}";

    var new_deviceSite_url = deviceSite+ "/"+ id+"/showDeviceBySiteId/";


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
            //$('#sites_submit_actions').val('Edit');
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
                    $('.primaryInvestigatorTableAppend tbody').html('');
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
                            $('.CtableAppend tbody').html('');
                            $('.CtableAppend tbody').html(html);
                            $.ajax({
                                data: $('#photographerForm').serialize(),
                                url: new_ph_url,
                                type: "GET",
                                dataType: 'html',
                                success: function (results) {
                                    //$('.photographertableAppend tbody tr').remove();
                                    var parsedata = JSON.parse(results)[0];
                                    var html    =   '';
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
                                    $('.photographertableAppend tbody').html('');
                                    $('.photographertableAppend tbody').html(html);
                                    $('#photographerForm').trigger("reset");
                                    $.ajax({
                                        type:"GET",
                                        dataType: 'html',
                                        url:new_other_url,
                                        success : function(results) {
                                            //$('.otherstableAppend tbody tr').remove();
                                            var parsedata = JSON.parse(results)[0];
                                            var html    =   '';
                                            $.each(parsedata, function(index,row)
                                            {
                                                html += '<tr id=' + row.id + '>\n' +
                                                    '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                                    '<td>' + row.phone + '</td>\n' +
                                                    '<td>' + row.email + '</td>\n' +
                                                    '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editOthers"></i></td>\n' +
                                                    '</tr>';
                                            });

                                            $('.otherstableAppend tbody').html('');
                                            $('.otherstableAppend tbody').html(html);

                                            $.ajax({
                                                type:"GET",
                                                dataType: 'html',
                                                url:new_other_url,
                                                success : function(results) {
                                                    //$('.otherstableAppend tbody tr').remove();
                                                    var parsedata = JSON.parse(results)[0];
                                                    var html    =   '';
                                                    $.each(parsedata, function(index,row)
                                                    {
                                                        html += '<tr id=' + row.id + '>\n' +
                                                            '<td>' + row.first_name + '   '.repeat(4) + row.last_name + '</td>\n' +
                                                            '<td>' + row.phone + '</td>\n' +
                                                            '<td>' + row.email + '</td>\n' +
                                                            '<td><i style="color: #EA4335;" class="fa fa-trash deleteOthers" data-id =' + row.id + '></i>&nbsp;&nbsp;<i style="color: #34A853; cursor: pointer;" data-id = ' + row.id + ' class="icon-pencil editOthers"></i></td>\n' +
                                                            '</tr>';
                                                    });

                                                    $('.otherstableAppend tbody').html('');
                                                    $('.otherstableAppend tbody').html(html);

                                                    //// Device Site Ajax Start

                                                    $.ajax({
                                                        type:"GET",
                                                        dataType: 'html',
                                                        url:new_deviceSite_url,
                                                        success : function(results) {
                                                            $('.deviceSiteTableAppend tbody').html('');
                                                            $('.deviceSiteTableAppend tbody').html(results);
                                                        }
                                                    });

                                                    //// Device Site Ajax Code End

                                                }

                                            });

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

//  Coordinator Delete function

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




//  Photographer Delete function
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



//  Delete Others function

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


//  Delete Device  function

$('body').on('click', '.deletedevice', function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var device_id = $(this).data("id");

    var url = "{{URL('deviceSite/')}}";

    var newPath = url+ "/"+ device_id+"/destroy/";
    if( confirm("Are You sure want to delete !") ==true)
    {
        $.ajax({
            type: "GET",
            url: newPath,
            success: function (data) {
                $('#'+device_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
});


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


///  Delete  Specific Row function

function changeSort(field_name){
    var sort_by_field = $('#sort_by_field').val();
    if(sort_by_field =='' || sort_by_field =='ASC'){
        $('#sort_by_field').val('DESC');
        $('#sort_by_field_name').val(field_name);
    }else if(sort_by_field =='DESC'){
        $('#sort_by_field').val('ASC');
        $('#sort_by_field_name').val(field_name);
    }
    $('.filter-form').submit();
}
