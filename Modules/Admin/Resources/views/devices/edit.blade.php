@extends('layouts.app')
@section('title')
    <title> Update Device | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('devices.update',$device->id)}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Update Device</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Device Name</label>
                                    <input type="text" class="form-control" name="device_name" value="{{$device->device_name}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('device_manufacturer')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Manufacturer</label>
                                    <input type="text" class="form-control" name="device_manufacturer" value="{{$device->device_manufacturer}}">
                                    @error('device_manufacturer')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Model</label>
                                    <input type="text" class="form-control" name="device_model" value="{{$device->device_model}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2>Assign Sites</h2>
                        </div>
                        <div class="form-row">
                            <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                <label>Select Sites</label>
                                <select class="form-control" id="select-sites" multiple="multiple" name="sites[]">
                                    @foreach($sites as $site)
                                        <option value="{{$site->id}}">{{$site->site_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('roles')
                            <span class="text-danger small">
                                    {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="panel-body">
                            <div class="panel-heading">
                                <h2>Assign Modalities</h2>
                            </div>
                            <div class="form-row">
                                <div class="{!! ($errors->has('roles')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Select Modility</label>
                                    <select class="" id="select-modility" multiple="multiple" name="modility[]">
                                        @foreach($modilities as $modility)
                                            <option value="{{$modility->id}}">{{$modility->modility_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('roles')
                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>
                            <div class="pull-right">
                                <a href="{!! route('devices.index') !!}" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-success">Create</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select-modility').multiselect();
        });
        $(function() {
            $('#select-sites').multiselect();
        });
    </script>
@endsection
