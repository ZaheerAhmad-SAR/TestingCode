<!-- Modal To add Option Groups -->
<div class="modal fade" tabindex="-1" role="dialog" id="addAnnotation" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" style="background-color: #F6F6F7;">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header" style="background: #1E3D73;color: white;">
                <p class="modal-title">Add New Annotation</p>
            </div>
            <form action="" id="Annotation_form" method="post">
                @csrf
                <div class="modal-body">
                    <div id="exTab1">
                        <div class="tab-content clearfix">
                            <div class="form-group row">
                                <div class="col-md-3">Annotation
                                    <sup>*</sup></div>
                                <div class="form-group col-md-9">
                                    <input type="hidden" name="annotation_id" id="annotation_id">
                                    <input type="text" class="form-control" id="annotation_name" name="annotation_name" value="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="annotation-close" class="btn btn-outline-danger" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End -->

@push('script')
    <script type="text/javascript">
        {{-- add new annotation during run time --}}
        $('.add_new_annotation').on('click',function(){
            $('#Annotation_form').trigger('reset');
            $('.modal-title').html('Add New Annotation');
            $('#addAnnotation').modal('show');
        })
        //
        $('body').on('click', '.remove_anno', function() {
            var row = $(this).closest('div.anno_values_row');
            row.remove();
        })
        $('body').on('click', '.fetch_annotation', function() {
            var study_id = '{{ Session('current_study') }}';
            var row = $(this).closest('div.anno_values_row');
            var anno_class = row.find('select.terminology_value');
            get_all_annotations(study_id, anno_class);
        })
         // Add New Option Group
        function addAnnotation() {
            $("#Annotation_form").submit(function(e) {
                var study_id = '{{ Session('current_study') }}';
                var anno_class = $('select.terminology_value');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                $.ajax({
                    data: $('#Annotation_form').serialize(),
                    url: "{{ route('annotation.addAnnotation') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(results) {
                        get_all_annotations(study_id, anno_class);
                        $('#Annotation_form').trigger("reset");
                        $("#annotation-close").click();
                    },
                    error: function(results) {
                        console.log('Error:', results);
                    }
                });
            });
        }
        addAnnotation();
        // get all annotations
        function get_all_annotations(id, div_class) {
            div_class.html('');
            var options = '<option value="">---Select Annotation---</option>';
            $.ajax({
                url: 'annotation/get_allAnnotations/' + id,
                type: 'post',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "_method": 'GET',
                    'id': id
                },
                success: function(response) {
                    $.each(response['data'], function(k, v) {
                        options += '<option value="' + v.id + '" >' + v.label + '</option>';
                    });
                    div_class.append(options);
                }
            });
        }
    </script>
@endpush