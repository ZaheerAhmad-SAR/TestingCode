<!-- phase modle -->
<div class="modal fade" role="dialog" id="clone_step">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title"></p>
            </div>
            <!-- action="{{route('cloneSteps.cloneSteps')}}" -->
            <form action="{{route('cloneSteps.cloneSteps')}}" enctype="multipart/form-data" method="POST" id="Clone_step_form">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            @csrf
                            <input type="hidden" name="step_id" id="step_id_for_clone">
                            @foreach($phases as $key => $phase)
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">{{$phase->name}}</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="phase[]" value="{{$phase->id}}" style="margin-top: 10px;">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="#"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary" id="#"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add steps agains phases -->
<!-- phase modle -->
<div class="modal fade" role="dialog" id="clone_phase">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header ">
                <p class="modal-title"></p>
            </div>
            <!-- action="{{route('cloneSteps.clonePhase')}}" -->
            <form action="{{route('cloneSteps.clonePhase')}}" enctype="multipart/form-data" method="POST" id="Clone_phase_form">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            @csrf
                            <input type="hidden" name="phase_id" id="phase_id_for_clone">
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="phase_name_clone" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="#"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary" id="#"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add steps agains phases -->
@push('script')
<script type="text/javascript">
	$('body').on('click','.cloneStep',function(){
		$('.modal-title').html('Clone step / form to other phases / visits');
		$('#Clone_step_form').trigger('reset');
		var row = $(this).closest('li.mail-item')
			step_id = row.find('input.step_id').val();
		$('#step_id_for_clone').val(step_id);
		$('#clone_step').modal('show');
	})
    $('body').on('click','.clonePhase',function(){
        $('.modal-title').html('Clone Phase with Same Name / Different Name');
        $('#Clone_phase_form').trigger('reset');
        var row = $(this).closest('li.nav-item')
            id = row.find('input.phase_id').val()
            name = row.find('input.phase_name').val();
        $('#phase_id_for_clone').val(id);
        $('#phase_name_clone').val(name);
        $('#clone_phase').modal('show');
    })
</script>
@endpush