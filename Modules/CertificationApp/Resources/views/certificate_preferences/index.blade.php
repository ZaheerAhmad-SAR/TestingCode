@extends ('layouts.home')
@section('title')
    <title> Studies | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('styles')
    <!-- <link rel="stylesheet" href="{{ asset('dist/vendors/tablesaw/tablesaw.css') }}"> -->
    
@stop

@section('content')
    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Studies List</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Studies List</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">

                     <div class="form-group col-md-12 mt-3">        


                        @if (!$getStudies->isEmpty())
                        <span style="float: right; margin-top: 3px;" class="badge badge-pill badge-primary">
                            {{ $getStudies->count().' out of '.$getStudies->total() }}
                        </span>
                        @endif

                    </div>

                     <hr>
                    <!-- Other Filters ends -->

                    <form action="{{route('certification-preferences.index')}}" method="get" class="filter-form">
                        <div class="form-row" style="padding: 10px;">

                            <div class="form-group col-md-2">
                                <label for="study_code">Study Code</label>
                                <input type="text" name="study_code" id="study_code" class="form-control filter-form-data" value="{{ request()->study_code }}" placeholder="Study Code">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="short_name">Short Name</label>
                                <input type="text" name="short_name" id="short_name" class="form-control filter-form-data" value="{{ request()->short_name }}" placeholder="Short Name">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="study_title">Study Title</label>
                                <input type="text" name="study_title" id="study_title" class="form-control filter-form-data" value="{{ request()->study_title }}" placeholder="Study Title">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="study_sponsor">Study Sponsor</label>
                                <input type="text" name="study_sponsor" id="study_sponsor" class="form-control filter-form-data" value="{{ request()->study_sponsor }}" placeholder="Study Sponsor">
                            </div>


                            <div class="form-group col-md-2 mt-4">
                                <button type="button" class="btn btn-primary reset-filter">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lng">Filter Record</button>
                            </div>

                        </div>
                        <!-- row ends -->
                    </form>
                
                   
                    <div class="card-body">
                        <table class="table table-bordered" id="laravel_crud">
                            <thead class="table-secondary">
                            <tr>
                                <th>
                                    Study Code
                                </th>

                                <th>
                                    Short Name
                                </th>

                                <th>
                                    Study Title
                                </th>

                                <th>
                                    Sponsor
                                </th>
                                
                                <th>
                                    Action
                                </th>

                            </tr>
                            </thead>
                            <tbody>
                                @if (!$getStudies->isEmpty())
                                @foreach($getStudies as $study)
                                    <tr>
                                        <td>
                                           {{ $study->study_code}}
                                        </td>

                                        <td>
                                            {{ $study->study_short_name}}
                                        </td>

                                        <td>{{$study->study_title}}</td>

                                        <td>
                                          {{ $study->study_sponsor }}
                                        </td>

                                        <td>

                                                &nbsp; &nbsp;
                                                &nbsp; &nbsp;

                                                <div class="d-flex mt-md-0 ml-auto" style="margin-top: -15px !important;">
                                                <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog"></i></span>
                                                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right" style="">
                                                        
                                                        <span class="dropdown-item">
                                                            <a href="{{ route ('preferences.assign-modality', encrypt($study->id)) }}" data-id="">
                                                                <i class="fas fa-question-circle" aria-hidden="true">
                                                                </i> Assign Modality</a>
                                                        </span>

                                                        <span class="dropdown-item">
                                                            <a href="{{ route ('preferences.assign-device', encrypt($study->id)) }}" data-id="">
                                                                <i class="fas fa-question-circle" aria-hidden="true">
                                                                </i> Assign Device</a>
                                                        </span>
                                                       

                                                         <span class="dropdown-item">
                                                            <a href="{{ route('preferences.study-setup', encrypt($study->id))}}" data-id="">
                                                                <i class="fas fa-question-circle" aria-hidden="true">
                                                                </i> Study Setup</a>
                                                        </span>
                                                    </div>
                                                </div>
                                                 <!-- gear dropdown -->
                                            </td>
                                    </tr>
                                   
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" style="text-align: center">No record found.</td>
                                </tr>
                               
                                @endif
                            </tbody>
                        </table>
                            {{ $getStudies->links() }}
                        
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
@endsection

@section('script')

<script type="text/javascript">
    
    $('.reset-filter').click(function(){
        // reset values
        $('.filter-form').trigger("reset");
        $('.filter-form-data').val("").trigger("change");
        // submit the filter form
        window.location.reload();
    });
</script>

@endsection
