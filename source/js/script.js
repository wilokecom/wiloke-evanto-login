(function ($) {
  $(document).ready(function () {
    console.log("dad");
    $('.form-evanto-settings').submit(function (event) {
      const $this = $(this);
      $this.addClass('loading');
      const $message = $this.find('.form-message');
      event.preventDefault();

      const data = $this.serializeArray();
      $message.removeClass('hidden positive');

      jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "wil_save_evanto_settings",
          data
        },
        success: function(response) {
          $message.html(response.data.msg);
          $message.removeClass('hidden');

          if (response.success) {
            $message.addClass('positive green');
          } else {
            $message.addClass('positive red');
          }
        }
      }).always(function(response) {
        $this.removeClass('loading');
      })
    });
  });
})(jQuery);
