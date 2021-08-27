import showMore from './showMore';

let checkboxAgree = {
    run() {
        this.checkbox();
    },
    checkbox() {
        $('[data-checkbox-agree]').each(function () {
            const $checkbox  = $(this);
            const $form  = $('[data-checkbox-agree-form]');
            const $btn  = $('[data-show-more-btn]');

            $checkbox.change(function () {
                if($checkbox.prop("checked")) {
                    $btn.slideDown();
                    $form.slideDown();
                    showMore.close();
                }
                else {
                    $btn.slideUp();
                    $form.slideUp();
                    showMore.open();
                }
            });

            $(document).on('click', '[data-show-more-btn]', function () {
                if ($(this).is('.active')) {
                    showMore.close();
                }
                else {
                    showMore.open();
                }
            })
        })
    }
};

module.exports = checkboxAgree;