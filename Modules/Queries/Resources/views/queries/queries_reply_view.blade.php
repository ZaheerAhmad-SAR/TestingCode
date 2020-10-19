<link rel="stylesheet" href="{{ asset("dist/vendors/summernote/summernote-bs4.css") }}">


    @php $querySubmitedBy = App\User::find($query->queried_remarked_by_id);@endphp
    <div class="form-group row">
        <div class="col-sm-10">
        <div class="media">
            <span> <img class="mr-3" style="width: 70px; height: 70px; border-radius: 50%;"  src="{{url($querySubmitedBy->profile_image)}}"/></span>
            <div class="media-body">
                <h5 class="mt-0">{{ucfirst($querySubmitedBy->name)}}  <i class="fas fa-circle" style="color: lightgreen; font-size:10px;"></i></h5>
                <q>{{strip_tags($query->messages)}}</q>
                @foreach($answers as $answer)
                    @php $querySubmitedBy = App\User::find($answer->queried_remarked_by_id);@endphp
                    <div class="media mt-3">
                        <span> <img class="mr-3" style="width: 70px; height: 70px; border-radius: 50%;"  src="{{url($querySubmitedBy->profile_image)}}"/></span>

                    <div class="media-body">
                        <h5 class="mt-0">{{ucfirst($querySubmitedBy->name)}}  <i class="fas fa-circle" style="color: lightgreen; font-size:10px;"></i></h5>
                        <q>{{strip_tags($answer->messages)}}</q>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        </div>
        </div>


{{--    <div class="form-group row">--}}
{{--        <label for="Name" class="col-sm-2 col-form-label">Status</label>--}}
{{--        <div class="col-sm-10">--}}
{{--            <select class="form-control" id="query_status" name="query_status">--}}
{{--                <option value="open">open</option>--}}
{{--                <option value="close">close</option>--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="malwareData">
        <input type="hidden" name="module_id" id="module_id" value="{{$query->module_id}}">
        <input type="hidden" name="query_type" id="query_type" value="{{$query->query_type}}">
        <input type="hidden" name="query_id" id="query_id" value="{{$query->id}}">
        <input type="hidden" name="query_url" id="query_url" value="{{$query->query_url}}">
        <input type="hidden" name="query_subject" id="query_subject" value="{{$query->query_subject}}">
    </div>

    <script src="{{ asset("dist/vendors/summernote/summernote-bs4.js") }}"></script>
    <script src="{{ asset("dist/js/summernote.script.js") }}"></script>

