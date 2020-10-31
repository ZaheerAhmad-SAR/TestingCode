@extends ('layouts.home')

@section('title')
    <title> Sites | {{ config('app.name', 'Laravel') }}</title>
@stop


@section('content')

    <div class="container-fluid site-width">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">App Query</h4></div>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Sites</li>
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
                        <div class="table-responsive">
                            <table id="example" class="display table dataTable table-striped table-bordered" >
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Subject</th>
                                    <th>Origin Name</th>
                                    <th>Created By</th>
                                    <th>Creation Date</th>
                                    <th>Status</th>
                                    <th>History</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $count= 1; @endphp
                                @foreach($queries as $query)
                                    @php
                                        $moduleId = \Modules\Admin\Entities\Study::where('id','=',$query->module_id)->first();
                                        $studyShortName = $moduleId->study_short_name;
                                    @endphp
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$query->query_subject}}</td>
                                        <td><a target="_blank" href="{{$query->query_url}}">{{$studyShortName}}</a></td>
                                        <td>{{ucfirst(auth()->user()->name)}}</td>
                                        <td>{{date_format($query->created_at,'M-d-Y')}}</td>
                                        <td>{{ucfirst($query->query_status)}}</td>
                                        <td class="detailConversation" style="cursor: pointer;" data-id="{{$query->id}}"><i class="fa fa-eye" aria-hidden="true"></i></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END: Card DATA-->
    </div>
     <!-- Query Details Conversation Modal Start -->
    <div class="modal fade" tabindex="-1" role="dialog" id="reply-modal" aria-labelledby="exampleModalQueries" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 1000px;" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <p class="modal-title">Query Details</p>
                    <span class="queryCurrentStatus text-center"></span>
                </div>
                <div class="modal-body">
                    <form id="replyForm" name="replyForm">
                        <div class="replyInput"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Query Details Conversation Modal End -->
@endsection
@section('script')
    <script type="text/javascript">

        $('body').on('click', '.detailConversation', function () {
            var query_id     = $(this).attr('data-id');
            console.log(query_id);
            $('#reply-modal').modal('show');
            showComments(query_id);
            $('#all-queries-modal').modal('hide');
        });

        function showComments(query_id) {
            $.ajax({
                url:"{{route('queries.showCommentsById')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'POST',
                    'query_id'      :query_id,
                },
                success: function(response)
                {
                    $('.replyInput').html('');
                    $('.replyInput').html(response);
                    var query_status = $( "#query_status option:selected" ).text();
                    $('.queryCurrentStatus').text('Status: '+query_status);
                    $('.replyClick').css('display','');
                }
            });
        }
    </script>
@endsection




