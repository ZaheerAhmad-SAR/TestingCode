(function ($) {
    "use strict";
    if ($('body').hasClass('dark')) {
       $('head').append('<link rel="stylesheet" href="dist/vendors/summernote/plex/summernote-lite-plex.css" type="text/css" />');
    }
    if ($('body').hasClass('dark-alt')) {
      $('head').append('<link rel="stylesheet" href="dist/vendors/summernote/plex/summernote-lite-plex.css" type="text/css" />');
  }



  $('.summernote').summernote({
        height: 100,
      toolbar: [
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
      ]

      });
$('.summernote-inline').summernote({
        height: 200,
        airMode:!0,
    toolbar: [
        ['font', ['bold', 'underline', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
    ]
      });

})(jQuery);
