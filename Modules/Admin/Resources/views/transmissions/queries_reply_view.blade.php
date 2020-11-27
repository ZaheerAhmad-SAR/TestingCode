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
                    <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                        <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                    </div>
                    <div class="login-form col-12 col-sm-7">
                        <input type="hidden" id="userAgent" value="">
                        <div class="form-group mb-3" style="margin-top: 60px;">
                            <label for="emailaddress">Query</label>
                            <p>{{$record['email_body']}}</p>
                        </div>

                        <div class="form-group mb-3">
                            <label for="emailaddress">Your Response</label>
                            <textarea name="message" id="message" cols="2" rows="1" class="form-control" style="height: 50px;"></textarea>
                        </div>
                        <div class="garbageData">
                            <input type="hidden" name="cc_email" value="{{$record['cc_email']}}">
                            <input type="hidden" name="parent_notification_id" value="{{$record['id']}}">
                            <input type="hidden" name="cc_email" value="{{$record['cc_email']}}">
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <button class="btn btn-primary" type="submit" style="float: right;"> Send </button>
                            </div>
                        </div>

                        <div class="mt-2" style="padding-top: 90px;">OIRRC CAPTURE System</div>
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
