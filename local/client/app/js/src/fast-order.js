'use strict';

let FastOrder = {
    run() {
        this.clickByArticleInit();
        this.clickByArticleMoreInit();
        // this.clickByDuplicate();
    },

    clickByDuplicate() {
        $('body').on('click', '#get_more_fields', function () {
            var i = this;
            var elem = '<div class="fast-order__row fast-order__item">'
                    + '<div class="fast-order__cell fast-order__input-wrap">'
                + '<input type="text" class="fast-order__input input-sm" name="products[productCode][]">'
                + '</div>'
                + '<div class="fast-order__cell fast-order__count">'+
                `<div class="b-count" data-input-count>
                    <button type="button" data-input-count-btn="minus" class="b-count__btn b-count__btn--minus"></button>
                    <input class="b-count__input"
                           type="text"
                           name="products[quantity][]" min="1" pattern="[0-9]+"
                           value="1"
                           data-input-count-input data-product-quantity="1">
                    <button type="button" data-input-count-btn="plus" class="b-count__btn b-count__btn--plus"></button>
                </div>`+
                + '</div>'
                + '</div>'
                + '</div>';
            
            
            $(".fast-order__body").append(elem);
            $("#get_more_fields").hide();

            var container = new Container();
            var cont2 = container.getService("TemplateContainer");
            cont2.services.InputMask.run();
            $('body').trigger('onRefreshInput');
        });
    },

    clickByArticleMoreInit() {
        $('body').on('click', '#send_fast_order_more', function () {
            var $form = $(this).parents('form');
            var send = true;
            var form = document.forms.order__fastmore_form;
            var formData = new FormData(form);

            $form.find('input.error').removeClass('error');
            $form.find('textarea.error').removeClass('error');
            if(!$form.find("textarea[name='TXT']").val()){
                $form.find("textarea[name='TXT']").addClass('error');
                send = false;
            }
            else{
                BX.showWait();
            }

            if(send){
                $.ajax({
                    type:"POST",
                    url: "/local/include/ajax/setFastOrderMore.php",
                    data: formData,
                    dataType: "html",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        BX.closeWait();
                        BX.onCustomEvent('OnBasketChange');
                        $.magnificPopup.open({
                            items: {
                                src: response,
                                type: 'inline'
                            }
                        });
                        /*
                        if(response.success){
                            //cont.services.BasketEnvironment.clickBuyPopupBasket(0,1);
                            //$form.find(".fast-order__error_block").html('');
                            $form.find(".fast-order__answer_block").html('Все товары успешно добавлены в корзину!');
                        }else{
                            //cont.services.BasketEnvironment.clickBuyPopupBasket(0,1);
                            $form.find(".fast-order__answer_block").html('');
                            var text = 'Не обработанные артикулы: ';
                            jQuery.each(response, function() {
                                text = text  +  this.article + '  ' ;
                                //console.log(this.article);
                            });
                            $form.find(".fast-order__answer_block").html(text);
                        }*/
                    }
                });
            }
            return false;
        });
    },

    clickByArticleInit() {
        $('body').on('click', '#send_fast_order_some', function () {
            let $form = $(this).parents('form');
            let form = document.forms.order__fastsome_form;
            let send = false;
            let formData = new FormData(form);
            let $input = $form.find(".b-modal-f__input");

            $form.find('.b-modal-f__item.error').removeClass('error');
            
            $input.each(function () {
                let $this = $(this);
                
                if($this.val() !== '')
                    send = true;
                else
                    $this.parent().addClass('error');
            });
            
            if(send) {
                BX.showWait();
    
                $.ajax({
                    type:"POST",
                    url: "/local/include/ajax/setFastOrderSome.php",
                    data: formData,
                    dataType: "html",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        /*window['Analytics'].openFastOrderModal();*/
                        BX.closeWait();
                        BX.onCustomEvent('OnBasketChange');
                        $.magnificPopup.open({
                            items: {
                                src: response,
                                type: 'inline'
                            }
                        });
                    }
                });
            }
            
            return false;
        });
    }
};

module.exports = FastOrder;