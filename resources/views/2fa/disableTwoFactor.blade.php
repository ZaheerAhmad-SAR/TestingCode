@extends('layouts.home')

@section('content')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">2FA Secret Key</div>

                    <div class="panel-body">
                        <div class="form-group mb-3" style="margin-top: 60px;">
                        2FA has been removed
                        </div>
                        <br /><br />
                        <a href="{{ url('/home') }}">Go Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@endsection

