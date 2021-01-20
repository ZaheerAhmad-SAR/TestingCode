@extends('layouts.home')

@section('title')
    <title> Activity Log | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('content')
 <div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Activity Log</h4></div>
                <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Activities</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->
    <!-- START: Card Data-->
     <div class="row">
         <div class="col-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive list">
                        <table class="table table-bordered dataTable" id="laravel_crud">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Online</th>
                                    <th>Offline</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{ dd($activities->user) }} --}}
                                @foreach($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->name }}</td>
                                        <td>{{ $activity->online_at}}</td>
                                        <td>
                                            {{ $activity->offline_at}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>

        </div>
</div>
    <!-- END: Card DATA-->
</div>
@endsection
@section('styles')
@stop
@section('script')
<script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('public/dist/vendors/datatable/buttons/js/buttons.html5.min.js') }}"></script>
@stop
