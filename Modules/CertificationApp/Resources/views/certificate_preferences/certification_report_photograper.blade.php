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
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Certification Report</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Certification Report Photograpers</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">

                     <div class="form-group col-md-12 mt-3">


                      

                    </div>

                     <hr>
                    <!-- Other Filters ends -->

                    


                    <div class="card-body">
                        <table class="table table-bordered" id="laravel_crud">
                            <thead class="table-secondary">
                            <tr>
                                <th>
                                    Site Code
                                </th>
                                <th>
                                    Site Name
                                </th>
                                <th>
                                   Photograper Email Id 
                                </th>
                                                               
                            </tr>
                            </thead>
                            <tbody>
             
                                @foreach($result_old as $site_array)
                                 @php



                            multidimensional_search($result_new, array('email'=>$site_array->email_address, 'site_code'=>$site_array->site_id));

                                 @endphp
                                    <tr>
                                        <td>
                                           {{ $site_array->site_id}}
                                        </td>

                                        <td>
                                            
                                        </td>

                                        <td>
                                         {{ $site_array->email_address}}
                                        </td>


                                       
                                    </tr>
                            
                                @endforeach
                               
                            </tbody>
                        </table>
                         

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
