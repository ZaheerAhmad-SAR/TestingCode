@extends('layouts.home')

@section('title')
    <title> Devices | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
 <div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Devices Detail</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Devices</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
<div class="row">
 <div class="col-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">                               
                    <button type="button" class="btn btn-outline-primary" id="create-new-device" data-toggle="modal" data-target="#createdevices"><i class="fa fa-plus"></i> Add Device
                    </button>                                   
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="laravel_crud">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <td colspan="2">Action</td>
                                </tr>
                            </thead>
                            <tbody id="devices-crud">
                                @foreach($devices as $device)
                                    <tr id="device_id_{{ $device->id }}">
                                        <td>{{ $device->id  }}</td>
                                        <td>{{ $device->device_name }}</td>
                                        <td>{{ $device->device_model }}</td>
                                        <td><a href="javascript:void(0)" id="edit-device" data-id="{{ $device->id }}" class="btn btn-info">Edit</a></td>
                                        <td>
                                            <a href="javascript:void(0)" id="delete-device" data-id="{{ $device->id }}" class="btn btn-danger delete-device">Delete</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $devices->links() }} 
                    </div>
                </div>
            </div> 

        </div>                  
</div>
    <!-- END: Card DATA-->
</div>
<!-- modal code  -->
    <div class="modal fade" id="device-crud-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deviceCrudModal"></h4>
                </div>
                <form id="deviceForm" name="deviceForm" class="form-horizontal">
                    <div class="modal-body">
                        <input type="hidden" name="device_id" id="device_id">
                            <nav>
                                <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Modalities" role="tab" aria-controls="nav-profile" aria-selected="false">Modalities</a>
                                </div>
                            </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="device_name" class="col-sm-3">Name</label>
                                <div class="{!! ($errors->has('device_name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_name" name="device_name" value="{{old('device_name')}}">
                                    @error('device_name')
                                    <span class="text-danger small">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="device_model" class="col-sm-3">Device Model</label>
                                <div class="{!! ($errors->has('device_model')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_model" name="device_model" value="{{old('device_model')}}"> @error('email')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="device_manufacturer" class="col-sm-3">Manufacturer</label>
                                <div class="{!! ($errors->has('device_manufacturer')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" name="device_manufacturer" value="{{old('device_manufacturer')}}">
                                    @error('device_manufacturer')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="device_manufacturer" class="col-sm-3">Hold Command And Select Multiple</label>
                                    <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <select class="searchable form-control" id="select-modality" multiple="multiple" name="modalities[]">
                                            @foreach($modilities as $modality)
                                                <option value="{{$modality->id}}">{{$modality->modility_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('modalities')
                                    <span class="text-danger small">
                                    {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
<!-- modal code  -->
@endsection
@section('styles')
<style>
    div.dt-buttons{
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}"> 
@stop
@section('script')
<script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#create-new-device').click(function () {
                $('#btn-save').val("create-device");
                $('#deviceForm').trigger("reset");
                $('#deviceCrudModal').html("New Device");
                $('#device-crud-modal').modal('show');
            });

            $('body').on('click', '#edit-device', function () {
                var device_id = $(this).data('id');
                console.log(device_id);
                $.get('devices/'+device_id+'/edit', function (data) {
                    $('#deviceCrudModal').html("Edit Device");
                    $('#btn-save').val("edit-device");
                    $('#device-crud-modal').modal('show');
                    $('#device_id').val(data.id);
                    $('#device_name').val(data.device_name);
                    $('#device_model').val(data.device_model);
                    $('#device_manufacturer').val(data.device_manufacturer);
                })
            });

            $('body').on('click', '.delete-device', function () {
                var device_id = $(this).data("id");
                confirm("Are You sure want to delete !");

                $.ajax({
                    type: "DELETE",
                    url: "{{ url('devices')}}"+'/'+device_id,
                    success: function (data) {
                        $("#device_id_" + device_id).remove();
                        confirm('Deleted Successfully !!');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });

        if ($("#deviceForm").length > 0) {
            $("#deviceForm").validate({

                submitHandler: function(form) {
                    var t;
                    var actionType = $('#btn-save').val();
                    $('#btn-save').html('Sending..');


                    $.ajax({
                        data: $('#deviceForm').serialize(),
                        url: "{{ route('devices.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            var device = '<tr id="device_id_' + data.id + '"><td>' + data.id + '</td><td>' + data.device_name + '</td><td>' + data.device_model + '</td>';
                            device += '<td><a href="javascript:void(0)" id="edit-device" data-id="' + data.id + '" class="btn btn-info">Edit</a></td>';
                            device += '<td><a href="javascript:void(0)" id="delete-device" data-id="' + data.id + '" class="btn btn-danger delete-device">Delete</a></td></tr>';

                            if (actionType == "create-device") {
                                $('#devices-crud').prepend(device);
                                var t = setTimeout(function(){// wait for -- secs(2)
                                    location.reload();
                                }, 1000);
                            } else {
                                $("#device_id_" + data.id).replaceWith(device);
                            }
                            $('#deviceForm').trigger("reset");
                            $('#device-crud-modal').modal('hide');
                            $('#btn-save').html('Save Changes');
                            alert(data.success());
                            if(data.success == true){
                                var t = setTimeout(function(){// wait for -- secs(2)
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            $('#btn-save').html('Save Changes');
                        }
                    });
                }
            })
        }
    </script>
<script src="{{ asset('public/dist/vendors/datatable/js/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('public/dist/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.print.min.js') }}"></script>              
<script src="{{ asset('public/dist/js/datatable.script.js') }}"></script>

@stop