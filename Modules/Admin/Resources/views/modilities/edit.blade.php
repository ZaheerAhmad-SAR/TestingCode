@extends('layouts.app')
@section('title')
    <title> Update Study | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('studies.update',$study->id)}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Update Study</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="study_name" value="{{$study->study_name}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Study Type</label>
                                    <input type="text" class="form-control" name="study_type" value="{{$study->study_type}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{$study->start_date}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{$study->end_date}}">
                                    @error('name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description">{{$study->description}}</textarea>
                                </div>
                                @error('description')
                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="pull-right">
                            <a href="{!! route('studies.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
