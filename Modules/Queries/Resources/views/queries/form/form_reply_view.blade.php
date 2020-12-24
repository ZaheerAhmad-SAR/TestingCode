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
<div class="form-group row formQueryTextarea" style="display: none;">
    <label for="Name" class="col-sm-2 col-form-label">Enter your Query</label>
    <div class="col-sm-10">
        <textarea class="form-control" name="formReply" id="formReply"></textarea>
    </div>
</div>
<div class="form-group row formQueryFile" style="display: none;" >
    <label for="Attachment" class="col-sm-2 col-form-label">Attachment:</label>
    <div class="col-sm-10">
        <input class="form-control" type="file" name="formFileInput"  id="formFileInput">
    </div>
</div>
 <div class="form-group row formQueryStatus" style="display:none;">
     <label for="Status" class="col-sm-2 col-form-label">Status</label>
     <div class="col-sm-10">
         <select class="form-control" id="formStatusInput" name="formStatusInput">
             <option value="open" {{$query->query_status== 'open'? 'selected="selected"' : ''}}>open</option>
             <option value="confirmed" {{$query->query_status== 'confirmed'? 'selected="selected"' : ''}}>Confirmed</option>
             <option value="unconfirmed" {{$query->query_status== 'unconfirmed'? 'selected="selected"' : ''}}>UnConfirmed</option>
             <option value="in progress" {{$query->query_status== 'in progress'? 'selected="selected"' : ''}}>Inprogress</option>
             <option value="close" {{$query->query_status== 'close'? 'selected="selected"' : ''}}>close</option>
             </select>
         </div>
 </div>



<div class="malwareData">

    <input type="hidden" name="studyIdInput" id="studyIdInput" value="{{ $query->study_id }}">
    <input type="hidden" name="subjectIdInput" id="subjectIdInput" value="{{ $query->subject_id }}">
    <input type="hidden" name="phaseStepsIdInput" id="phaseStepsIdInput" value="{{ $query->phase_steps_id }}">
    <input type="hidden" name="sectionIdInput" id="sectionIdInput" value="{{ $query->section_id }}">
    <input type="hidden" name="questionIdInput" id="questionIdInput" value="{{ $query->question_id }}">
    <input type="hidden" name="fieldIdInput" id="fieldIdInput" value="{{ $query->field_id }}">
    <input type="hidden" name="formTypeIdInput" id="formTypeIdInput" value="{{ $query->form_type_id }}">
    <input type="hidden" name="modilityIdInput" id="modilityIdInput" value="{{ $query->modility_id }}">
    <input type="hidden" name="moduleNameInput" id="moduleNameInput" value="{{ $query->module_name }}">
    <input type="hidden" name="studyStructuresIdInput" id="studyStructuresIdInput" value="{{ $query->study_structures_id }}">

    <input type="hidden" name="queryTypeInput" id="queryTypeInput" value="{{ $query->query_type }}">
    <input type="hidden" name="queryIdInput" id="queryIdInput" value="{{ $query->id }}">
    <input type="hidden" name="queryUrlInput" id="queryUrlInput" value="{{ $query->query_url }}">
    <input type="hidden" name="subjectFormInput" id="subjectFormInput" value="{{ $query->query_subject }}">
    <input type="hidden" name="queryLeveFormInput" id="queryLeveFormInput" value="{{ $query->query_level }}">
</div>

