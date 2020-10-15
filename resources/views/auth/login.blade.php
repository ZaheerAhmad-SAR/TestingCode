@extends ('layouts.app')
@section('body')
<div class="container">
    <div class="row vh-100 justify-content-between align-items-center">
        <div class="col-12">
{{--            <form  action="{{ route('login') }}" method="POST" class="row row-eq-height lockscreen  mt-5 mb-5">--}}
            <form  action="{{ url('/2fa') }}" method="POST" class="row row-eq-height lockscreen  mt-5 mb-5">
                @csrf
                <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                    <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                </div>
                <div class="login-form col-12 col-sm-7">
                    <div class="form-group mb-3" style="margin-top: 60px;">
                        <label for="emailaddress">Email address</label>
                        <input class="form-control  @error('email') is-invalid @enderror" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                   <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror" type="password"  name="password" required id="password" placeholder="Enter your password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked="">
                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                            <button class="btn btn-primary" type="submit" style="float: right;"> Sign In </button>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="custom-control">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
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
