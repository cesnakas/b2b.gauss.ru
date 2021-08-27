import validation from './citfact.lib/validation.js/validation.js';
let onused = false
let order = {
    run() {
        this.formSubmitAjax();
    },

    formSubmitAjax(url) {
        let component_name = 'citfact_personal_order';
        let orders_filter_form = $('form[name=' + component_name + '_form]');

        let data_send = orders_filter_form.serialize();
        let bx_ajax_id = $('.btn.btn--loading').attr('data-ajax-id');

        if (validation.validate(orders_filter_form)) {
            $.ajax({
                type: "POST",
                url: url,
                data: data_send,
                success: function (data) {
                    let regItems = /<!-- orders !-->(.*[\s\S]*)<!-- \/orders !-->/;
                    let items = data.match(regItems);

                    if ((typeof(items) != "undefined" && items !== null)) {
                      $("[data-update-filter-block]").html(items[1]);
                    }

                    let regBtn = /<!-- btn !-->([\s\S]*.*)<!-- \/btn !-->/;
                    let btn = data.match(regBtn);
                    
                    if (typeof(btn) != "undefined" && btn != null) {
                      $("#btn_"+bx_ajax_id).replaceWith(btn[1]);
                    } else {
                      $("#btn_"+bx_ajax_id).remove()
                    }
                    
                    BX.closeWait();
                },
                error: BX.closeWait(),
            });
        } else {
            BX.closeWait();
        }
    },

    initFastOrderForm() {
        $('[data-vendor-code]').on('click', (event) => {
            let vendorCode = $(event.currentTarget).data('vendor-code');
            let inputId = $(event.currentTarget).data('input-id');
            let inputField = $('#' + inputId);
            if ('' !== vendorCode && undefined !== inputField) {
                if ( vendorCode.toString().includes('<b>') ) {
                    inputField.val(vendorCode.replace(/<\/?b>/g, ''));}
                else
                {
                    inputField.val(vendorCode);
                }
                ///TODO norm
                $('.title-search-result').each((key, element) => {
                    $(element).html('');
                    $(element).attr('style', '');
                });
            }
        })
        if (!onused){
            $('[data-input-count-btn]').on('click', (event) => {
                const input = event.currentTarget.closest('[data-input-count]').querySelector('[data-input-count-input]');
                const operation = $(event.currentTarget).data('input-count-btn');
                if ( operation === 'plus'){
                    input.value++;
                }
                else  {
                    if (input.value > 1 ){
                        input.value--;
                    }
                }
            })
            onused = true;
        }
    }
};


module.exports = order;
