@extends('layouts.home')

@section('content')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-md-12">
                <div class="row row-eq-height lockscreen  mt-5 mb-5">
                <div class="lock-image col-12 col-sm-7">
                    <div class="form-group mb-3">
                        <label>2FA Secret Key is </label>
                        <p>Open up your 2FA mobile app and scan the following QR barcode:
                        </p>
                        <img alt="Image of QR barcode" src="{!! $inlineUrl !!}" />
                        <br />
                        <p>If your 2FA mobile app does not support QR barcodes,
                        enter in the following number: <code>{{ $secret }}</code>
                        </p>
                        {{--<a class="btn btn-outline-warning" href="{{ url('/studies') }}">Go Back</a>--}}
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#verify2fa">
                            <i class="fa fa-plus"></i> Verify
                        </button>
                    </div>
                </div>
                    <div class="login-form col-12 col-sm-5" id="myDIV" style="display: none">
                        <div class="form-group mb-3">
                            <div class="col-md-6">
                                <p>Your Backup Codes are</p>
                            </div>
                            <div class="col-md-6">
                                @foreach($codes as $code)
                                    <li>{{$code->backup_code}}</li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="verify2fa">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Enter OTP</p>
                </div>
                <form class="row row-eq-height mt-5 mb-5" role="form" method="POST" action="{{url('/2fa/validate')}}">
                    {!! csrf_field() !!}
                    <div class="login-form col-12 col-sm-7">
                        <div style="margin-top: 15px" class="form-group row{{ $errors->has('totp') ? ' has-error' : '' }}">
                            <div class="col-md-2">
                                <label>OTP</label></div>

                            <div class="col-md-10">
                                <input type="number" class="form-control" name="totp" required>
                                @if ($errors->has('totp'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('totp') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="col-md-6 col-md-offset-4">
                                <button onclick="myFunction()" type="submit" class="btn btn-primary">
                                    Validate
                                </button>
                            </div>
                        </div>
                        <div class="login-form col-12 col-sm-5" id="myDIV" style="display: none">
                            <div class="form-group mb-3">
                                <div class="col-md-6">
                                    <p>Your Backup Codes are</p>
                                </div>
                                <div class="col-md-6">
                                    @foreach($codes as $code)
                                        <li>{{$code->backup_code}}</li>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@stop
@section('script')
    <script type="text/javascript">
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
@endsection
