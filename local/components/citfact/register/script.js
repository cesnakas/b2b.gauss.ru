document.addEventListener('AppLib.Ready', function (e) {
  var component_path = BX.message('COMPONENT_PATH_CITFACT_REGISTER_AJAX');
  var arParams = BX.message('arParams');

  $('[data-form-register-ajax]').submit(function () {
    var form = $(this);
    var data_send = form.serialize();
    var result_cont = $('.result_cont');
    var errors_cont = form.find($('.errors_cont'));
    if (window.validation.validate(form)) {
      $.ajax({
        type: "POST",
        url: component_path + "/ajax.php",
        data: data_send,
        beforeSend: function () {
          BX.showWait();
        },
        success: function (data) {
          var json = JSON.parse(data);
          result_cont.html('');
          errors_cont.html('');
          if (json.errors.length > 0) {
            for (var key in json.errors) {
              errors_cont.append('<p class="red">' + json.errors[key] + '</p>');
            }
            errors_cont.append('<br>');
            errors_cont.hide().fadeIn();
          }
          else {
            for (var key in json.result) {
              result_cont.append('<p class="">' + json.result[key] + '</p>');
            }
            form[0].reset();
            form.hide().parent().find('.result_desc').fadeIn();
            setTimeout(function () {
              location = "/account/";
            }, 3000);
            //window.location.reload();
            //result_cont.hide().fadeIn();
          }
        },
        complete: function () {
          BX.closeWait();
        }
      });
    }
    else {
      // прокручиваем к первой ошибке
      var pos = form.find('input.error:first').offset();
      $('html,body').animate({scrollTop: pos.top - 70}, 500);
    }
    return false;
  });

}); // end document ready