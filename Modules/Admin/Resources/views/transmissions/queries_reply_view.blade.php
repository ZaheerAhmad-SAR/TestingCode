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
                <form  action="" method="POST" class="row row-eq-height lockscreen  mt-5 mb-5">
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
                            <textarea name="message" id="message" cols="2" rows="1" class="form-control" style="height: 50px;"></textarea>
                        </div>
                        <div class="garbageData">
                            <input type="hidden" name="parent_notification_id" value="{{$record['id']}}">
                        </div>
                        <div class="form-group mb-3">
                                <button class="btn btn-primary" type="submit"> Send </button>
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

    </script>
@stop
