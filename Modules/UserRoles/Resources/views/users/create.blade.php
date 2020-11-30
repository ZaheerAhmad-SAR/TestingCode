@extends('layouts.app')
@section('title')
    <title> Create User | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('users.store')}}" enctype="multipart/form-data" method="POST" id="user-store-form-1">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Create User</h2>
                    </div>
                    <div class="panel-body">
                        <div class="form-row">
                            <div class="{!! ($errors->has('name')) ?'form-group col-md-6 has-error':'form-group col-md-6' !!}">

                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                @error('name')
                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>
                            <div class="{!! ($errors->has('email')) ?'form-group col-md-6 has-error':'form-group col-md-6' !!}">

                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                @error('email')
                                <span class="text-danger small">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="{!! ($errors->has('user_type')) ?'form-group col-md-6 has-error':'form-group col-md-6' !!}">

                            <label>Account Type</label>
                            <div class="row radio radio-inline">
                               <span>Study Admin <input type="radio" class="" id="user_type" name="user_type" value="1"> </span>
                                <br>
                            <span>System Manager <input type="radio" class="" id="user_type" name="user_type" value="0"> </span>
                                <br>

                            <span>Study User <input type="radio" class="" id="user_type" name="user_type" value="2"> </span>

                            </div>
                            @error('user_type')
                            <span class="text-danger small">
                                    {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="{!! ($errors->has('password')) ?'form-group col-md-6 has-error':'form-group col-md-6' !!}">

                                <label>Password</label>
                                <input type="password" autocomplete="off" class="form-control" name="password" value="{{old('password')}}">
                                @error('password')
                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>
                            <div class="{!! ($errors->has('password_confirmation')) ?'form-group col-md-6 has-error':'form-group col-md-6' !!}">

                                <label>Confirm Password</label>
                                <input type="password" autocomplete="off" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}">
                                @error('password_confirmation')
                                <span class="text-danger small">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row pull-left">
                            @include('admin::assignRoles.assign_roles', ['roles'=>$roles, 'assigned_roles'=>[], 'errors'=>$errors ])
                        </div>

                        <div class="pull-right">
                            <a href="{!! route('roles.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script type="text/javascript">
    $('#user-store-form-1').submit(function(e){
        $('#select_roles_to option').prop('selected', true);
    });
        $(document).ready(function() {
		        $('#select_roles').multiselect({
                    search: {
                        left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                        right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    },
                    fireSearch: function(value) {
                        return value.length > 1;
                    }
                });
	        });
    </script>
@endsection
