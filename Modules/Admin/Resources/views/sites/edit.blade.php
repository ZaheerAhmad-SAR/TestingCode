@extends('layouts.app')
@section('title')
    <title> Update Site | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('sites.update',$site->id)}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Update Site</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Site Name</label>
                                    <input type="input" class="form-control" name="site_name" value="{{$site->site_name}}">
                                    @error('site_name')
                                    <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Manager</label>
                                    <input type="text" class="form-control" name="site_manager" value="{{$site->site_manager}}">
                                </div>
                                @error('site_manager')
                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="site_address" value="{{$site->site_address}}">
                                </div>
                                @error('site_address')
                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="site_city" value="{{$site->site_city}}">
                                </div>
                                @error('site_city')
                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="site_state" value="{{$site->site_state}}">
                                </div>
                                @error('site_state')
                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="site_phone" value="{{$site->site_phone}}">
                                </div>
                                @error('site_phone')
                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="site_email" value="{{$site->site_email}}">
                                </div>
                                @error('description')
                                <span class="input-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="pull-right">
                            <a href="{!! route('sites.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
