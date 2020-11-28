@extends ('layouts.app')
@section('body')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-12">
                <form  action="{{ route('password.update') }}" method="POST" class="row row-eq-height lockscreen  mt-5 mb-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                        <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                    </div>
                    <div class="login-form col-12 col-sm-7">
                        <div class="form-group mb-3" style="margin-top: 60px;">
                                <label for="email">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                        </div>
                            <div class="form-group mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <input id="password" type="password" autocomplete="off" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <input id="password-confirm" type="password" autocomplete="off" class="form-control" name="password_confirmation" required autocomplete="new-password">

                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Reset Password') }}
                                    </button>
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
