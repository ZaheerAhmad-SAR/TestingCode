@extends('layouts.app')
@section('title')
    <title> Create Study | {{ config('app.name', 'Laravel') }}</title>

@stop
@section('content')

    <form action="{{route('studies.store')}}" enctype="multipart/form-data" method="POST">
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
                           {{-- <div class="col-md-3">
                                <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Select Study Admin</label>
                                    <select class="form-control" id="selectUser" name="study_user">
                                        @foreach($users as $user)
                                            <option value=""> ---- Select ---- </option>
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('users')
                                <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="{!! ($errors->has('study_phase')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Select Study Phase</label>
                                    <select class="form-control" id="select-phase" name="study_phase">
                                        <option value=""> ---- Select ---- </option>
                                        <option value="0">Phase 0</option>
                                        <option value="1">Phase 1</option>
                                        <option value="2">Phase 2</option>
                                        <option value="3">Phase 3</option>
                                        <option value="4">Phase 4</option>
                                        <option value="5">Phase 5</option>
                                        <option value="6">Phase 6</option>
                                    </select>
                                    --}}{{--<br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="0"> Phase 0</span>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="1"> Phase 1</span>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="2"> Phase 2</span>
                                    <br>
                                    <span><input type="radio" class="form-control" name="study_phase" value="3"> Phase 3</span>--}}{{--
                                    @error('study_phase')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>--}}
                        </div>

                    </div>
                    <div class="panel-body">
                        <div class="panel-heading">
                            <h2>Assign Users</h2>
                        </div>
                        <div class="form-row">
                            <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                <label>Select Users</label>
                                <select class="form-control" id="select-users" multiple="multiple" name="users[]">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('roles')
                            <span class="text-danger small">
                                    {{ $message }}
                            </span>
                            @enderror
                        </div>

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
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#select-users').multiselect();
        });
        $(document).ready(function() {
            $('#select-sites').multiSelect({ keepOrder: true });
        });
    </script>
    <script type="text/javascript">

    </script>
@endsection
--}}


@extends('layouts.app')
@section('title')
    <title> Create Study | {{ config('app.name', 'Laravel') }}</title>

@stop
@section('content')

    <form action="{{route('studies.store')}}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="container">
            <div class="col-lg-12">
                    <div class="panel-heading">
                        <h2>Create Study</h2>
                    </div>
            </div>
                    <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                    <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                    <p>Study Info</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                    <p>Assign Users</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                    <p>Assign Sites</p>
                </div>
            </div>
        </div>
        <form role="form">
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3>Study Info</h3>
                        <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Study Short Name</label>
                            <input maxlength="20" type="text" class="form-control"  name="study_short_name" placeholder="Study Short Name" value="{{old('study_short_name')}}">

                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Study Title</label>
                            <input maxlength="255" type="text" class="form-control" placeholder="Study Title" name="study_title" value="{{old('study_title')}}">
                        </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                               <label class="control-label">Study Code</label>
                               <input maxlength="255" type="text"  class="form-control" placeholder="Study Title" name="study_title" value="{{old('study_title')}}">
                           </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Protocol Number</label>
                                <input maxlength="25" type="text" class="form-control" placeholder="Protocol Number" name="protocol_number" value="{{old('protocol_number')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Trial Registery ID</label>
                                <input maxlength="15" type="text"  class="form-control" placeholder="Trial Registery ID" name="protocol_number" value="{{old('study_title')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Study Sponsor</label>
                                <input maxlength="15" type="text"  class="form-control" placeholder="Study Sponsor" name="study_sponsor" value="{{old('study_title')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Start Date</label>
                                <input type="date"  class="form-control" name="start_date" value="{{old('start_date')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{old('end_date')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea  class="form-control" name="description" value="{{old('description')}}"> </textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pull-left">
                    <button class="btn btn-primary center-block nextBtn btn-lg pull-right" type="button" >Next</button>
                </div>
            </div>
            <div class="row setup-content" id="step-2">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3>Select Users</h3>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="{!! ($errors->has('users')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">
                                    <label>Select Users</label>
                                    <select class="form-control" id="select-users" required="" multiple="multiple" name="users[]">
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
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
                        <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-3">
                <div class="col-xs-12">
                    <div class="col-md-12">
                        <h3> Assign Sites</h3>
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
                                    {{ $message }}
                            </span>
                            @enderror
                        </div>
                        <div class="pull-right">
                            <a href="{!! route('studies.index') !!}" class="btn btn-danger">Cancel</a>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
{{--                        <button class="btn btn-success btn-lg pull-right" type="submit">Finish!</button>--}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endsection

@section('scripts')
    <script type="text/javascript">
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
