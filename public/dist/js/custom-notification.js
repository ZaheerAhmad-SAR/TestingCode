
$('.currentNotificationId').click(function () {
    var currentNotificationId  = $(this).attr('data-value');
    var query_url              = $(this).attr('data-query_url');
    var study_id               = $(this).attr('data-study_id');
    var study_code             = $(this).attr('data-study_code');
    var study_short_name       = $(this).attr('data-study_short_name');
    updateNotificationToRead(currentNotificationId,query_url,study_id,study_code,study_short_name);
});


$('.currentNotificationBugId').click(function () {
    var currentNotificationId  = $(this).attr('data-value');
    var query_url              = $(this).attr('data-query_url');
    var study_id               = $(this).attr('data-study_id');
    var study_code             = $(this).attr('data-study_code');
    var study_short_name       = $(this).attr('data-study_short_name');
    updateNotificationToRead(currentNotificationId,query_url,study_id,study_code,study_short_name);
});




function updateNotificationToRead(currentNotificationId,query_url,study_id,study_code,study_short_name) {
    $.ajax({
        url:"{{route('notifications.update')}}",
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'POST',
            'currentNotificationId' :currentNotificationId,
            'query_url' :query_url,
            'study_id' :study_id,
            'study_code' :study_code,
            'study_short_name' :study_short_name,
        },
        success: function(response)
        {
            //console.log(response);
            if (response.success)
            {
                var urlPath = response.success;
                window.location.href = urlPath;
            }
        }
    });
}

$('.markAllRead').click(function () {

    $.ajax({
        url:"{{route('notifications.markAllNotificationToRead')}}",
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'POST'
        },
        success: function(response)
        {
            console.log(response);
            location.reload();
        }
    });
});


function loadnotifications() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url:"{{route('notifications.countUserNotification')}}",
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'POST',
        },
        success: function(response)
        {
            //$('.updateListItems').html('');
            $('.updateListItems').html(response);
        }
    });
}
// loadnotifications();
//
// setInterval(function(){
//     loadnotifications()
//
// }, 10000);
