@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Users Details</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Users</li>
                    </ol>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <script>
                $( document ).ready(function() {
                    $('#createUser').modal('show');
                });
            </script>
    @endif
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <div class="row">
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                        <button class="close" data-dismiss="alert">&times;</button>
                    </div>
                </div>
            @endif
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">

                    <div class="card-header d-flex align-items-center">
                            @if(hasPermission(auth()->user(),'users.create'))
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#createUser">
                                    <i class="fa fa-plus"></i> Add User
                                </button>
                            @endif

                                @if(hasPermission(auth()->user(),'invite_view'))
                                    &nbsp; <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#inviteuser">
                                        <i class="far fa-edit"></i>&nbsp; Invite User
                                    </button>
                                @endif
                        &nbsp;
                            @if(hasPermission(auth()->user(),'users.create'))
                                @if(session('current_study'))
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#assignUser">
                                    <i class="fa fa-plus"></i> Assign User
                                </button>
                            @endif
                            @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive list">
                            <table class="table table-bordered editable-table" id="laravel_crud">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">2 Factor Auth</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="users-crud">
                                        @foreach($users as $user)
                                    <tr>
                                        <td>{{ucfirst($user->name)}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @foreach($user->user_roles as $role)
                                                {{ucfirst($role->role->name)}},
                                            @endforeach
                                        </td>
                                        <td>{{!empty($user->google2fa_secret)?'Enabled':'Disabled'}}</td>
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                <span class="dropdown-item">
                                                    <a href="{!! route('users.edit',$user->id) !!}">
                                                        <i class="far fa-edit"></i>&nbsp; Edit
                                                    </a>
                                                </span>
                                                    <span class="dropdown-item">
                                                    <a href="{{route('users.destroy',$user->id)}}" class="delete-user" id="delete-user" data-id="{{ $user->id }}">
                                                        <i class="fa fa-trash"></i>&nbsp; Delete </a>
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
    <div class="modal " tabindex="-1" role="dialog" id="createUser">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add User</p>
                </div>
                <form action="{{route('users.store')}}" enctype="multipart/form-data" method="POST">
                    <div class="modal-body">
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
                                        <input type="text" class="form-control" required="required" id="name" name="name" value="{{old('name')}}">
                                        @error('name')
                                        <span class="text-danger small">{{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Email" class="col-md-3">Email</label>
                                    <div class="{!! ($errors->has('email')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="email" class="form-control" name="email" id="email" required="required" value="{{old('email')}}"> @error('email')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-3">Password</label>
                                    <div class="{!! ($errors->has('password')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="password" class="form-control" required="required" id="password" name="password" value="{{old('password')}}">
                                        @error('password')
                                        <span class="text-danger small"> {{ $message }} </span>
                                        @enderror
                                        <p id="passwordHelpBlock" class="form-text text-muted">
                                            Your password must be 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.
                                        </p>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="C-Password" class="col-md-3">Confirm Password</label>
                                    <div class="{!! ($errors->has('password_confirmation')) ?'form-group col-md-9 has-error':'form-group col-md-9' !!}">
                                        <input type="password" class="form-control" required="required" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}">
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
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
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
                        @if(hasPermission(auth()->user(),'users.store'))
                            <button type="submit" class="btn btn-outline-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save Changes</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- modal code  -->
    <div class="modal fade" tabindex="-1" role="dialog" id="assignUser">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add User</p>
                </div>
                <form action="{{route('users.assignUsers')}}" enctype="multipart/form-data" method="POST">
                    <div class="modal-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select User</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="study_user">
                                            <option value="selectuser"> Select User</option>
                                            @foreach($studyusers as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select Role</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="user_role">
                                            <option value="1">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        @if(hasPermission(auth()->user(),'users.store'))
                            <button type="submit" class="btn btn-outline-primary" id="btn-save" onclick="checkuser()" value="create"><i class="fa fa-save"></i> Save Changes</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- reset password -->
    <div class="modal fade" tabindex="-1" role="dialog" id="resetpassword">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add User</p>
                </div>
                <form action="{{route('users.assignUsers')}}" enctype="multipart/form-data" method="POST">
                    <div class="modal-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select User</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="study_user">
                                            <option value="selectuser"> Select User</option>
                                            @foreach($studyusers as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select Role</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="user_role">
                                            <option value="1">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        @if(hasPermission(auth()->user(),'users.store'))
                            <button type="submit" class="btn btn-outline-primary" id="btn-save" onclick="checkuser()" value="create"><i class="fa fa-save"></i> Save Changes</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Invite User -->
    <div class="modal fade" tabindex="-1" role="dialog" id="inviteuser">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Invite User</p>
                </div>
                <form action="{{route('process_invite')}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Email address</div>
                                    <div class="col-md-8">
                                        <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Role </div>
                                    <div class="col-md-8">
                                        <select name="roles" id="roles" class="form-control">
                                            <option value="">-- Select Role --</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        @if(hasPermission(auth()->user(),'users.store'))
                            <button type="submit" class="btn btn-success">Send Invitation</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop
@section('styles')
    <style>
        div.dt-buttons{
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.css" integrity="sha512-2sFkW9HTkUJVIu0jTS8AUEsTk8gFAFrPmtAxyzIhbeXHRH8NXhBFnLAMLQpuhHF/dL5+sYoNHWYYX2Hlk+BVHQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('dist/vendors/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css') }}">
@stop
@section('script')
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#2fa').on('show.bs.modal',function (e) {
                var id = $(e.relatedTarget).data('target-id');
                $('#user_id').val(id);
            })
        })
    </script>

    <script type="text/javascript">
        function checkuser() {
            var user = document.getElementByName("users")[0].value;
            if (user.value == "selectuser") {
                alert("Please select a user");
            }
        }

            @if (count($errors) > 0)
            $('#inviteuser').modal('show');
        @endif

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
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
@stop
