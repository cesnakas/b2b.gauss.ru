'use strict';
window.inputMask.run();
if (window.FormAjax === undefined) {
  var FormAjax = {
    run: function () {
      this.init();
      this.formResetOnModalOpen();
      window.inputMask.run();
    },

    formResetOnModalOpen: function () {
      $(document).on('.modal-popup', 'mfpOpen', function () {
        var resetContainer = $('[data-form-reset]');
        resetContainer.find('form').show();
        resetContainer.find('.result_desc').hide();
      });
    },

    init: function () {
      $(document).on('submit', '[data-ajax-form]', function (e) {
        e.preventDefault();
        var form = $(this);
        var loader = form.parents(".form-box").find(".modal-loader");

        form.find('input[data-page-path]').val(window.location.hostname + window.location.pathname);

        var data_send = new FormData(this);
        var arParams = BX.message('arParams_' + form.find('input[name=paramsHash]').val());
        data_send.append('arParams', JSON.stringify(arParams));

        var result_cont = form.find('.result_cont');
        var success_cont = form.find('.success_cont');
        var errors_cont = form.find('.errors_cont');
        result_cont.html('');
        success_cont.fadeOut('');
        errors_cont.html('');
        if (window.validation.validate(form)) {
          loader.fadeIn(200);
          $.ajax({
            type: "POST",
            url: "/local/components/citfact/form.ajax/ajax.php",
            data: data_send,
            processData: false,
            contentType: false,
            beforeSend: function () {
              BX.showWait();
              success_cont.hide();
            },
            success: function (data) {
              var json = JSON.parse(data);
              result_cont.html('');
              errors_cont.html('');

              if (json.errors.length > 0) {
                for (var key in json.errors) {
                  errors_cont.append('<p class="red">' + json.errors[key] + '</p>');
                }
                errors_cont.hide().fadeIn();
              }
              else {
                if (json.result.message.length > 0) {
                  result_cont.append('<p class="">' + json.result.message + '</p>');
                }

                form.find('input[type=text], textarea, select').val('');
                if (json.result.success === 'Y') {
                  success_cont.fadeIn();
                }
              }
              loader.fadeOut(200);
            },
            complete: function () {
              BX.closeWait();
            }
          });
        } else if (form.parents('[data-modal-form]').length <= 0) {
          var pos = form.find('.error-required.active:first, .error-format.active:first').offset();
          if (pos !== undefined) {
            $('html,body').animate({scrollTop: pos.top - 70}, 500);
          }
        }
      });
    },
  };

  if (
    typeof window.frameCacheVars !== "undefined" &&
    BX && !window.isFrameDataReceived
  ) {
    //BX.addCustomEvent("onFrameDataReceived", function () {
      FormAjax.run();
    //});
  } else {
    $(function () {
      FormAjax.run();
    });
  }
}