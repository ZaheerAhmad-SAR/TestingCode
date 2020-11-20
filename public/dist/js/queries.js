$(window).on( "load", function() {
    console.log(document.URL);
    // $.ajaxSetup({
    //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    // });
    // var url = "{{URL('/queries')}}";
    // $.ajax({
    //     type:"GET",
    //     dataType: 'json',
    //     url:url,
    //     success : function(results) {
    //         console.log(results);
    //         // var parsedata = JSON.parse(results)[0];
    //         // $('#pi_id').val(parsedata.id);
    //         // $('#site_id').val(parsedata.site_id);
    //         // $('#pi_submit_actions').val('Edit');
    //         // $('#pi_first_name').val(parsedata.first_name);
    //         // $('#pi_mid_name').val(parsedata.mid_name);
    //         // $('#pi_last_name').val(parsedata.last_name);
    //         // $('#pi_phone').val(parsedata.phone);
    //         // $('#pi_email').val(parsedata.email);
    //     }
    // });
});
