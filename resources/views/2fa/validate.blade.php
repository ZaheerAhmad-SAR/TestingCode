@extends('layouts.app')

@section('body')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-12">
                        <form class="row row-eq-height lockscreen  mt-5 mb-5" role="form" method="POST" action="{{url('2fa/validate')}}">
                            {!! csrf_field() !!}
                            <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                                <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                            </div>
                            <div class="login-form col-12 col-sm-7">
                            <div style="margin-top: 15px" class="form-group row{{ $errors->has('totp') ? ' has-error' : '' }}">
                                <div class="col-md-2">
                                    <label>OTP</label></div>

                                <div class="col-md-10">
                                    <input type="number" class="form-control tt" name="totp" required="">
                                    @if ($errors->has('totp'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('totp') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group mb-3" >
                           
                            <div class="form-group mb-3">
                                @if (isset($_COOKIE['$ocap_remember_user']))
                                <input type="checkbox" name="remember_browser" checked> 
                                @else
                                <input type="checkbox" name="remember_browser" > 
                                @endif

                                &nbsp; Don't ask for OTP again this browser!!
                            </div>
                            <div class="form-group mb-3">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Validate
                                    </button>
                                </div>
                            </div>
                                <div class="form-group mb-3">
                                    <h6>Protect Your Account.</h6>
                                    <p>Improve your portal security with Two Factor Authentication.
                                    We encourage you to take advantage of our two-factor authentication if you haven???t already.
                                    </p>
                                </div>
                                <!-- <div class="form-group mb-3">
                                   <h6 style="color:blue;">Try Another Way </h6> 
                                   <input id="Button1" type="button" class="button btn-primary" value="Alternate Method" onclick="switchVisible();"/>
                                    

                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@stop


