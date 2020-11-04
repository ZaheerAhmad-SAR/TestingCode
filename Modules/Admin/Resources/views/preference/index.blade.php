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
                                <button type="button" class="btn btn-warning"
                                    onclick="openAddPreferencePopup('{{ $studyId }}');">Add new preference</button>
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
                                                <label for="username">{{ $preference->preference_title }}</label><br>
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
                                                @else
                                                    <input type="text" name="preference_{{ $preference->id }}"
                                                        id="preference_{{ $preference->id }}" class="form-control"
                                                        placeholder="preference value"
                                                        value="{{ $preference->preference_value }}"
                                                        onchange="updatePreference('{{ $preference->id }}', this.value);">
                                                @endif
                                                <hr class="hr-line">

                                            </div>
                                        </div>
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

        function openAddPreferencePopup(studyId) {
            $("#addNewPreferencePopUp").modal('show');
            loadAddPreferenceForm(studyId);
        }

        function loadAddPreferenceForm(studyId) {
            $.ajax({
                url: "{{ route('preference.loadAddPreferenceForm') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'studyId': studyId
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
            $("#preferenceForm").validate({
                rules: {
                    preference_title: "required",
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
                    preference_title: "Please enter preference title",
                    preference_value: "Please enter preference value",
                    preference_options: "Please enter preference options",
                }
            });
        });

    </script>
@endpush
