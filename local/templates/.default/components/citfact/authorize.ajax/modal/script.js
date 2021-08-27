$(document).ready(function () {
  var component_name = 'citfact_authorize_ajax';
  var component_path = BX.message('COMPONENT_PATH_CITFACT_AUTHORIZE_AJAX');
  var authorize_ajax_form = $('form[name=' + component_name + '_form]');

  authorize_ajax_form.submit(function () {
    var form = $(this);
    var data_send = form.serialize();

    var success_auth = form.find('.success_auth');
    var errors_auth = form.find('.errors_auth');
    success_auth.html('');
    errors_auth.html('');
    if (Am.validation.validate(form)) {
      $.ajax({
        type: "POST",
        url: component_path + "/ajax.php?login=yes",
        data: data_send,
        success: function (data) {
          var json = JSON.parse(data);
          success_auth.html('');
          errors_auth.html('');
          if (json.errors.length > 0) {
            var arr = json.errors;
            for (var key = 0; key < arr.length; key++) {
              errors_auth.append('<p>' + arr[key] + '</p>');
            }
            errors_auth.hide().fadeIn();
          } else {
            var arr = json.result;
            for (var key = 0; key < arr.length; key++) {
              success_auth.append('<p>' + arr[key] + '</p>');
            }

            form[0].reset();
            window.location.reload();
          }
        }
      });
    } else {
      BX.closeWait();
    }

    return false;
  });


  $(document).on('input', '[data-login]', function () {
    var val = $(this).val();
    $('[data-login-clear]').val(val.replace(/[^\d]+/g, '').substring(1));
  });
});
