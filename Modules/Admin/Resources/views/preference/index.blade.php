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
                        <h4 class="card-title">Preferences list</h4>
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
                                                                onchange="updatePreference('{{ $preference->id }}', this.value);"
                                                                >
                                                            <label class="custom-control-label"
                                                                for="preference_{{ $optionId }}">{{ $preference_option }}</label>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <input
                                                        type="text"
                                                        name="preference_{{ $preference->id }}"
                                                        id="preference_{{ $preference->id }}"
                                                        class="form-control"
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

    </script>
@endpush
