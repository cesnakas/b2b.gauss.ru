$(document).ready(function () {
    var component_name = BX.message('WEB_FORM_NAME_MODAL_MARKETING');
    var component_path = BX.message('COMPONENT_PATH_MODAL_MARKETING');
    var authorize_ajax_form = $('form[name=' + component_name + ']');

    authorize_ajax_form.submit(function (e) {
        e.preventDefault();
        BX.showWait();
        var form = $(this);
        var resStr = '';
        $('span[id^="prod_name"]').each(function () {
            let $span = $(this);

            $(this).parent().parent().find('.thingtofind').text();

            let spanTxt = $span.text();

            let countItem = $(this).closest('.basket-item').find('input').val();

            resStr += spanTxt + ' - ' + countItem + '\n';
        });
        form['0']['4']['value'] = resStr;
        var data_send = form.serialize();
        if (Am.validation.validate(form)) {
            $.ajax({
                type: "POST",
                url: component_path,
                data: data_send,
                success: function (data) {
                    $(".b-modal").replaceWith(data);
                    BX.closeWait();
                },
                error: BX.closeWait(),
            });
        } else if(!form['0']['4']['value']){
            $('.title-2').addClass('red');
            BX.closeWait();
        }else{
            BX.closeWait();
        }
        return false;
    });

    $(document).on('click', '[data-delete_item]', function () {
        $(this).closest('.basket-item').remove();

        if (!$('[data-prod-item]').length){
            $('.basket-item--top').remove();
            $('.title-2 span').text('Товары не выбраны');
        }

    });

}); // end document ready