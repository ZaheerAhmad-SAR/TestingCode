@extends ('layouts.home')
@section('title')
    <title> Studies | {{ config('app.name', 'Laravel') }}</title>
@stop
@section('styles')
    <link rel="stylesheet" href="{{ asset('dist/vendors/tablesaw/tablesaw.css') }}">
    
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
                   
                    <div class="card-body">
                        <table class="tablesaw table-bordered">
                            <thead>
                            <tr>
                                <th scope="col" data-tablesaw-sortable-default-col data-tablesaw-priority="3">
                                    Short Name : <strong>Study Title</strong>
                                    <br>
                                    <br>Sponsor
                                </th>

                                <th scope="col" data-tablesaw-priority="2" class="tablesaw-stack-block">Progress bar</th>

                                <th scope="col" data-tablesaw-priority="1">Status</th>

                                <th scope="col" data-tablesaw-priority="1">Study Admin</th>
                                
                                <th scope="col" data-tablesaw-priority="4">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                                @if (!$studies->isEmpty())
                                @foreach($studies as $study)
                                    <tr>
                                        <td>
                                            <a class="" href="{{ route('studies.show', $study->id) }}">
                                                {{ucfirst($study->study_short_name)}} : <strong>{{ucfirst($study->study_title)}}</strong>
                                            </a>
                                            <br><br>
                                            <p style="font-size: 14px; font-style: oblique">
                                                Sponsor: <strong>{{ucfirst($study->study_sponsor)}}</strong>
                                            </p>
                                        </td>

                                        <td class="tablesaw-stack-block">
                                            <p></p>
                                            {!! \Modules\Admin\Entities\Study::calculateFormPercentage($study->id) !!}
                                        </td>

                                        <td>{{$study->study_status}}</td>

                                        <td>
                                          
                                            {{ \Modules\Admin\Entities\Study::getstudyAdminsName($study->id) }}
                                           
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
                                                            <a href="javascript:void(0)" data-id="">
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
                            {{ $studies->links() }}
                        
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
@endsection

@section('script')

@endsection
