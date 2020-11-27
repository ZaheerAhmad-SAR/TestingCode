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
                                <button type="button" class="btn btn-outline-primary" onclick="openAddUserPopup();">
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
                                        <td>{{ucfirst(($user->name))}}</td>
                                        <td>{{($user->email)}}</td>
                                        <td>{{ \App\User::getUserRolesString($user) }}</td>
                                        <td>{{!empty($user->google2fa_secret)?'Enabled':'Disabled'}}</td>
                                        <td>
                                            <div class="d-flex mt-3 mt-md-0 ml-auto">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                <span class="dropdown-item">
                                                <a href="javascript:void(0);" onclick="openEditUserPopup('{{ $user->id }}');">
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
    @include('userroles::users.popups.createuser')
    @include('userroles::users.popups.assignuser', ['roles'=>$roles, 'studyusers'=>$studyusers])
    @include('userroles::users.popups.inviteuser', ['roles'=>$roles])

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
function setMultiselect() {
		        $('#select_roles').multiselect({
                    search: {
                        left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                        right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    },
                    fireSearch: function(value) {
                        return value.length > 1;
                    }
                });
	        }
        $(document).ready(function(){
            $('#2fa').on('show.bs.modal',function (e) {
                var id = $(e.relatedTarget).data('target-id');
                $('#user_id').val(id);
            });

            // form submit create user
            $('#user-store-form-5').submit(function(e){
                e.preventDefault();
                $('#select_roles_to option').prop('selected', true);
                // get form data
                var formData = new FormData($(this)[0]);
                $.ajax({
                  url: $(this).attr('action'),
                  data: formData,
                  processData: false,
                  contentType: false,
                  type: 'POST',
                  success: function(data) {
                    if (data.errors) {
                        $('.user-store-error').text(data.errors);
                        $('.user-store-error').css('display', 'block');
                        setTimeout(function() {
                            $('.user-store-error').slideUp(500);
                        }, 5000);
                    } else {
                        location.reload();
                    }
                  }
                }); // ajax ends
            }); // form submit function


            $('#inviteuser_form_1').submit(function(e){
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                  url: $(this).attr('action'),
                  data: formData,
                  processData: false,
                  contentType: false,
                  type: 'POST',
                  success: function(data) {
                    if (data.errors) {
                        $('.inviteuser_error').text(data.errors);
                        $('.inviteuser_error').css('display', 'block');
                        setTimeout(function() {
                            $('.inviteuser_error').slideUp(500);
                        }, 5000);
                    } else {
                        location.reload();
                    }
                  }
                }); // ajax ends
            });

            $('#assignuser_form_1').submit(function(e){
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                  url: $(this).attr('action'),
                  data: formData,
                  processData: false,
                  contentType: false,
                  type: 'POST',
                  success: function(data) {
                    if (data.errors) {
                        $('.assignuser_error').text(data.errors);
                        $('.assignuser_error').css('display', 'block');
                        setTimeout(function() {
                            $('.assignuser_error').slideUp(500);
                        }, 5000);
                    } else {
                        //location.reload();
                    }
                  }
                }); // ajax ends
            });

        });
            function openAddUserPopup(){
                $("#createUser").modal('show');
                loadUserForm();
            }
            function openEditUserPopup(userId){
                $("#createUser").modal('show');
                loadUserEditForm(userId);
            }
            function loadUserForm(){
                $.ajax({
                    url: "{{route('users.create')}}",
                    type: 'GET',
                    success: function(response){
                        $('#userFormInner').empty();
                        $("#userFormInner").html(response);
                        setMultiselect();
                    }
                });
            }
            function loadUserEditForm(userId){
                $.ajax({
                    url: "{{ url('/')}}" + '/users/'+ userId +'/edit',
                    type: 'GET',
                    success: function(response){
                        $('#userFormInner').empty();
                        $("#userFormInner").html(response);
                        setMultiselect();
                    }
                });
            }

            function submitAddUserForm(){
                $('#select_roles_to option').prop('selected', true);
                $.ajax({
                    url: $("#user-store-form-5").attr('action'),
                    type: 'POST',
                    data: $("#user-store-form-5").serialize(),
                    success: function(data){
                        if (data.errors) {
                            $('.user-store-error').text(data.errors);
                            $('.user-store-error').css('display', 'block');
                            setTimeout(function() {
                                $('.user-store-error').slideUp(500);
                            }, 5000);
                        } else {
                            $('#userFormInner').empty();
                            location.reload();
                        }
                    }
                });
            }

            function submitEditUserForm(){
                $('#select_roles_to option').prop('selected', true);
                $.ajax({
                    url: $("#user-store-form-5").attr('action'),
                    type: 'POST',
                    data: $("#user-store-form-5").serialize(),
                    success: function(data){
                        if (data.errors) {
                            $('.user-store-error').text(data.errors);
                            $('.user-store-error').css('display', 'block');
                            setTimeout(function() {
                                $('.user-store-error').slideUp(500);
                            }, 5000);
                        } else {
                            $('#userFormInner').empty();
                            location.reload();
                        }
                    }
                });

            }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
    <script src="http://loudev.com/js/jquery.quicksearch.js" type="text/javascript"></script>
@stop
