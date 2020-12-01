<script src="{{ asset('tinymce/js/tinymce/jquery.tinymce.min.js') }}"></script>
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
tinymce.init({
    selector: '#text_info_add,#text_info_add_calc,#text_info_add_cert',
    height: 250,
    forced_root_block: '',
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    entity_encoding : "raw",
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    images_upload_url: "{{ route('tinymce.image_upload') }}",
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', "{{ route('tinymce.image_upload') }}");
        xhr.onload = function () {
            var json;
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };
        formData = new FormData();
        formData.append('image', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);
    },
    /*

     */
});
</script>
