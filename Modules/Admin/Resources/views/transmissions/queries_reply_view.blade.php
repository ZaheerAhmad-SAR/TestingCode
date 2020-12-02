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
                            <label for="emailaddress"> <strong>Query</strong></label>
                            <p>{{$record['email_body']}}</p>
                        </div>

                        <div class="form-group mb-3">
                            <label for="emailaddress"><strong>Name</strong></label>
                            <input type="text" name="yourName" id="yourName" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress"><strong>Email</strong></label>
                            <input type="email" name="yourEmail" id="yourEmail" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="Attachment"><strong>Attachment</strong>:</label>
                                <input class="form-control" type="file" name="attachment"  id="attachment">
                        </div>

                        <div class="form-group mb-3">
                            <label for="emailaddress"><strong>Your Response</strong></label>
                            <textarea name="yourMessage" id="yourMessage" cols="2" rows="1" class="form-control" style="height: 50px;"></textarea>
                        </div>
                        <div class="garbage">
                            <input type="hidden" name="parent_notification_id" id="parent_notification_id" value="{{$record['id']}}">
                            <input type="hidden" name="subject" id="subject" value="{{$record['subject']}}">
                            <input type="hidden" name="notifications_token" id="notifications_token" value="{{$record['notifications_token']}}">
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" name="sendEmail" class="btn btn-primary" id="sendEmail"><i class="fa fa-save"></i> Send</button>
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
            var notifications_token    = $('#notifications_token').val();
            var parent_notification_id = $('#parent_notification_id').val();
            var formData               = new FormData();
            formData.append('yourName', yourName);
            formData.append('yourEmail', yourEmail);
            formData.append('yourMessage', yourMessage);
            formData.append('subject', subject);
            formData.append('notifications_token', notifications_token);
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
                    $("#responseForm")[0].reset();
                }
            });
        });
    </script>
@stop
