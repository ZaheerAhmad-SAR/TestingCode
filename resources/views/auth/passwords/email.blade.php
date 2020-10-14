@extends ('layouts.app')
@section('body')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <h3>{{ __('Reset Password') }}</h3>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="col-12">
                    <form method="POST" action="{{ route('password.email') }}" class="row row-eq-height lockscreen  mt-5 mb-5">
                    @csrf
                    <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                        <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                    </div>
                    <div class="login-form col-12 col-sm-7">
                            <div class="form-group mb-3" style="margin-top: 60px;">
                                <label for="email">{{ __('E-Mail Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <div class="form-group">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('Back to Login') }}
                            </a>
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
