
@extends('layouts.app')
@section('title')
    <title> Modalities | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <style>
        .dropdown{
            position: absolute;
        }
        .dropdown-menu {
            width: 25px;
            min-height: 31px;
            top: -5px;
            left: -40px;
        }
        .dropdown .dropdown-menu a:hover{
            background: #fff;
            color: #4D4D4D;
            cursor: pointer;
        }
        .dropdown:hover .dropdown-menu {
            display:inline;
            position: absolute;
            background-color: #fff;
        }
        ul{
            list-style: none;
        }
        .dropdown .dropdown-menu{
            min-width: 100px;
            font-size: 12px;
        }
        .dropdown .dropdown-menu a {
            padding: 3px 3px 3px 3px;
            display: block;
        }
        .dropdown .dropdown-menu i{
            font-size: 14px;
        }
        .custom-btn{
            border: 1px solid cadetblue;
            border-radius: 4px;
            margin-bottom: 3px;
            background: #fff;
        }
        .blue-color{
            color: cadetblue;
        }
        .blue-background{
            background: cadetblue;
        }
        .color-white{
            color: #ffffff;
        }
    </style>
    <div class="row">
        <div class="col-lg-12" style="min-height: 20px;"></div>
        <div class="col-lg-12">
                    <div class="col-lg-12">
                        <div class="panel">
                            <p class="color-black">Modalities</p>
                        </div>
                    </div>
            <div class="col-lg-12" style="min-height: 20px;"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="modal" id="parentModel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="width: inherit;">
                                        <div class="modal-header blue-background  color-white">
                                            <h5 class="modal-title" id="childModalLabel">Add Parent</h5>
                                        </div>
                                        <form id="parentForm" name="parentForm" class="form-horizontal">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="modility_name" name="modility_name" placeholder="Enter Modility name" maxlength="50"  value="{{old('modility_name')}}" required/>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="parent_yes" value="1">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn custom-btn blue-color" id="saveParent" value="create" style="margin-top: 4px;"><i class="fa fa-save blue-color"></i> Save
                                                </button>
                                                <button type="button" class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- Button trigger modal -->
                            <!--  <button type="button" class="btn btn-outline-primary">
                             Create Child
                             </button> -->
                            <!-- Modal -->
                            <div class="modal" id="childModal" tabindex="-1" role="dialog" aria-labelledby="childModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content" style="width: inherit;">
                                        <div class="modal-header blue-background  color-white">
                                            <h5 class="modal-title" id="childModalLabel">Add Child</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="childForm" name="childForm" class="form-horizontal">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="col-sm-3" for="modility_name">Child Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="modility_name" name="modility_name" value="{{old('modility_name')}}" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3" for="modility_name">Modality</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="parent_id" id="parent_id">
                                                            <option value="">Select Parent Modality</option>
                                                            @foreach ($modalities as $modility)
                                                                <option value="{{ $modility->id }}">{{ $modility->modility_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="saveChild" class="btn custom-btn blue-color" style="margin-top: 4px;"><i class="fa fa-save blue-color"></i> Save</button>
                                                <button type="button" class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="panel-body" style="min-height: 450px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="custom-btn blue-color"  data-toggle="modal" data-target="#parentModel"><i class="fa fa-plus blue-color"></i> add</button>
                            <ul class="list-group">
                                @foreach($modalities as $modality)
                                    <li data-id="{{$modality->id}}" id="{{$modality->id}}" style="border-bottom: 2px solid rgb(71, 84, 109) !important; background-color: #ECEEF0 !important;" class="list-group-item getParentValue">
                                        <a class="" href="#"> {{$modality->modility_name}}</a>
                                        <span class="pull-right">
                                        <ul>
                                        <li class="dropdown">
                                            <i class="fa fa-cog" data-toggle="modal"></i>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="modal-toggle open_modal " at="{{$modality->id}}" data-toggle="modal" data-target="#editParentModal" data-id="{{$modality->id}}" >
                                                        <i class="fa fa-pencil" aria-hidden="true"></i> Edit Phase
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" data-id="{{$modality->id}}" class="deleteParent">
                                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete Phase
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="replicateParent" data-id="{{$modality->id}}">
                                                        <i class="fa fa-clone" aria-hidden="true"></i> Clone  Item
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>
                                    </ul>
                                </span>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                        <div class="col-sm-6">
                            <button class="custom-btn blue-color"  data-toggle="modal" data-target="#childModal"><i class="fa fa-plus blue-color"></i> add</button>
                            <div id="childClass"></div>
                        </div>
                    </div>
                </div>
        <!-- <div class="row"> -->
        <div class="modal" id="editParentModal" tabindex="-1" role="dialog" aria-labelledby="editParentModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="width: inherit;">
                    <div class="modal-header blue-background  color-white">
                        <h5 class="modal-title" id="editParentModalLabel">Edit Parent</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editParentForm" name="editParentForm">
                        <div class="modal-body" id="modal-body">
                            <input type="hidden" name="parent_yes" value="1">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10" id="editParentClass"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn custom-btn blue-color" name="saveEditParent" id="saveEditParent" value="create" style="margin-top: 4px;"><i class="fa fa-save blue-color"></i> Save
                            </button>

                            <button type="button" class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- </div> -->
        <div class="row">
            <div class="modal" id="editChildModal" tabindex="-1" role="dialog" aria-labelledby="editParentModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: inherit;">
                        <div class="modal-header blue-background  color-white">
                            <h5 class="modal-title" id="editChildModalLabel">Edit Child</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form class="" id="editChildForm" name="editChildForm">
                            <div class="modal-body" id="modal-body">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10" id="editChildClass">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn custom-btn blue-color" name="saveEditChild" id="saveEditChild" value="create" style="margin-top: 4px;"><i class="fa fa-save blue-color"></i> Save
                                </button>
                                <button type="button" class="btn custom-btn blue-color" data-dismiss="modal"><i class="fa fa-window-close blue-color" aria-hidden="true"></i> Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script type="text/javascript">
        // Edit Parent Modility by id
        function editParent()
        {
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

        function editChild()
        {
            $(document).on('click','.open_modal_edit_child',function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var url = "{{URL('/ocap/childmodilities/')}}";
                //var url = "{{URL('childmodilities/')}}";
                var child_id = $(this).data('id');

                var newPath = url+ "/"+child_id+"/edit/";
                $.ajax({
                    type:"GET",
                    dataType: 'html',
                    url:newPath,
                    success : function(results)
                    {
                        $('#editChildClass').html(results);
                        $('#editChildModal').modal('show');
                    }
                });
            });
        }

        editChild();



        /// Update Child Modility Function


        function updateChildmodilities ()
        {
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

        function showChild()
        {
            $('.list-group-item').click(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id =($(this).attr("data-id"));

                var url = "{{URL('/ocap/modalities')}}";
                //var url = "{{URL('modalities')}}";

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
        }

        showChild();

        //// Add Parent Function

        function modalitiesStore()
        {
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
                        $('#parentModel').modal('hide');

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

        function updateModalities()
        {
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
                        $('#editParentModal').modal('hide');
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 100);

                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveEditParent').html('Save Changes');
                    }
                });
            });

        }

        updateModalities();

        /// end of update Modalities Function


        // Add Child function
        function childmodilitiesStore()
        {
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
                        $('#childModal').modal('hide');
                        window.setTimeout(function () {
                            location.href = '{{ route('modalities.index') }}';
                        }, 100);
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

        function modalitiesDestroy()
        {
            $('body').on('click', '.deleteParent', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                //var url = "{{URL('modalities')}}";
                var url = "{{URL('/ocap/modalities')}}";
                var newPath = url+ "/"+ parent_id+"/destroy/";
                if( confirm("Are You sure want to delete !") ==true)
                {

                    $(this).parent().removeClass('old_row_parent').addClass('new_row_parent').html('<a href="#" data-id= '+parent_id+' class="restoreParent">Undo</a><div id="parent_div">\n' +
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

        function childmodilitiesDestroy()
        {
            $('body').on('click', '.deleteChild', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                var parent_id = $(this).data("id");
                var url = "{{URL('/ocap/childmodilities')}}";
                //var url = "{{URL('childmodilities')}}";
                var newPath = url+ "/"+ parent_id+"/destroy/";


                if( confirm("Are You sure want to delete !") ==true)
                {
                    //$('.undoChild').append('<li class="list-group-item"><a href="#" data-id= '+parent_id+' class="restoreChild">Undo<i class="fa fa-undo" aria-hidden="true"></i></a></li>');

                    $(this).parent().removeClass('old_row').addClass('new_row').html('<a href="#" data-id= '+parent_id+' class="restoreChild">Undo</a><div id="some_div">\n' +
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

        function restoreChild()
        {

            $('body').on('click', '.restoreChild', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                var url = "{{URL('childmodilities')}}";
                var newPath = url+ "/"+ parent_id+"/restoreChild/";
                $.ajax({
                    type: "GET",
                    url: newPath,
                    success: function (data) {
                        //console.log(data);
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

        function restoreParent()
        {

            $('body').on('click', '.restoreParent', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                var url = "{{URL('modalities')}}";
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

        function replicateParent()
        {

            $('body').on('click', '.replicateParent', function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var parent_id = $(this).data("id");
                console.log(parent_id);
                var url = "{{URL('/ocap/modalities')}}";
                //var url = "{{URL('modalities')}}";
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
    function showChildModalities() {
        $('.list-group-item').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id =($(this).attr("data-id"));
            var url = "{{URL('/ocap/modalities')}}";
            //var url = "{{URL('modalities')}}";
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
    }
    showChildModalities();


    $( ".getParentValue" ).dblclick(function()
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var parentId = $(this).data("id");
        var url = "{{URL('modalities')}}";
        var newPath = url+ "/"+ parentId+"/edit/";
        $.ajax({
            type:"GET",
            dataType: 'html',
            url:newPath,
            success : function(results) {
                console.log(results);
                $('#childClass').html(results);
            }
        });
    });

</script>
@endsection

