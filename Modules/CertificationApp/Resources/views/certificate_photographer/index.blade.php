@extends ('layouts.home')

@section('title')
    <title> Certification Photographer | {{ config('app.name', 'Laravel') }}</title>
@stop

@section('styles')

    <style type="text/css">
        /*.table{table-layout: fixed;}*/

        .select2-container--default
        .select2-selection--single {
            background-color: #fff;
            border: transparent !important;
            border-radius: 4px;
        }
        .select2-selection__rendered {
            font-weight: 400;
            line-height: 1.5;
            color: #495057 !important;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: solid black 1px;
            outline: 0;
        }

        .select2-container--default .select2-selection--multiple {
            background-color: white;
            border: 1px solid #485e9029 !important; 
            border-radius: 4px;
            cursor: text;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('public/dist/vendors/summernote/summernote-bs4.css') }}">

    <!-- date range picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('public/dist/vendors/select2/css/select2-bootstrap.min.css') }}"/>
@endsection

@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Certification Photographer</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certification Photographer</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-sm-12 mt-3">
                <div class="card">
                   
                    <form action="{{route('certification-photographer.index')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <div class="form-group col-md-3">
                                <label for="trans_id">Transmission#</label>
                                <input type="text" name="trans_id" id="trans_id" class="form-control filter-form-data" value="{{ request()->trans_id }}" placeholder="Transmission#">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="study">Study Name</label>
                                <input type="text" name="study" id="study" class="form-control filter-form-data" value="{{ request()->study }}" placeholder="Study Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="photographer_name">Photographer Name</label>
                                <input type="text" name="photographer_name" id="photographer_name" class="form-control filter-form-data" value="{{ request()->photographer_name }}" placeholder="Photographer Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="certification">Certification</label>
                                <input type="text" name="certification" id="certification" class="form-control filter-form-data" value="{{ request()->certification }}" placeholder="Certification">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="site">Site Name</label>
                                <input type="text" name="site" id="site" class="form-control filter-form-data" value="{{ request()->site }}" placeholder="Site Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputState"> Transmission Status</label>
                                <select id="status" name="status" class="form-control filter-form-data">
                                    <option value="">All Status</option>
                                    <option @if(request()->status == 'pending') selected @endif value="pending">Pending</option>
                                    <option @if(request()->status == 'accepted') selected @endif  value="accepted">Accepted</option>
                                    <option @if(request()->status == 'rejected') selected @endif  value="rejected">Rejected</option>
                                    <option  @if (request()->status == 'deficient') selected @endif value="deficient">Deficient</option>
                                    <option  @if (request()->status == 'duplicate') selected @endif value="duplicate">Duplicate</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2 mt-4">
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lng">Filter Record</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                   
                   <hr>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="laravel_crud">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Photographer</th>
                                        <th>Certification</th>
                                        <th>Study</th>
                                        <th>Site</th>
                                        <th>Certification Status</th>
                                        <th>Transmission#</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$getTransmissions->isEmpty())
                                    @foreach($getTransmissions as $transmission)
                                        <tr style="background: {{ $transmission->rowColor }}">
                                            <td> 
                                                {{$transmission->Photographer_First_Name}} 
                                            </td>
                                            <td> {{$transmission->Requested_certification}}</td>
                                           
                                            <td> {{$transmission->Study_Name}} </td>
                                           
                                            <td> {{$transmission->Site_Name}} </td>
                                            
                                            <td> 
                                                <span class="badge badge-dark">
                                                    Generate Certificate
                                                </span> 
                                            </td>
                                            
                                            <td>

                                            @if ($transmission->linkedTransmission != null)

                                            @foreach($transmission->linkedTransmission as $linkedTransmission)

                                                <a href="{{ route('certification-photographer.edit', encrypt($linkedTransmission['id']))}}" id="view-transmission" class="" data-id="" title="Edit Certifaction Photographer Details" data-url="" style="color: #17a2b8 !important;">
                                                    <strong>
                                                    {{ $linkedTransmission['Transmission_Number'] }}
                                                    </strong>
                                                </a>

                                                &nbsp; | &nbsp;

                                                <span class="text-dark">
                                                    <strong> {{$linkedTransmission['status']}} </strong>
                                                </span>

                                                {{--

                                                @if($linkedTransmission['status'] == 'accepted')

                                                    <span class="badge badge-success" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'pending')

                                                    <span class="badge badge-primary" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'rejected')

                                                    <span class="badge badge-danger" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'deficient')

                                                    <span class="badge badge-warning" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @elseif($linkedTransmission['status'] == 'duplicate')

                                                    <span class="badge badge-dark" onClick="changeStatus('{{ $linkedTransmission['id'] }}', '{{ $linkedTransmission['status'] }}')">{{$linkedTransmission['status']}}
                                                    </span>

                                                @endif
                                                --}}
                                                <br>
                                                <br>
                                            @endforeach

                                            @else
                                                N/A
                                            @endif
                                                <!-- |
                                                <i class="fas fa-edit"> </i> -->

                                            </td>

                                            <!--  <td>
                                                &nbsp; &nbsp;
                                                &nbsp; &nbsp;

                                                <div class="d-flex mt-md-0 ml-auto" style="margin-top: -15px !important;">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right" style="">
                                                        @if($transmission->status !== 'accepted')
                                                        <span class="dropdown-item">
                                                            <a href="javascript:void(0)" data-id="{{$transmission->Transmission_Number}}">
                                                                <i class="fas fa-question-circle" aria-hidden="true">
                                                                </i> Queries</a>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                            </td>  -->
                                            
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                           <td colspan="7" style="text-align: center">No record found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                             {{ $getTransmissions->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA -->
    </div>

    {{--
    <!-- transmission status modal  -->
    <div class="modal fade" id="transmission-status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-color: #1e3d73;">
          <div class="modal-header bg-primary" style="color: #fff">
            <h5 class="modal-title" id="exampleModalLabel">Change Transmission Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  style="color: #fff">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <form action="{{ route('update-photographer-transmission-status')}}" method="POST" class="transmission-status-form">
                @csrf
              <div class="modal-body">
                    <input type="hidden" name="hidden_transmission_id" value="">
                    <div class="form-group col-md-12">
                        <label>Change Status</label>
                        <select name="status" id="status" class="form-control" required="required">
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Reject</option>
                            <option value="deficient">Deficient</option>
                            <option value="duplicate">Duplicate</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="edit_users">Email To</label>
                        <Select class="form-control user_email" name="user_email[]" id="user_email" multiple>

                        </Select>
                    </div>

                    <div class="form-group col-md-12">
                            
                        <label for="inputState">Templates</label>
                        <select id="template" name="template" class="form-control" required>
                            <option value="">All Templates</option>
                             @foreach($getTemplates as $template)
                             <option value="{{ $template->template_id }}">{{ $template->template_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-12 comment-div" style="display: none;">
                        <label>Comments</label>
                        <textarea class="form-control summernote" name="comment" value="" rows="4"></textarea>
                        <span class="edit-error-field" style="display: none; color: red;">Please fill comment field.</span>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Change Status</button>
              </div>
            </form>
        </div>
      </div>
    </div>
    <!-- Modal ends -->
    --}}
@endsection
@section('script')

<script src="{{ asset('public/dist/vendors/summernote/summernote-bs4.js') }}"></script>

<!-- date range picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- select2 -->
<script src="{{ asset('public/dist/vendors/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/dist/js/select2.script.js') }}"></script>

<script type="text/javascript">

    // reset filter form
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change")
        // submit the filter form
        $('.filter-form').submit();
    });


</script>

@endsection



