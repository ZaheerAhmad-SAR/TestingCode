(function ($) {
    "use strict";
    if ($('body').hasClass('dark')) {
       $('head').append('<link rel="stylesheet" href="dist/vendors/summernote/plex/summernote-lite-plex.css" type="text/css" />');
    }
    if ($('body').hasClass('dark-alt')) {
      $('head').append('<link rel="stylesheet" href="dist/vendors/summernote/plex/summernote-lite-plex.css" type="text/css" />');
  }



  $('.summernote').summernote({
        height: 50,
      toolbar: [
          ['font', ['bold', 'underline', 'clear']],
          // ['fontname', ['fontname']],
          // ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          // ['table', ['table']],
          ['insert', ['link', 'picture', 'video']]
      ],
      maximumImageFileSize: 1048576, // bytes, 1048576 bytes = 1mb
      callbacks:{
          onImageUploadError: function(msg){
              console.log(msg + ' (1 MB)');
          }
      }
      });
$('.summernote-inline').summernote({
        height: 200,
        airMode:!0
      });


})(jQuery);
