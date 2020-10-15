@extends ('layouts.app')
@section('body')
    <div class="container">
        <div class="row vh-100 justify-content-between align-items-center">
            <div class="col-12">
                <form  action="{{ url('/2fa_verify') }}" method="POST" class="row row-eq-height lockscreen  mt-5 mb-5">
                    @csrf
                    <div class="lock-image col-12 col-sm-5" style="min-height: 400px;">
                        <img src="{{asset('public/dist/images/Logo.gif')}}" alt="" style="width: 230px;margin-top: 120px;">
                    </div>
                    <div class="login-form col-12 col-sm-7" style="min-height: 400px;">
                        <div class="form-group" style="min-height: 35%">
                        </div>
                        <div class="form-group"></div>
                        <div class="form-group mb3">
                            <label for="password">Enter Code</label>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input id="2fa" type="text" class="form-control" name="two_factor_token" placeholder="Enter the code you received here." required autofocus>
                        @if ($errors->has('2fa'))
                            <span class="help-block">
                                <strong>{{ $errors->first('2fa') }}</strong>
                            </span>
                        @endif
                    </div>
                        </div>

                    <div class="form-group">
                        <button class="btn btn-primary" style="float: right" type="submit">Verify Code</button>
                    </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/social-button/bootstrap-social.css') }}"/>
@stop
