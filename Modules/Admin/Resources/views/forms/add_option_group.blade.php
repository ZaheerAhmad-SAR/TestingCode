<div class="modal fade" tabindex="-1" role="dialog" id="addOptionGroups">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content" style="background-color: #F6F6F7;">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header" style="background: #1E3D73;color: white;">
                    <p class="modal-title">Add Option Group</p>
                </div>
                <form name="OptionsGroupForm" id="OptionsGroupForm">
                    <div class="modal-body">
                        <div id="exTab1">
                            <div class="tab-content clearfix">
                                <div class="form-group row">
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" id="option_group_name"
                                            name="option_group_name" value="" placeholder="Enter option group name"
                                            style="background: white;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" id="option_group_description"
                                            name="option_group_description" value="" placeholder="Option group description"
                                            style="background: white;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">Option Layout </div>
                                    <div class="form-group col-md-9">
                                        <input type="radio" name="option_layout" value="vertical"> Vertical
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="option_layout" value="horizontal" checked> Horizontal
                                    </div>
                                </div>
                                <div class="appendDataOptions"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary addOptions pull-right"><i
                                    class="fa fa-plus"></i> Add option</button>
                            <button id="optiongroup-close" class="btn btn-outline-danger" data-dismiss="modal"><i
                                    class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                            <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('script')
<script type="text/javascript">
        // Add New Option Group
        function addOptionsGroup() {
            $("#OptionsGroupForm").submit(function(e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $.ajax({
                    data: $('#OptionsGroupForm').serialize(),
                    url: "{{ route('optionsGroup.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(results) {
                        fetch_options();
                        $('#OptionsGroupForm').trigger("reset");
                        $("#optiongroup-close").click();
                    },
                    error: function(results) {
                        console.log('Error:', results);
                    }
                });
            });
        }
        addOptionsGroup();
        // append all options group
        function fetch_options() {
            $('#option_group_id').html('');
            var options = '<option value="">None</option>';
            $.ajax({
                url: "{{ route('getall_options') }}",
                type: "post",
                dataType: 'json',
                success: function(res) {
                    $.each(res['data'], function(k, v) {
                        options += '<option value="' + v.id + '">' + v.option_group_name + '</option>'
                    })
                    $('#option_group_id').append(options);
                }
            });
        }
</script>

@endpush