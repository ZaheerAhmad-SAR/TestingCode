
@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Study Roles</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Study Roles</li>
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
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createuser">
                            <i class="fa fa-plus"></i> Add Study Role
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive list">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Roles</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td>{{$record['name']}}</td>
                                        <td>{{$record['email']}}</td>
                                        <td>{{$record['roles']}}</td>
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                    <span class="dropdown-item">
                                                        <a href="javascript:void(0)" id="edit-device" data-id="">
                                                            <i class="far fa-edit"></i>&nbsp; Edit </a>
                                                    </span>
                                                    <span class="dropdown-item">
                                                            <a href="" id="delete-device" data-id="">
                                                            <i class="far fa-edit"></i>&nbsp; Delete </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Card DATA-->
    <!-- modal code  -->
    <div class="modal fade" id="createuser" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deviceCrudModal"></h4>
                </div>
                <form id="deviceForm" name="deviceForm" class="form-horizontal">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="user_id">
                        <nav>
                            <div class="nav nav-tabs font-weight-bold border-bottom" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-Basic" role="tab" aria-controls="nav-home" aria-selected="true">Basic Info</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-Modalities" role="tab" aria-controls="nav-profile" aria-selected="false">Roles</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="Name" class="col-md-3">Name</label>
                                    <div class="{!! ($errors->has('name')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="text" class="form-control" required="required" name="name" value="{{old('name')}}">
                                        @error('name')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Email" class="col-md-3">Email</label>
                                    <div class="{!! ($errors->has('email')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="email" class="form-control" name="email" required="required" value="{{old('email')}}"> @error('email')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-3">Password</label>
                                    <div class="{!! ($errors->has('password')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="password" autocomplete="off" class="form-control" required="required" name="password" value="{{old('password')}}">
                                        @error('password')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="C-Password" class="col-md-3">Confirm Password</label>
                                    <div class="{!! ($errors->has('password_confirmation')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="password" autocomplete="off" class="form-control" required="required" name="password_confirmation" value="{{old('password_confirmation')}}">
                                        @error('password_confirmation')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Modalities" role="tabpanel" aria-labelledby="nav-Validation-tab">
                                <div class="form-group row" style="margin-top: 10px;">
                                    <label for="device_manufacturer" class="col-sm-3">Select Roles</label>
                                    <div class="{!! ($errors->has('roles')) ?'col-sm-9 has-error':'col-sm-9' !!}">

                                        <select class="searchable" id="select-roles" multiple="multiple" name="roles[]">
{{--                                            @foreach($roles as $role)--}}
                                                <option value=""></option>
{{--                                            @endforeach--}}
                                        </select>
                                    </div>
                                    @error('roles')
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

@stop
@section('styles')
    <style>
        div.dt-buttons{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@stop
@section('script')
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select-roles').multiSelect({
                selectableHeader: "<label for=''>All Roles</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
                selectionHeader: "<label for=''>Assigned Roles</label><input type='text' class='form-control' autocomplete='off' placeholder='search here'>",
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
                var user_id = $(this).data('id');
                // alert(device_id);
                $.get('users/'+user_id+'/edit', function (data) {
                    $('#deviceCrudModal').html("Edit Device");
                    $('#btn-save').val("edit-device");
                    $('#device-crud-modal').modal('show');
                    $('#device_id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
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
                        url: "{{ route('users.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            var device = '<tr id="user_id' + data.id + '"><td>' + data.id + '</td>' +
                                '<td>' + data.name + '</td>' +
                                '<td>' + data.email + '</td>' +
                                '<td>' + data.password + '</td>'
                            '<td>' + data.role + '</td>';
                            device += '<td><a href="javascript:void(0)" id="edit-device" data-id="' + data.id + '" class="btn btn-info"> Edit</a></td>';
                            device += '<td><a href="javascript:void(0)" id="delete-device" data-id="' + data.id + '" class="btn btn-danger delete-device">Delete</a></td></tr>';

                            if (actionType == "create-device") {
                                $('#devices-crud').prepend(device);
                                var t = setTimeout(function(){// wait for -- secs(2)
                                    location.reload();
                                }, 1000);
                            } else {
                                $("#user_id" + data.id).replaceWith(device);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
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
    <script src="{{ asset("js/jquery.quicksearch.js") }}" type="text/javascript"></script>




@stop
