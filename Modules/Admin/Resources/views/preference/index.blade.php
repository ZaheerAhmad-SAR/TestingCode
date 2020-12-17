@extends('layouts.home')

@section('title')
    <title> Preferences | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Preferences</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Preferences</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <h4 class="card-title">Preferences list</h4>
                            </div>
                            <div class="col-md-3 text-right">
                                @if(hasPermission(auth()->user(), 'preference.loadAddPreferenceForm'))
                                <button type="button" class="btn btn-primary"
                                    onclick="openAddPreferencePopup(0);">Add new preference</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body py-5">
                            <div class="row">
                                <div class="col-12">
                                    @foreach ($preferences as $preference)
                                        <div class="form-row">
                                            <div class="col-12 mb-3">
                                                <label>{{ $preference->preference_title }}</label>
                                                @if(hasPermission(auth()->user(), 'preference.loadAddPreferenceForm'))
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href="javascript:void(0);" class="text text-danger" onclick="openAddPreferencePopup({{ $preference->id }});">Edit</a>
                                                @endif
                                                <br>
                                                @if ($preference->is_selectable == 'yes')
                                                    @php
                                                    $preference_options = explode('|', $preference->preference_options);
                                                    @endphp
                                                    @foreach ($preference_options as $preference_option)
                                                        @php
                                                        $optionId =
                                                        Illuminate\Support\Str::snake($preference->preference_value) .
                                                        $preference->id;
                                                        @endphp
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" name="preference_{{ $preference->id }}"
                                                                id="preference_{{ $optionId }}"
                                                                value="{{ $preference_option }}"
                                                                class="custom-control-input"
                                                                {{ $preference->preference_value == $preference_option ? 'checked="checked"' : '' }}
                                                                onchange="updatePreference('{{ $preference->id }}', this.value);">
                                                            <label class="custom-control-label"
                                                                for="preference_{{ $optionId }}">{{ $preference_option }}</label>
                                                        </div>
                                                    @endforeach
                                                    <p><br>
                                                        <strong>Usage (for developers):</strong><br>
                                                        <code>
                                                            use Modules\Admin\Entities\Preference;<br>
                                                            if(Preference::getPreference('{{ $preference->preference_title }}') == '{{ $preference->preference_value }}'){ // code goes here } </code>
                                                    </p>
                                                @else
                                                    <input type="text" name="preference_{{ $preference->id }}"
                                                        id="preference_{{ $preference->id }}" class="form-control"
                                                        placeholder="preference value"
                                                        value="{{ $preference->preference_value }}"
                                                        onchange="updatePreference('{{ $preference->id }}', this.value);">

                                                        <p><br>
                                                            <strong>Usage (for developers):</strong><br>
                                                            <code>
                                                                use Modules\Admin\Entities\Preference;<br>
                                                                $variableName = Preference::getPreference('{{ $preference->preference_title }}');</code>
                                                        </p>
                                                @endif

                                            </div>
                                        </div>
                                        <hr class="hr-line">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
    @include('admin::preference.preference_popup')
@endsection
@push('styles')
    @include('formsubmission::forms.form_css')
@endpush
@push('script')
    <script>
        function updatePreference(id, preference_value) {
            var csrf_token = '&_token={{ csrf_token() }}';
            var frmData = 'id=' + id + '&' + 'preference_value=' + preference_value + csrf_token;
            $.ajax({
                url: "{{ route('preference.updatePreference') }}",
                type: 'POST',
                data: frmData,
                success: function(response) {
                    //alert(response);
                }
            });
        }

        function openAddPreferencePopup(preferenceId) {
            $("#addNewPreferencePopUp").modal('show');
            loadAddPreferenceForm(preferenceId);
        }

        function loadAddPreferenceForm(preferenceId) {
            $.ajax({
                url: "{{ route('preference.loadAddPreferenceForm') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'preferenceId': preferenceId,
                },
                success: function(response) {
                    $('#addNewPreferenceMainDiv').empty();
                    $("#addNewPreferenceMainDiv").html(response);
                }
            });
        }

        function submitAddPreferenceForm() {
            $.ajax({
                url: "{{ route('preference.submitAddPreferenceForm') }}",
                type: 'POST',
                data: $("#preferenceForm").serialize(),
                success: function(response) {
                    $("#addNewPreferencePopUp").modal('hide');
                    $('#addNewPreferenceMainDiv').empty();
                    location.reload();
                }
            });
        }
        $(document).ready(function() {
            $.validator.setDefaults({
                submitHandler: function() {
                    submitAddPreferenceForm();
                }
            });
            $.validator.addMethod("constant", function(value, element) {
                return this.optional(element) || /^[A-Z_]+$/.test(value);
            }, "Capital letters and underscore only please");

            $("#preferenceForm").validate({
                rules: {
                    preference_title: {
                        required: true,
                        constant: true
                    },
                    preference_value: "required",
                    preference_options: {
                        required: function() {
                            if ($("#is_selectable_yes").is(':checked')) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    preference_title: {
                        required: "Please enter preference title",
                        constant: $.validator.format("Capital letters and underscore only please")
                    },
                    preference_value: "Please enter preference value",
                    preference_options: "Please enter preference options",
                }
            });
        });

    </script>
@endpush
