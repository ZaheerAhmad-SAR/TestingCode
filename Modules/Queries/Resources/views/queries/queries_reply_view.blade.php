<link rel="stylesheet" href="{{ asset("dist/vendors/summernote/summernote-bs4.css") }}">
@foreach($records as $record)
    <div class="form-group row">
        @php $querySubmitedBy = App\User::find($record->queried_remarked_by_id);@endphp

                        <div class="col-sm-10">
                            <div class="media">
                                <span>
                                    <img style="width: 100px; height: 100px; border-radius: 50%;"  src="{{url($querySubmitedBy->profile_image)}}"/>
                                </span>
                                <span style="padding: 30px;">
                                    {{$querySubmitedBy->name}} <i class="fas fa-circle"></i> <br>
                                    {{strip_tags($record->messages)}}
                                </span>
                            </div>
                        </div>

            </div>

    <div class="form-group row ">
        <label for="Name" class="col-sm-2 col-form-label">Status</label>
        <div class="col-sm-10">
            <select class="form-control" id="sel1">
                <option value="open" {{$record->query_status=='open'? 'selected="selected"': ''}}>open</option>
                <option value="close" {{$record->query_status=='close'? 'selected="selected"': ''}}>close</option>
            </select>
        </div>
    </div>
@endforeach

    <script src="{{ asset("dist/vendors/summernote/summernote-bs4.js") }}"></script>
    <script src="{{ asset("dist/js/summernote.script.js") }}"></script>



