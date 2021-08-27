let subscribe = {
    run() {
        this.inputInit();
        this.formSubmitInit();
    },

    inputInit() {

    },

    formSubmitInit() {
        let self = this;

        $(document).on('submit', '[data-f-subscribe]form', function (e) {
            e.preventDefault();
            let form = $(this);

            let valid = self.validate(form);
            if (!valid) {
                form.find('.b-form__text').html('Введите корректный адрес эл.почты');
                form.find('.b-form__item').removeClass('success');
                form.find('.b-form__item').addClass('error');
                return false;
            }

            let emailRequired = form.find('[data-f-field]');

            let data = form.serialize();
            data += '&isAjaxAction=Y&action=subscribe';

            window['BX'].showWait();
            $.ajax({
                type: "POST",
                url: "/",
                data: data,
                dataType: "json",
                success: (data) => {
                    if (data !== null && "success" in data && data.success) {
                        emailRequired.prop('disabled', true);
                        form.find('button[type="submit"]').attr('disabled', true);

                        form.find('button span').html('Вы подписаны');
                        form.find('.b-form__item').removeClass('error');
                    } else {
                        form.find('.b-form__text').html('Ошибка! Повторите попытку позднее');
                        form.find('.b-form__item').addClass('error');
                    }
                    BX.closeWait();
                },
                error: function (xhr, ajaxOptions, thrownError) {

                },
                complete: function () {
                    BX.closeWait();
                }
            });
        });
    },

    validate(form) {

        let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        let emailRequired = form.find('[data-f-field]').val();

        return (reg.test(emailRequired) == false) ? false : true;
    },
};

module.exports = subscribe;
