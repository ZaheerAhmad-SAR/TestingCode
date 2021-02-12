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
        
        <div class="card">
            <div class="card-body">
                <form action="{{route('studyusers.index')}}" method="get" class="filter-form">
                    @csrf
                    <input type="hidden" name="sort_by_field" id="sort_by_field" value="{{ getOldValue($old_values,'sort_by_field') }}">
                    <input type="hidden" name="sort_by_field_name" id="sort_by_field_name" value="{{ getOldValue($old_values,'sort_by_field_name') }}">
                    <div class="form-row" style="padding: 10px;">
                        <div class="form-group col-md-3">
                            <input type="text" name="name" class="form-control" value="{{ getOldValue($old_values,'name')}}" placeholder="Name">
                        </div>
                         <div class="form-group col-md-3">
                            <input type="text" name="email" class="form-control" value="{{ getOldValue($old_values,'email')}}" placeholder="Email">
                        </div>
                        <div class="form-group col-md-3">
                           @php
                                $old_role_id ='';
                                $old_role_id =  getOldValue($old_values,'role_id');
                            @endphp 
                           <select class="form-control" name="role_id">
                               <option value="">Role</option>
                               @foreach($allroles as $key => $role)
                                <option value="{{$role->id}}" @if($old_role_id == $role->id) selected @endif>{{$role->name}}</option>
                               @endforeach
                           </select>
                        </div>
                        <div class="form-group col-md-3" style="text-align: right;">
                            <button class="btn btn-outline-warning reset-filter"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                            <button type="submit" class="btn btn-primary submit-filter"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
        <!-- END: Breadcrumbs-->
        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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
                                            <th scope="col" onclick="changeSort('name');">Name <i class="fas fa-sort float-mrg"></i></th>
                                            <th scope="col" onclick="changeSort('email');">Email <i class="fas fa-sort float-mrg"></i></th>
                                            <th scope="col">Role</th>
                                            <th scope="col" onclick="changeSort('is_active');">Is Active? <i class="fas fa-sort float-mrg"></i></th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody id="users-crud">
                                        @foreach($studyusers as $user)
                                            <tr>
                                                <td>{{ucfirst($user->name)}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{ \App\User::getUserRolesInStudyString($user) }}</td>
                                                <td id="userActiveTD_{{$user->id}}">{{ ((int)$user->is_active == 1)? 'Active':'InActive' }}</td>
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
                                                    <a href="{{route('studyusers.destroy',$user->id)}}" class="delete-user" id="delete-user" data-id="{{ $user->id }}">
                                                        <i class="far fa-edit"></i>&nbsp; Delete </a>
                                                    </span>
                                                    @if (hasPermission(auth()->user(), 'systemtools.index'))
                                                    <div id="userActiveStatusDiv_{{$user->id}}">
                                                        @if($user->is_active == 0)
                                                        <span class="dropdown-item activateUser" onclick="submitActivateUserRequest('{{ $user->id }}');"><i class="far fa-play-circle"></i>&nbsp; Activate User</span>
                                                        @else
                                                        <span class="dropdown-item inActivateUser" onclick="submitInActivateUserRequest('{{ $user->id }}');"><i class="far fa-pause-circle"></i>&nbsp; Inactivate User</span>
                                                        @endif
                                                    </div>
                                                    @endif
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
    @include('userroles::users.popups.createuser')
    <!-- modal code  -->
    <div class="modal fade" tabindex="-1" role="dialog" id="assignUser">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add User</p>
                </div>
                <form action="{{route('users.assignUsers')}}" enctype="multipart/form-data" method="POST" id="assignuser_form_2">
                    <div class="modal-body">
                        <p class="alert alert-danger assignuser_error" style="display: none;"></p>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select User</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="study_user">
                                            <option value=""> Select User</option>
                                            @foreach($remaining_users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select Role</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="user_role">
                                            <option value="">Select Role</option>
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
                            <button type="submit" class="btn btn-outline-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save Changes</button>
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
                <form action="{{route('users.assignUsers')}}" enctype="multipart/form-data" method="POST" id="">
                    <div class="modal-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-Basic" role="tabpanel" aria-labelledby="nav-Basic-tab">
                                @csrf
                                <div class="form-group row" style="margin-top: 10px;">
                                    <div class="col-md-4">Select User</div>
                                    <div class="col-md-8">
                                        <select class="form-control dropdown" name="study_user">
                                            <option value="selectuser"> Select User</option>
                                            @foreach($remaining_users as $user)
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
                            <button type="submit" class="btn btn-outline-primary" id="btn-save" value="create"><i class="fa fa-save"></i> Save Changes</button>
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
                <form action="{{route('process_invite')}}" enctype="multipart/form-data" method="POST" id="inviteuser_form_2">
                    @csrf
                    <div class="modal-body">
                        <p class="alert alert-danger inviteuser_error" style="display: none;"></p>
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


            $('#inviteuser_form_2').submit(function(e){
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                //var formData = $("#inviteuser_form").serialize();

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
                        }, 2000);

                    } else {

                        location.reload();
                    }
                  }
                }); // ajax ends
            }); // form submit function

            $('#assignuser_form_2').submit(function(e){
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                //var formData = $("#inviteuser_form").serialize();

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
                        }, 2000);

                    } else {

                        location.reload();
                    }
                  }
                }); // ajax ends
            }); // form submit function

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
                    url: "{{route('studyusers.create')}}",
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
                    url: "{{ url('/')}}" + '/studyusers/'+ userId +'/edit',
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
    @include('userroles::users.common_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js" integrity="sha512-vSyPWqWsSHFHLnMSwxfmicOgfp0JuENoLwzbR+Hf5diwdYTJraf/m+EKrMb4ulTYmb/Ra75YmckeTQ4sHzg2hg==" crossorigin="anonymous"></script>
    <script src="{{ asset("js/jquery.quicksearch.js") }}" type="text/javascript"></script>
@stop
