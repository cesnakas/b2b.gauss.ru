document.addEventListener('AppLib.Ready', function (e) {
    var component_name = 'citfact_personal_order';
    var component_path = BX.message('COMPONENT_PATH_SALE_PERSONAL_ORDER');
    var orders_filter_form = $('form[name=' + component_name + '_form]');

    $('form').on('click', 'button[type=submit]', function(e) {
        $(this.form).data('name', this.name);
    });

    orders_filter_form.submit(function (e) {
        e.preventDefault();
        BX.showWait();

        var form = $(this);
        var data_send = form.serialize();
        data_send += '&'+$(this).data('name')+'='+$(this).data('name');

        if (Am.validation.validate(form)) {
            $.ajax({
                type: "POST",
                url: component_path,
                data: data_send,
                success: function (data) {
                    let regItems = /<!-- orders !-->(.*[\s\S]*)<!-- \/orders !-->/;
                    let items = data.match(regItems);

                    (typeof(items) != "undefined" && items !== null) ? $('[data-update-filter-block]').html(items[1]) : null;
                    Ac.lazy.run();

                    var regBtn = /<!-- btn !-->([\s\S]*.*)<!-- \/btn !-->/;
                    var btn = data.match(regBtn);
                    (typeof(btn) != "undefined" && btn != null) ? $('[data-ajax-id]').replaceWith(btn[1]) : $('[data-ajax-id]').remove();

                    BX.closeWait();
                },
                error: BX.closeWait(),
            });
        }else{
            BX.closeWait();
        }
        return false;
    });


    $(document).on('click', '[data-show-more]', function(){
        var $btn = $(this);
        var page = $btn.attr('data-next-page');
        var id = $btn.attr('data-show-more');
        var bx_ajax_id = $btn.attr('data-ajax-id');
        var block_id = "#block_"+bx_ajax_id;

        var data = {
            bxajaxid:bx_ajax_id
        };
        data['PAGEN_'+id] = page;

        $btn.addClass('active');


        var component_name = 'citfact_personal_order';
        var orders_filter_form = $('form[name=' + component_name + '_form]');

        var data_send = orders_filter_form.serialize();

        $.ajax({
            type: "POST",
            url: window.location.href + '?PAGEN_' + id + '=' + page,
            data: data_send,
            success: function(data) {
                $btn.removeClass('active');

                var regItems = /<!-- orders !-->([\s\S]*.*)<!-- \/orders !-->/;
                var items = data.match(regItems);

                var regBtn = /<!-- btn !-->([\s\S]*.*)<!-- \/btn !-->/;
                var btn = data.match(regBtn);

                (typeof(items) != "undefined" && items !== null) ? $('[data-update-filter-block]').append(items[1], Ac.lazy.run()) : null;
                (typeof(btn) != "undefined" && btn != null) ? $("#btn_"+bx_ajax_id).replaceWith(btn[1]) : $("#btn_"+bx_ajax_id).remove();
            }
        });
    });

}); // end document ready
