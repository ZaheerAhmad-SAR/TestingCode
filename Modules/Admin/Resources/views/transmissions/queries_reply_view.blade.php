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
                            <input type="hidden" name="emailSubject" id="emailSubject" value="{{$record['subject']}}">
                            <input type="hidden" name="cc_email" id="cc_email" value="{{$record['cc_email'] }}">
                            <input type="hidden" name="study_id" id="study_id" value="{{ $record['study_id'] }}">
                            <input type="hidden" name="subject_id" id="subject_id" value="{{ $record['subject_id'] }}">
                            <input type="hidden" name="transmission_number" id="transmission_number" value="{{ $record['transmission_number'] }}">
                            <input type="hidden" name="vist_name" id="vist_name" value="{{ $record['vist_name'] }}">
                            <input type="hidden" name="notifications_token" id="notifications_token" value="{{$record['notifications_token']}}">
                            <input type="hidden" name="site_name" id="site_name" value="{{$record['site_name']}}">
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
            var emailSubject           = $('#emailSubject').val();
            var cc_email               = $('#cc_email').val();
            var study_id               = $('#study_id').val();
            var subject_id             = $('#subject_id').val();
            var transmission_number    = $('#transmission_number').val();
            var vist_name              = $('#vist_name').val();
            var notifications_token    = $('#notifications_token').val();
            var parent_notification_id = $('#parent_notification_id').val();
            var site_name              = $('#site_name').val();
            var formData               = new FormData();
            formData.append('yourName', yourName);
            formData.append('yourEmail', yourEmail);
            formData.append('yourMessage', yourMessage);
            formData.append('emailSubject', emailSubject);
            formData.append('cc_email', cc_email);
            formData.append('study_id', study_id);
            formData.append('subject_id', subject_id);
            formData.append('transmission_number', transmission_number);
            formData.append('vist_name', vist_name);
            formData.append('site_name', site_name);
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
