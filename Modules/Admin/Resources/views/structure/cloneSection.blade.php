<!-- phase modle -->
<div class="modal fade" role="dialog" id="clone_section">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header" style="background: cadetblue;color: white;font-weight: bold;">
                <p class="modal-title_sec"></p>
            </div>
            <!-- action="" -->
            <form action="" enctype="multipart/form-data" method="POST" id="Clone_form_section">
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            @csrf
                            <input type="hidden" id="cloning_section_id" name="cloning_section_id">
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="sec_name" dusk="sec_name_clone" id ="sec_name_clone" placeholder="Section Name" required>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Description</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="sec_description" dusk="sec_description_clone" id ="sec_description_clone" placeholder="Section Title Description" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Sort # / Position</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="sort_num" dusk="sort_num_clone" id ="sort_num_clone" placeholder="Sort Number" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Remove Question variable Suffix</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="remove_suffix" dusk="remove_suffix_clone" id ="remove_suffix_clone" placeholder="Sort Number" placeholder="Remove Question variable Suffix">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Name" class="col-sm-3 col-form-label">Add Question variable Suffix</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="add_suffix" dusk="add_suffix_clone" id ="add_suffix_clone" placeholder="Add Question variable Suffix">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger" data-dismiss="modal" id="close-cloneSection"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="button" class="btn btn-outline-primary" id="cloneSection" dusk="cloneSection"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add steps agains phases -->

@push('script')
<script type="text/javascript">
    $('body').on('click','.clone_section',function(){
        $('.modal-title_sec').html('Clone Section');
            var row = $(this).closest('tr');
            var id = row.find('td.sec_id').text();
            $('#cloning_section_id').val(id);
        $('#clone_section').modal('show');
    })
     // Save sections against Steps Save_section {{route('sections.store')}}
    $('#cloneSection').on('click',function(){
        var fromData = $("#Clone_form_section").serialize();
        var tId;
        $.ajax({
            url: "{{route('cloneSteps.cloneSection')}}",
            type: 'POST',
            data: fromData,
            dataType: 'JSON',
            success: function(response){
                Sections(response.step_id);
                $('#close-cloneSection').click();
                $('.success-msg-sec').html('');
                $('.success-msg-sec').html('Section Cloned Successfully!')
                $('.success-alert-sec').slideDown('slow');
                tId=setTimeout(function(){
                  $(".success-alert-sec").slideUp('slow');
                }, 3000);
            }
        });
    })
    //end
</script>
@endpush