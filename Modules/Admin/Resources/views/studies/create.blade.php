@extends('layouts.app')
@section('title')
    <title> Create Study | {{ config('app.name', 'Laravel') }}</title>

@stop
@section('content')

    <form action="{{route('studies.store')}}" enctype="multipart/form-data" method="POST" id="add_study_1">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Create Study</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_short_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Study Short Name</label>
                                    <input type="text" class="form-control" name="study_short_name" value="{{old('study_short_name')}}">
                                    @error('study_short_name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_title')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Study Title</label>
                                    <input type="text" class="form-control" name="study_title" value="{{old('study_title')}}">
                                    @error('study_title')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_code')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Study Code</label>
                                    <input type="text" class="form-control" name="study_code" value="{{old('study_code')}}">
                                    @error('study_code')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('protocol_number')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Protocol Number</label>
                                    <input type="text" class="form-control" name="protocol_number" value="{{old('protocol_number')}}">
                                    @error('protocol_number')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('trial_registry_id')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Trial Registery ID</label>
                                    <input type="text" class="form-control" name="trial_registry_id" value="{{old('trial_registry_id')}}">
                                    @error('trial_registry_id')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_sponsor')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Study Sponsor</label>
                                    <input type="text" class="form-control" name="study_sponsor" value="{{old('study_sponsor')}}">
                                    @error('study_sponsor')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('start_date')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{old('start_date')}}">
                                    @error('start_date')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('end_date')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}">
                                    @error('end_date')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('description')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Description</label>
                                    <textarea type="textarea" class="form-control" name="description" value="{{old('description')}}">
                                    </textarea>
                                    @error('description')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2>Assign Users</h2>
                        </div>
                        @include('admin::assignRoles.assign_users', ['users'=>$users, 'assigned_users'=>[], 'errors'=>$errors ])

                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2>Assign Sites</h2>
                        </div>
                        <div class="form-row">
                            <div class="{!! ($errors->has('roles')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                <label>Select Sites</label>
                                <select class="" id="select-sites" multiple="multiple" name="sites[]">
                                    @foreach($sites as $site)
                                        <option value="{{$site->id}}">{{$site->site_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="{!! ($errors->has('roles')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Select Sites</label>
                                    <select class="" id="select-sites" multiple="multiple" name="sites[]">
                                        @foreach($sites as $site)
                                            <option value="{{$site->id}}">{{$site->site_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('roles')
                                <span class="text-danger small">
                            @error('roles')
                            <span class="text-danger small">
                                    {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="pull-right">
                            <a href="{!! route('studies.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </form>
@endsection
@section('script')
<script type="text/javascript">
    $('#study_add_1').submit(function(e){
        $('#select_users_to option').prop('selected', true);
    });
        $(document).ready(function() {
		        $('#select_users').multiselect({
                    search: {
                        left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                        right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                    },
                    fireSearch: function(value) {
                        return value.length > 1;
                    }
                });
	        });

        $(document).ready(function () {

            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');

            allWells.hide();

            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this);

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn-primary').addClass('btn-default');
                    $item.addClass('btn-primary');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function(){
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for(var i=0; i<curInputs.length; i++){
                    if (!curInputs[i].validity.valid){
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid)
                    nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-primary').trigger('click');
        });
    </script>
    @endsection
