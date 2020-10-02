@extends ('layouts.home')
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Modalities</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Modalities</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 success-alert" style="display: none;">
                <div class="alert alert-primary success-msg" role="alert">
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="eagle-divider"></div>
            <div class="col-lg-4 col-xl-4">
                @if(hasPermission(auth()->user(),'modalities.create'))
                Parent <button class="custom-btn blue-color" id="add_phase"> <i class="fa fa-plus blue-color"></i> add</button>
                    @endif
            </div>
            <div class="col-lg-8  col-xl-8">
                @if(hasPermission(auth()->user(),'childmodilities.create'))
                Child <button class="custom-btn blue-color" id="add_steps"><i class="fa fa-plus blue-color"></i> add</button>
                    @endif
            </div>
            <div class="col-lg-4 col-xl-4 mb-4 mt-3 pr-lg-0 flip-menu">
                <a href="#" class="d-inline-block d-lg-none mt-1 flip-menu-close"><i class="icon-close"></i></a>
                <div class="card border h-100 mail-menu-section ">
                    <ul class="list-unstyled inbox-nav  mb-0 mt-2 mail-menu" id="phases-group">
                        @foreach($modalities as $modality)
                            <li class="nav-item mail-item" style="border-bottom: 1px solid #F6F6F7;">
                                <div class="d-flex align-self-center align-middle">
                                    <div class="mail-content d-md-flex w-100">
                                        <a href="#" data-mailtype="tab_" data-id="{{$modality->id}}" class="nav-link showPhasesSteps">
                                            <span class="mail-user">{{$modality->modility_name}}</span>
                                        </a>
                                        <div class="d-flex mt-3 mt-md-0 ml-auto">
                                            <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
                                            <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
                                                <span class="dropdown-item edit_phase" data-id="{{$modality->id}}"><i class="far fa-edit"></i>&nbsp; Edit</span>
                                                <span class="dropdown-item replicateParent" data-id="{{$modality->id}}"><i class="far fa-clone"></i>&nbsp; Clone</span>
                                                <span class="dropdown-item deleteParent" data-id="{{$modality->id}}"><i class="far fa-trash-alt"></i>&nbsp; Delete</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 col-xl-8 mb-4 mt-3 pl-lg-0" style="min-height: 450px;">
                <div class="card border h-100 mail-list-section">
                    <div class="card-body p-0">
                        <div class="scrollertodo">
                            <ul class="list-unstyled inbox-nav  mb-0 mt-2 mail-menu" id="childClass">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
    <!-- phase modle -->
    <div class="modal fade" tabindex="-1" role="dialog" id="addphase" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header ">
                    <p class="modal-title">Add Parent</p>
                </div>
                <form  id="parentForm" name="parentForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                @csrf
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="modility_name" name="modility_name" placeholder="Enter Modility name" maxlength="50"  value="{{old('modility_name')}}" required/>
                                    </div>
                                    <input type="hidden" name="parent_yes" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="addphase-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary" id="saveParent" ><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add steps agains phases -->
    <div class="modal fade" tabindex="-1" role="dialog" id="editphase" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header ">
                    <p class="modal-title">Edit a Parent
                </div>
                <form  id="editParentForm" name="editParentForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                @csrf

                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9" id="editParentClass">
                                        <input type="text" class="form-control" id="modility_name" name="modility_name" placeholder="Enter Modility name" maxlength="50"  value="{{old('modility_name')}}" required/>
                                    </div>
                                    <input type="hidden" name="parent_yes" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="addphase-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary" id="updateParent" ><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="addsteps" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Add Child</p>
                </div>
                <form  id="childForm" name="childForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                @csrf

                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3 col-form-label">Child Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="modility_name" name="modility_name" value="{{old('modility_name')}}" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3 col-form-label">Modality</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="parent_id" id="parent_id">
                                            <option value="">Select Parent Modality</option>
                                            @foreach ($modalities as $modility)
                                                <option value="{{ $modility->id }}">{{ $modility->modility_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="addstep-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="button" class="btn btn-outline-primary" id="saveChild"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="editsteps" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Edit Child</p>
                </div>
                <form  id="editChildForm" name="editChildForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                @csrf

                                <div class="form-group row">
                                    <label for="Name" class="col-sm-3 col-form-label">Child Name</label>
                                    <div class="col-md-9" id="editChildClass">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-danger" data-dismiss="modal" id="addstep-close"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary" id="updateChild"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->

    <!--  -->

@stop
@section('styles')
    <style>
        .d-flex > span > i {
            cursor: pointer;

        }
        .mail-menu li a {
            font-weight: 600 !important;
        }
        .dropdown-menu span{
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/quill/quill.snow.css') }}" />
@stop
@section('script')

@section('script')
    <script type="text/javascript">
        $('body').on('click','.edit_phase',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = "{{URL('modalities')}}";
            var parent_id = $(this).data('id');
            //alert(parent_id);
            var newPath = url+ "/"+parent_id+"/edit/";
            $.ajax({
                type:"GET",
                dataType: 'html',
                url:newPath,
                success : function(results)
                {
                    $('#editParentClass').html(results);
                    $('#editphase').trigger('reset');
                    $('#editphase').modal('show');
                }
            });


        })
        $('#add_phase').on('click',function(){

            // $('.modal-title').html('Add a Phase');
            // $('#add_edit_phase').trigger('reset');

            $('#addphase').modal('show');
        })

        $('#add_steps').on('click',function(){
            // $('.modal-title').html('Add a steps');
            // $('#add_edit_steps').trigger('reset');
            // $('#step_id').val('');
            $('#addsteps').modal('show');
        })

        // Edit Parent Modility by id
        function editParent(){
            $(document).on('click','.open_modal',function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                //var url = "{{URL('modalities')}}";
                var url = "{{URL('/ocap/modalities')}}";
                var parent_id = $(this).data('id');
                //alert(parent_id);
                var newPath = url+ "/"+parent_id+"/edit/";
                $.ajax({
                    type:"GET",
                    dataType: 'html',
                    url:newPath,
                    success : function(results)
                    {
                        $('#editParentClass').html(results);
                        $('#editParentModal').modal('show');
                    }
                });
            });
        }
        editParent();
        // End of Edit Child Modility
        function editChild(){
            $(document).on('click','.edit_steps',function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = "{{URL('childmodilities/')}}";
                var child_id = $(this).data('id');

                var newPath = url+ "/"+child_id+"/edit/";
                $.ajax({
                    type:"GET",
                    dataType: 'html',
                    url:newPath,
                    success : function(results)
                    {
                        $('#editChildClass').html(results);
                        $('#editsteps').modal('show');
                    }
                });
            });
        }
        editChild();
        /// Update Child Modility Function
        function updateChildmodilities (){
            $("#editChildForm").submit(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();

                $.ajax({
                    data: $('#editChildForm').serialize(),
                    url: "{{ route('updateChildmodilities') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#editChildModal').modal('hide');
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 100);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
        updateChildmodilities();
        //// show Child function
        {{--function showChild() {--}}
        {{--    $('.list-group-item').click(function() {--}}

        {{--        $.ajaxSetup({--}}
        {{--            headers: {--}}
        {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--            }--}}
        {{--        });--}}
        {{--        var id =($(this).attr("data-id"));--}}
        {{--        var url = "{{URL('/ocap_new/modalities')}}";--}}
        {{--        var newPath = url+ "/"+ id+"/showChild/";--}}

        {{--        $.ajax({--}}
        {{--            type:"GET",--}}
        {{--            dataType: 'html',--}}
        {{--            url:newPath,--}}
        {{--            success : function(results) {--}}
        {{--                $('#childClass').html(results);--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--}--}}
        {{--showChild();--}}
        //// Add Parent Function
        function modalitiesStore(){
            $('#saveParent').click(function (e) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $(this).html('Sending..');

                $.ajax({
                    data: $('#parentForm').serialize(),
                    url: "{{ route('modalities.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {

                        $('#parentForm').trigger("reset");
                        $('#add_phase').modal('hide');

                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 100);
                    },

                    error: function (data) {
                        console.log('Error:', data);
                        // $('#saveParent').html('Save Changes');
                    }
                });
            });
        }
        modalitiesStore();
        /// update Modalities Function
        function updateModalities() {
            $("#editParentForm").submit(function(e) {


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();

                $.ajax({
                    data: $('#editParentForm').serialize(),
                    url: "{{ route('updateModalities') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('#editphase').modal('hide');
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 100);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

        }
        updateModalities();
        /// end of update Modalities Function
        // Add Child function
        function childmodilitiesStore() {
            $('#saveChild').click(function (e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $(this).html('Sending..');
                $.ajax({
                    data: $('#childForm').serialize(),
                    url: "{{ route('childmodilities.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $('#childForm').trigger("reset");
                        $('#addsteps').modal('hide');
                        location.reload();
                        // window.setTimeout(function () {
                        //     location.href = '{{ route('modalities.index') }}';
                        // }, 100);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveChild').html('Save Changes');
                    }
                });
            });
        }
        childmodilitiesStore();
        // Parent Delete function
        function modalitiesDestroy() {
            $('body').on('click', '.deleteParent', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                var url = "{{URL('/ocap_new/modalities')}}";
                var newPath = url+ "/"+ parent_id+"/destroy/";
                if( confirm("Are You sure want to delete !") ==true)
                {

                    $(this).parent().removeClass('old_row_parent').addClass('new_row_parent').html('<span href="#" data-id= '+parent_id+' class="dropdown-item restoreParent"><i class="far fa-trash-alt"></i>&nbsp;Undo</span><div id="parent_div">\n' +
                        '</div>');

                    var timeLeft = 15;
                    var elem = document.getElementById('parent_div');

                    var timerId = setInterval(countdown, 1000);

                    function countdown() {
                        if (timeLeft == 0) {
                            clearTimeout(timerId);
                            //doSomething();
                        } else {
                            elem.innerHTML = timeLeft + 'seconds remaining';
                            timeLeft--;
                        }
                    }
                    $.ajax({
                        type: "GET",
                        url: newPath,
                        success: function (data) {
                            console.log(data);
                            window.setTimeout(function () {
                                location.href = '{{ route('modalities.index') }}';
                            }, 15000);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }

            });
        }
        modalitiesDestroy();
        // Child Delete function
        function childmodilitiesDestroy() {
            $('body').on('click', '.deleteChild', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                var url = "{{URL('/ocap_new/childmodilities')}}";
                var newPath = url+ "/"+ parent_id+"/destroy/";
                if( confirm("Are You sure want to delete !") ==true)
                {
                    //$('.undoChild').append('<li class="list-group-item"><a href="#" data-id= '+parent_id+' class="restoreChild">Undo<i class="fa fa-undo" aria-hidden="true"></i></a></li>');

                    $(this).parent().removeClass('old_row').addClass('new_row').html('<span href="#" data-id= '+parent_id+' class="restoreChild dropdown-item">Undo</span><div id="some_div">\n' +
                        '</div>');

                    var timeLeft = 15;
                    var elem = document.getElementById('some_div');

                    var timerId = setInterval(countdown, 1000);

                    function countdown() {
                        if (timeLeft == 0) {
                            clearTimeout(timerId);
                            //doSomething();
                        } else {
                            elem.innerHTML = timeLeft + 'seconds remaining';
                            timeLeft--;
                        }
                    }

                    $.ajax({
                        type: "GET",
                        url: newPath,
                        success: function (data) {
                            console.log(data);
                            window.setTimeout(function () {
                                location.href = '{{ route('modalities.index') }}';
                            }, 30000);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }

            });
        }
        childmodilitiesDestroy();
        // Child Restore function
        function restoreChild() {

            $('body').on('click', '.restoreChild', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                var url = "{{URL('/ocap_new/childmodilities')}}";
                var newPath = url+ "/"+ parent_id+"/restoreChild/";
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 10);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
        restoreChild();
        // Restore Parent function
        function restoreParent() {
            $('body').on('click', '.restoreParent', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                var url = "{{URL('/ocap_new/modalities')}}";
                var newPath = url+ "/"+ parent_id+"/restoreParent/";
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        console.log(data);
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 10);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
        restoreParent();
        //  Replicate Parent function
        function replicateParent(){

            $('body').on('click', '.replicateParent', function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                console.log(parent_id);
                var url = "{{URL('/ocap_new/modalities')}}";
                var newPath = url+ "/"+ parent_id+"/replicateParent/";
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        console.log(data);
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 10);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        }
        replicateParent();

        //// show Child function

            $('.showPhasesSteps').click(function() {
                $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                var id =($(this).attr("data-id"))
                var url = "{{URL('ocap_new/modalities')}}";
                var newPath = url+ "/"+ id+"/showChild/";
                $.ajax({
                    type:"GET",
                    dataType: 'html',
                    url:newPath,
                    success : function(results) {
                        $('#childClass').html(results);
                    }
                });
            });
    </script>
@stop
