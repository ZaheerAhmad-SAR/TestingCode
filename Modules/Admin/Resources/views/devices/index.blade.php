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
    <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <form action="{{route('devices.index')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">
                            <input type="hidden" name="sort_by_field" id="sort_by_field" value="{{ request()->sort_by_field }}">
                            <input type="hidden" name="sort_by_field_name" id="sort_by_field_name" value="{{ request()->sort_by_field_name }}">
                            <div class="form-group col-md-3">
                                <label for="trans_id">Name</label>
                                <input type="text" name="device_name" id="filter_device_name" class="form-control filter-form-data" value="{{ request()->device_name }}" placeholder="Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="suject_id">Model</label>
                                <input type="text" name="device_model" id="filter_device_model" class="form-control filter-form-data" value="{{ request()->device_model }}" placeholder="Device Model">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="suject_id">Manufacturer</label>
                                <input type="text" name="device_manufacturer" id="filter_device_manufacturer" class="form-control filter-form-data" value="{{ request()->device_manufacturer }}" placeholder="Manufacturer">
                            </div>
                            <div class="form-group col-md-3 mt-4">
                                <button type="button" class="btn btn-outline-warning reset-filter">
                                   <i class="fas fa-undo-alt" aria-hidden="true"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-lng">
                                   <i class="fas fa-filter" aria-hidden="true"></i> Filter
                                </button>
                            </div>
                        </div>
                        <!-- row ends -->
                    </form>
                </div>
            </div>
    </div>
        <!-- END: Card DATA-->
    <!-- START: Card Data-->
     <div class="row">
         <div class="col-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if(hasPermission(auth()->user(),'devices.create'))
                    <button type="button" class="btn btn-outline-primary" id="create-new-device" data-toggle="modal" data-target="#createdevices">
                        <i class="fa fa-plus"></i> Add Device
                    </button>
                        @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive list">
                        <table class="table table-bordered dataTable" id="laravel_crud">
                            <thead>
                                <tr>
                                    <th onclick="changeSort('device_name');">Name <i class="fas fa-sort float-mrg"></i></th>
                                    <th onclick="changeSort('device_model');">Model <i class="fas fa-sort float-mrg"></i></th>
                                    <th onclick="changeSort('device_manufacturer');">Manufacturer <i class="fas fa-sort float-mrg"></i></th>
                                    <td colspan="2">Action</td>
                                </tr>
                            </thead>
                            <tbody id="devices-crud">
                                @foreach($devices as $device)
                                    <tr id="device_id_{{ $device->id }}">
                                        <td>{{ $device->device_name }}</td>
                                        <td>{{ $device->device_model }}</td>
                                        <td>{{ $device->device_manufacturer }}</td>
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    @if(hasPermission(auth()->user(),'devices.edit'))
                                                    <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="edit-device" data-id="{{ $device->id }}">
                                                            <i class="far fa-edit"></i>&nbsp; Edit </a>
                                                    </span>
                                                    @endif
                                                        @if(hasPermission(auth()->user(),'devices.destroy'))
                                                    <span class="dropdown-item">
                                                            <a href="{{route('devices.destroy',$device->id)}}" id="delete-device" data-id="{{ $device->id }}">
                                                            <i class="far fa-edit"></i>&nbsp; Delete </a>
                                                    </span>
                                                            @endif
                                                </div>
                                            </div>
                                        </td>
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
                <div class="modal-header bg-primary" style="color: #fff">
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
                    <div class="alert alert-danger device-error-message" style="display:none; margin-top:5px;"></div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                            @csrf
                            <div class="form-group row" style="margin-top: 10px;">
                                <label for="device_name" class="col-sm-3">Name</label>
                                <div class="{!! ($errors->has('device_name')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_name" name="device_name"
                                           value="{{old('device_name')}}" required>
                                    @error('device_name')
                                    <span class="text-danger small">{{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="device_model" class="col-sm-3">Device Model</label>
                                <div class="{!! ($errors->has('device_model')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_model" name="device_model" value="{{old('device_model')}}" required> @error('email')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="device_manufacturer" class="col-sm-3">Manufacturer</label>
                                <div class="{!! ($errors->has('device_manufacturer')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                    <input type="text" class="form-control" id="device_manufacturer" name="device_manufacturer" value="{{old('device_manufacturer')}}" required>
                                    @error('device_manufacturer')
                                    <span class="text-danger small"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="device_manufacturer" class="col-sm-3"></label>
                                    <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">
                                        <select class="searchable form-control" id="select-modality" multiple="multiple" name="modalities[]" required>
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
<!-- modal code  -->
@endsection
@section('styles')
<style>
    div.dt-buttons{
        display: none;
    }
</style>
@stop
@section('script')
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
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
                // hide error message
                $('.device-error-message').css('display', 'none');
                $('#device-crud-modal').modal('show');
            });

            $('body').on('click', '#edit-device', function () {
                var device_id = $(this).data('id');
               // alert(device_id);
                $.get('devices/'+device_id+'/edit', function (data) {
                    $('#deviceCrudModal').html("Edit Device");
                    $('#btn-save').val("edit-device");
                    // hide error message
                    $('.device-error-message').css('display', 'none');
                    $('#device-crud-modal').modal('show');
                    $('#device_id').val(data.id);
                    $('#device_name').val(data.device_name);
                    $('#device_model').val(data.device_model);
                    $('#device_manufacturer').val(data.device_manufacturer);
                });
            });

            $('body').on('click', '.delete-device', function () {
                var device_id = $(this).data("id");
                alert(device_id);
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
                            if(data.error) {
                                // append/show error message
                                $('.device-error-message').text('Device already exists.');
                                $('.device-error-message').css('display', 'block');
                            } else if (data.success) {
                                // close modal
                                $('#device-crud-modal').modal('hide');
                                location.reload();
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
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
       $(document).ready(function() {
           $('#select-modality').multiSelect({
               selectableHeader: "<label for=''>All Modalities</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
               selectionHeader: "<label for=''>Assigned Modalities</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
               afterInit: function(ms){
                   var that = this,
                       $selectableSearch = that.$selectableUl.prev(),
                       $selectionSearch = that.$selectionUl.prev(),
                       selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                       selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                   that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                       .on('keydown', function(e){
                           if (e.which === 40){
                               that.$selectableUl.focus();
                               return false;
                           }
                       });

                   that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                       .on('keydown', function(e){
                           if (e.which == 40){
                               that.$selectionUl.focus();
                               return false;
                           }
                       });
               },
               afterSelect: function(){
                   this.qs1.cache();
                   this.qs2.cache();
               },
               afterDeselect: function(){
                   this.qs1.cache();
                   this.qs2.cache();
               }
           });
       });

        // sorting gride
        function changeSort(field_name){
            var sort_by_field = $('#sort_by_field').val();
            if(sort_by_field =='' || sort_by_field =='ASC'){
               $('#sort_by_field').val('DESC');
               $('#sort_by_field_name').val(field_name);
            }else if(sort_by_field =='DESC'){
               $('#sort_by_field').val('ASC'); 
               $('#sort_by_field_name').val(field_name); 
            }
            $('.filter-form').submit();
        }
   </script>

@stop
