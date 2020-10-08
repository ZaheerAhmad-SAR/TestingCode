<div class="d-flex mt-3 mt-md-0 ml-auto">
    <span class="ml-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"><i class="fas fa-cog" style="margin-top: 12px;"></i></span>
    <div class="dropdown-menu p-0 m-0 dropdown-menu-right">
        @include('queries::queries.query_popup_span',['study_id'=> (null != $study)? $study->id:''])
    </div>
</div>
