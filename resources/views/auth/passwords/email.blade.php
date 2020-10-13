@extends('layouts.home')

@section('content')
<div class="container">
    <div class="row vh-100 justify-content-between align-items-center">
        <div class="col-12">
                <h3>{{ __('Reset Password') }}</h3>
            @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
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
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('Back to Login') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
