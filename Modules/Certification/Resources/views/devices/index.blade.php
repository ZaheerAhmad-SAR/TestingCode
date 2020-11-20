@extends ('layouts.home')
@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12 align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Devices List</h4>
                    </div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Devices</li>
                    </ol>
                </div>
            </div>
            @if(session()->has('message'))
                <div class="col-lg-12 success-alert">
                    <div class="alert alert-primary success-msg" role="alert">
                        {{ session()->get('message') }}
                    </div>
                </div>
            @endif
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('devices_certify.index')}}" method="get" class="filter-form">
                            @csrf
                            <div class="form-row" style="padding: 10px;">
                                <div class="form-group col-md-4">
                                    <select class="form-control" name="modality">
                                        <option>---Device Category---</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <select class="form-control" name="status">
                                        <option>---Device Status---</option>
                                        <option value="pending">Pending</option>
                                        <option value="accepted">Accepted</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="deficient">Deficient</option>
                                        <option value="duplicate">Duplicate</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="device_manf" class="form-control" placeholder="Manufacturer">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="device_sn" class="form-control" placeholder="Serial #">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="device_model" class="form-control" placeholder="Device Model">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="cert_issueDate" class="form-control" placeholder="Issue Date" onfocus="(this.type='date')">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name="expiry_date" class="form-control" placeholder="Expiry Date" onfocus="(this.type='date')">
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
                                        <th>Device Type</th>
                                        <th>CERTIFICATE ID</th>
                                        <th>Manufacturer</th>
                                        <th>Serial #</th>
                                        <th>Device Model</th>
                                        <th>Site Name</th>
                                        <th>Issue Date</th>
                                        <th>Expiry Date</th>
                                        <th>CER Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devices as $key =>$device)
                                    <tr>
                                        <td>{{$device->device_categ}}</td>
                                        <td>{{$device->trans_no}}</td>
                                        <td>{{$device->device_manf}}</td>
                                        <td>{{$device->device_sn}}</td>
                                        <td>{{$device->device_model}}</td>
                                        <td>{{$device->site_name}}</td>
                                        <td>{{$device->cert_issueDate}}</td>
                                        <td></td>
                                        <td >{{$device->status}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                            {{$devices->links()}}
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection

@section('script')
<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>
<script type="text/javascript">
    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form input').val(" ");
        $('.filter-form select').val(" ");
        // submit the filter form
        $('.submit-filter').click();
    });
</script>
@endsection