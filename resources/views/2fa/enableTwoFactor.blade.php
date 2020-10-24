@extends('layouts.home')

@section('content')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-12 ">
                <div class="login-form col-12 col-sm-7">
                    <div class="panel-heading">2FA Secret Key is </div>

                    <div class="form-group mb-3">
                        Open up your 2FA mobile app and scan the following QR barcode:
                        <br />
                        <img alt="Image of QR barcode" src="{!! $inlineUrl !!}" />

                        <br />
                        If your 2FA mobile app does not support QR barcodes,
                        enter in the following number: <code>{{ $secret }}</code>
                        <br /><br />
                        <a class="btn btn-outline-warning" href="{{ url('/studies') }}">Go Backgggggggggggggggggggggggg</a>
                        <a class="btn btn-outline-success" href="{{ url('/backup-codes') }}">View Codes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@stop
