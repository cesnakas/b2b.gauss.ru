let register = {
    run() {
        this.daData();
        this.continue();
        this.formCopy();
    },

    daData() {
        $('input[data-suggestion="inn"]').each(function () {
            let item = $(this);
            window.Am.suggestions.setInn(item);
        });
    },

    continue() {
        var btn = $('#continue');

        btn.click(function () {
            $('[data-tab-btn="2"]')[0].click();
        });
    },

    formCopy() {
        let register_ajax_form = $('form[name=regform_2]');
        let register_ajax_form_1 = $('form[name=regform_1]');

        register_ajax_form_1.find('input, textarea').on('keyup', function () {
            let name = $(this).attr('name');
            let val = $(this).val();

            register_ajax_form.find('[name="'+name+'"]').val(val);
        });

        let btnSubmitForm = $(register_ajax_form).find('[data-btn-submit]');

        btnSubmitForm.on('click', function (e) {
            e.preventDefault();

            let form = $(this).closest('form');
            let form1 = $('form[name=regform_1]');

            let formValid1 = Am.validation.validate(form1);
            let formValid2 = Am.validation.validate(form);

            if (!formValid1) {
                $('[data-tab-btn="1"]')[0].click();
                return false;
            } else if(!formValid2) {
                return false;
            } else {

                let captchaCode = form.find('[name="captcha_sid"]').val();
                let captchaWordHiddenInput = form.find('[name="captcha_word"]');

                const captchaCallback = function() {

                    const modal = $('[data-captcha-modal]');

                    const captchaWord = modal.find('input[data-captcha-word]');

                    const btnSubmitRegForm = modal.find('[data-submit-reg-form]');

                    $(btnSubmitRegForm).on('click', function () {

                        let captchaWordValue = modal.find('input[data-captcha-word]').val();

                        if (captchaWordValue.length <= 0) {
                            captchaWord.closest('[data-f-item]').addClass('error');
                        } else {
                            captchaWordHiddenInput.val(captchaWordValue);
                            register_ajax_form.submit();
                        }
                    });
                };

                Am.modals.showDialog('/local/include/modals/captcha.php?captcha_code=' + captchaCode, captchaCallback);

            }
        });
    }
};

module.exports = register;