@extends ('layouts.app')
@section('body')
    <div class="container" id ="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-12">
                <form  action="{{route('accept')}}" method="POST" class="row row-eq-height lockscreen mb-5">
                    @csrf
                    <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                        <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                    </div>
                    <div class="login-form col-12 col-sm-7">
                        <input type="hidden" id="userAgent" value="">
                        <div class="form-group mb-3" style="margin-top: 60px;">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <p id="passwordHelpBlock" class="form-text text-muted">
                                Your password must be 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                            </p>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mb-3">
                                <button class="btn btn-primary" type="submit"  style="float: right;"> Register </button>
                        </div>

                        <div class="mt-2" style="padding-top: 50px;">OCAP by OIRRC</div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@stop

