
@extends('layouts.app')
@section('title')
    <title> Create Study | {{ config('app.name', 'Laravel') }}</title>

@stop
@section('content')

    <form action="{{route('devices.store')}}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Create Study</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Device Name</label>
                                    <input type="text" class="form-control" name="device_name" value="{{old('device_name')}}">
                                    @error('device_name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_title')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Device Model</label>
                                    <input type="text" class="form-control" name="device_model" value="{{old('device_model')}}">
                                    @error('device_model')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Device Manufacturer</label>
                                    <input type="text" class="form-control" name="device_manufacturer" value="{{old('device_manufacturer')}}">
                                    @error('device_manufacturer')
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
                            <h2>Assign Modalities</h2>
                        </div>
                        <div class="form-row">
                            <div class="{!! ($errors->has('roles')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                <label>Select Modalities</label>
                                <select class="" id="select-modality" multiple="multiple" name="modalities[]">
                                    @foreach($modilities as $modality)
                                        <option value="{{$modality->id}}">{{$modality->modility_name}}</option>
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
    </form>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            alert('dfdfddfdf');
            //$('#select-modality').multiSelect({ keepOrder: true });
        });

    </script>
@endsection
