@extends ('layouts.app')
@section('body')
    <div class="container" id ="container">
        <div class="row vh-100 justify-content-between align-items-center">
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                        <button class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            @endif
            <div class="col-12">
                <form  method="POST" class="row row-eq-height lockscreen  mt-5 mb-5" name="responseForm" id="responseForm">
                    @csrf
                    <div class="login-form col-12 col-sm-7">
                        <input type="hidden" id="userAgent" value="">
                        <div class="form-group mb-3" style="margin-top: 60px;">
                            <label for="emailaddress"> <strong>Status</strong></label>
                            <p>access denied contact your administrator</p>
                        </div>

                        <div class="mt-2" style="padding-top: 90px; text-align: center;">OIRRC CAPTURE System</div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@stop
@section('script')
    <script type="text/javascript">
        $("#responseForm").on('submit', function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            var yourName               = $('#yourName').val();
            var yourEmail              = $('#yourEmail').val();
            var yourMessage            = $('#yourMessage').val();
            var subject                = $('#subject').val();
            var parent_notification_id = $('#parent_notification_id').val();
            var formData    = new FormData();
            formData.append('yourName', yourName);
            formData.append('yourEmail', yourEmail);
            formData.append('yourMessage', yourMessage);
            formData.append('subject', subject);
            formData.append('parent_notification_id', parent_notification_id);
            // Attach file
            formData.append('attachment', $('input[type=file]')[0].files[0]);

            $.ajax({

                url:"{{route('transmissions.queryResponseSave')}}",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function(response)
                {
                    console.log(response);
                }
            });
        });
    </script>
@stop
