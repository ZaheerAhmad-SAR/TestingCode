(function ($) {
    "use strict";
   $('.multieSelectDropDown').each(function () {
    $(this).select2({
      theme: 'bootstrap4',
      width: 'style',
      // placeholder: $(this).attr('placeholder'), original as per library
      placeholder: '',
      allowClear: Boolean($(this).data('allow-clear')),
    });
  });

})(jQuery);
