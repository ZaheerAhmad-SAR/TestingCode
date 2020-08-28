@extends('layouts.app')
@section('title')
    <title> Update Study | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('modalities.update',$modalities->id)}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Update Modility Child</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="modility_name" value="{{$modalities->modility_name}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="pull-right">
                            <a href="{!! route('modalities.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
