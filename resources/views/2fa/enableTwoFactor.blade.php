@extends('layouts.home')

@section('content')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-12 ">
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
                        <a class="btn btn-outline-warning" href="{{ url('/studies') }}">Go Back</a>
                        <a class="btn btn-outline-danger" onclick="myFunction()">View Codes</a>
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
