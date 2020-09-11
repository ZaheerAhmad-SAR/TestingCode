@extends('layouts.home')

@section('title')
    <title> Update User Roles | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
 <div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Update User Roles</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Role</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
<div class="row">
    <div class="col-12 col-sm-12 mt-3">
        <div class="card">
           <form action="{{route('roles.update',$role->id)}}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PATCH')
                <div class="card-body">
                    <div class="form-group row">
                        <label for="Name" class="col-sm-3">Name</label>
                        <div class="{!! ($errors->has('name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                            <input type="text" class="form-control" name="name" value="{{$role->name}}">
                            @error('name')
                            <span class="text-danger small">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="Description" class="col-sm-3">Description</label>
                        <div class="{!! ($errors->has('description')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                            <textarea class="form-control" name="description">{{$role->description}}</textarea>
                        </div>
                        @error('description')
                        <span class="text-danger small">
                                {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="form-group row">
                        @foreach ($permissions as $permission)
                            <div class="col-sm-3">
                                {!! $permission ->controller_name!!}
                            </div>
                            <div class="col-sm-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permission[]" value="{{ $permission->id }}"
                                                  @foreach ($role->permissions as $role_permit)
                                                  @if ($role_permit->id == $permission->id)
                                                  checked
                                            @endif
                                            @endforeach
                                        >{{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="pull-right" style="text-align: right;">
                            <a href="{!! route('roles.index') !!}" ><button type="button" class="btn btn-outline-danger">Back To List</button></a>
                            <button type="submit" class="btn btn-outline-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- END: Card DATA-->
</div>

@endsection
@section('styles')

@stop
@section('script')


@stop
