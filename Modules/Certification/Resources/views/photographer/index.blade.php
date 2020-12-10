@extends ('layouts.home')
@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Photographers List</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Photographers</li>
                    </ol>
                </div>
            </div>
            {{ showMessage() }}
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('photographers.index')}}" method="get" class="filter-form">
                            @csrf
                            <div class="form-row" style="padding: 10px;">
                                <div class="form-group col-md-4">
                                    <input type="text" name="photographer_name" class="form-control" placeholder="Photographer Name">
                                </div>
                                <div class="form-group col-md-4">
                                    <select class="form-control" name="modality">
                                        <option>---Modality---</option>
                                        @foreach($imaging_modality as $key =>$value)
                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="site" class="form-control" placeholder="Site">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="transmission_number" class="form-control" placeholder="Transmission Number">
                                </div>
                                <div class="form-group col-md-4">
                                    <select class="form-control" name="status">
                                        <option>---Certification Status---</option>
                                        <option value="new">New</option>
                                        <option value="in process">In Process</option>
                                        <option value="provisional">Provisionally Certified</option>
                                        <option value="full">Full</option>
                                        <option value="suspended">Suspended</option>
                                        <option value="expired">Expired</option>
                                        <option value="audit">In Audit</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" style="text-align: right;">
                                    <button class="btn btn-outline-warning reset-filter"><i class="fas fa-undo-alt" aria-hidden="true"></i> Reset</button>
                                    <button type="submit" class="btn btn-primary submit-filter"><i class="fas fa-filter" aria-hidden="true"></i> Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:10%">Photographer</th>
                                        <th style="width:10%">Email</th>
                                        <th style="width:10%">Study Site</th>
                                        <th width="5%">Site ID</th>
                                        <th width="10%">PI</th>
                                        <th width="10%">Modality</th>
                                        <th width="10%">Device Model</th>
                                        <th width="5%">CER Status</th>
                                        <th width="10%">Certification Date</th>
                                        <th width="10%">Transmission #</th>
                                        <th width="10%">CER File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($photographers as $key =>$photographer)
                                    <tr>
                                        <td>{{$photographer->photographer_name}}</td>
                                        <td >{{$photographer->email_address}}</td>
                                        <td >{{$photographer->study_site}}</td>
                                        <td >{{$photographer->site_id}}</td>
                                        <td >{{$photographer->principal_investigator}}</td>
                                        <td >{{$photographer->imaging_modality_req}}</td>
                                        <td >{{$photographer->device_model}}</td>
                                        <td >{{$photographer->certificate_status}}</td>
                                        <td >{{$photographer->certificate_date}}</td>
                                        <td >{{$photographer->transmission_number}}</td>
                                        <td >{{$photographer->certificate_file}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$photographers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
<link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
@endsection
@section('script')
<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
<script type="text/javascript">
    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form input').val(" ");
        $('.filter-form select').val(" ");
        // submit the filter form modality
        $('.submit-filter').click();
    });
    $('select[name="modality"]').select2();
</script>
@endsection
