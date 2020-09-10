@extends('layouts.app')
@section('title')
    <title> Create Modalities | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <form action="{{route('modalities.store')}}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h2>Create Modalities </h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="{!! ($errors->has('modility_name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">

                                    <label>Modility Name</label>
                                    <input type="text" class="form-control" name="modility_name" value="{{old('modility_name')}}">
                                    @error('modility_name')
                                    <span class="text-danger small">
                                    {{ $message }}
                            </span>
                                    @enderror
                                </div>
                            </div>

                            {{--                            <div class="col-md-3">--}}
                            {{--                                <div class="{!! ($errors->has('name')) ?'form-group col-md-12 has-error':'form-group col-md-12' !!}">--}}

                            {{--                                    <label class="radio-inline">--}}
                            {{--                                        <input type="checkbox" id="parent_yes" value="1" name="parent_yes">Yes,Parent Modality</label>--}}
                            {{--                                    @error('name')--}}
                            {{--                                    <span class="text-danger small"> --}}
                            {{--                                    {{ $message }}--}}
                            {{--                            </span>--}}
                            {{--                                    @enderror--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                            <input type="hidden" name="parent_yes" value="1">

                            <div class="form-group">
                                <select class="form-control" name="parent_id" id="parent_id">
                                    <option value="">Select Parent Modality</option>

                                    @foreach ($modalities as $modility)
                                        <option value="{{ $modility->id }}">{{ $modility->modility_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pull-right">
                                <a href="{!! route('modalities.index') !!}" class="btn btn-danger">Cancel</a>
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
        $(document).on('change', '[type=checkbox]', function() {
            if ($(this).is(":checked")) {
                $("#parent_id").attr("disabled", "disabled");
            } else {
                $("#parent_id").removeAttr("disabled");
            }

        });

        // $(document).on('change', '#parent_yes', function(){
        //     //debugger;
        //     if($(this).prop('checked')){
        //         $('#parent_id').attr('disabled', 'disabled');
        //     } else {
        //         $('#parent_id').removeAttr('disabled');
        //     }
        // });
    </script>
@endsection
