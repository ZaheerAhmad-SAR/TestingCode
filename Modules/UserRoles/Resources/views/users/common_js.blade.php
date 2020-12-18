<script>
function submitActivateUserRequest(userId) {
    $.ajax({
        url: 'systemUser/activate_user/',
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'POST',
            'userId': userId,
        },
        success: function(res) {
            var spanHtml = '<span class="dropdown-item inActivateUser" onclick="submitInActivateUserRequest(\'' +
                userId + '\');"><i class="far fa-pause-circle"></i>&nbsp; Inactivate User</span>';
            $('#userActiveStatusDiv_' + userId).html(spanHtml);
        }
    });
}
function submitInActivateUserRequest(userId) {
    $.ajax({
        url: 'systemUser/inactivate_user/',
        type: 'POST',
        data: {
            "_token": "{{ csrf_token() }}",
            "_method": 'POST',
            'userId': userId,
        },
        success: function(res) {
            var spanHtml = '<span class="dropdown-item activateUser" onclick="submitActivateUserRequest(\'' +
                userId + '\');"><i class="far fa-play-circle"></i>&nbsp; Activate User</span>';
            $('#userActiveStatusDiv_' + userId).html(spanHtml);
        }
    });
}
</script>
