@php $querySubmitedBy = App\User::find($query->queried_remarked_by_id);@endphp

<div class="m-2">
    {!! Modules\Queries\Entities\Query::buildHtmlForQuerySubmitter($querySubmitedBy, $query) !!}

    @foreach ($answers as $answer)
        @php
        $answerSubmitedBy = App\User::find($answer->queried_remarked_by_id);
        @endphp
        @if($query->queried_remarked_by_id == $answer->queried_remarked_by_id)
            {!! Modules\Queries\Entities\Query::buildHtmlForQuerySubmitter($answerSubmitedBy, $answer) !!}
        @else
            {!! Modules\Queries\Entities\Query::buildHtmlForQueryAnswer($answerSubmitedBy, $answer) !!}
        @endif

    @endforeach

</div>
<div class="form-group row commentsInput" style="display: none;">
    <label for="Name" class="col-sm-2 col-form-label">Enter your Query</label>
    <div class="col-sm-10">
        <textarea class="form-control" name="reply" id="reply"></textarea>
    </div>
</div>
<div class="form-group row queryAttachments" style="display: none;" >
    <label for="Attachment" class="col-sm-2 col-form-label">Attachment:</label>
    <div class="col-sm-10">
        <input class="form-control" type="file" name="question_file"  id="question_file">
    </div>
</div>
 <div class="form-group row queryStatus" style="display:none;">
     <label for="Status" class="col-sm-2 col-form-label">Status</label>
     <div class="col-sm-10">
         <select class="form-control" id="query_status" name="query_status">
             <option value="open" {{$query->query_status== 'open'? 'selected="selected"' : ''}}>open</option>
             <option value="confirmed" {{$query->query_status== 'confirmed'? 'selected="selected"' : ''}}>Confirmed</option>
             <option value="unconfirmed" {{$query->query_status== 'unconfirmed'? 'selected="selected"' : ''}}>UnConfirmed</option>
             <option value="in progress" {{$query->query_status== 'in progress'? 'selected="selected"' : ''}}>Inprogress</option>
             <option value="close" {{$query->query_status== 'close'? 'selected="selected"' : ''}}>close</option>
             </select>
         </div>
 </div>



<div class="malwareData">

    <input type="hidden" name="study_id" id="study_id" value="{{ $query->study_id }}">
    <input type="hidden" name="subject_id" id="subject_id" value="{{ $query->subject_id }}">
    <input type="hidden" name="phase_steps_id" id="phase_steps_id" value="{{ $query->phase_steps_id }}">
    <input type="hidden" name="section_id" id="section_id" value="{{ $query->section_id }}">
    <input type="hidden" name="question_id" id="question_id" value="{{ $query->question_id }}">
    <input type="hidden" name="field_id" id="field_id" value="{{ $query->field_id }}">
    <input type="hidden" name="form_type_id" id="form_type_id" value="{{ $query->form_type_id }}">
    <input type="hidden" name="modility_id" id="modility_id" value="{{ $query->modility_id }}">
    <input type="hidden" name="module_name" id="module_name" value="{{ $query->module_name }}">
    <input type="hidden" name="study_structures_id" id="study_structures_id" value="{{ $query->study_structures_id }}">

    <input type="hidden" name="query_type" id="query_type" value="{{ $query->query_type }}">
    <input type="hidden" name="query_id" id="query_id" value="{{ $query->id }}">
    <input type="hidden" name="query_url" id="query_url" value="{{ $query->query_url }}">
    <input type="hidden" name="subject_question" id="subject_question" value="{{ $query->query_subject }}">
    <input type="hidden" name="query_level_question" id="query_level_question" value="{{ $query->query_level }}">
</div>

{{--<script src="{{ asset('dist/vendors/summernote/summernote-bs4.js') }}"></script>--}}
{{--<script src="{{ asset('dist/js/summernote.script.js') }}"></script>--}}
