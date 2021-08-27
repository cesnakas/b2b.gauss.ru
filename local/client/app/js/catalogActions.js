'use strict';

let CatalogActions = {
    run: function (container) {
        if (!container) {
            container = $(document);
        }

        this.initCatalogActions(container);
        this.initCounterActions(container);
    },
    initCatalogActions: function(container) {
        let self = this;
        container.on('click', '[data-add2basket]', (e) => {
            e.preventDefault();

            let target = $(e.currentTarget);
            let isReload = !!(target.data('add2basket')==='reload');

            let itemId = target.data('itemid');

            let quantity = $('[data-input-count-input][data-itemid="'+itemId+'"]').val();

            self.add2basket(target, quantity, self, isReload);
        });


        // Добавление в избранное
        container.on('click', '[data-add2favorites]', function (e) {
            e.preventDefault();

            let target = $(e.currentTarget);
            let item = $(this).attr('data-itemId');

            let input = $('[data-input-count-input][data-itemId="' + item + '"]');
            let val = input.val();

            // Если у инпута есть заполненный дата-атрибут data-base-quantity, то в качестве значения берем его
            if (typeof input.attr('data-base-quantity') !== 'undefined' && input.attr('data-base-quantity').length > 0){
                val = input.attr('data-base-quantity');
            }

            if (!val) {
                val = 1;
            }

            BX.showWait();

            let fill_prop = {};
            fill_prop.quantity = val;
            fill_prop.item = item;
            fill_prop.isAjaxAction = 'Y';
            fill_prop.action = 'add2favorites';

            $.ajax({
                type: "POST",
                url: "/",
                data: fill_prop,
                dataType: "json",
                success: function (data) {
                    if (data !== null) {
                        if (data.MESSAGE_EXT === null)
                            data.MESSAGE_EXT = '';
                        if ("STATUS" in data) {
                            if (data.STATUS === 'OK') {
                                $('#counter-favorites').html(data.COUNT_STRING);
                                target.toggleClass('active');
                                $('[data-add2basket][data-itemId="' + target.attr('data-itemId') + '"]').removeClass('active');

                                /**
                                 * для страницы избранного
                                 */
                                if (target.data('remove-item')) {
                                    target.parents('[data-item-container="'+target.attr('data-itemId')+'"]').remove();

                                    if ($('[data-favourites-container]').find('[data-item-container]').length === 0) {
                                        $('[data-favourites-empty]').show();
                                    }
                                }
                            } else {
                                //showBasketError(BX.message(data.MESSAGE) + ' <br/>' + data.MESSAGE_EXT, BX.message("ERROR_ADD_DELAY_ITEM"));
                            }
                        } else {
                            //showBasketError(BX.message(data.MESSAGE) + ' <br/>' + data.MESSAGE_EXT, BX.message("ERROR_ADD_DELAY_ITEM"));
                        }
                    } else {
                    }

                    BX.closeWait();
                    BX.onCustomEvent('OnBasketChange');
                }
            });
        });

        // Множественное выделение товаров дял добавления в корзину (каталог таблицей)
        $('[data-catalog-select-all]').on('click', function () {
            const state = $(this).prop('checked');
            $('[data-catalog-select-item]').prop('checked', state);
        });

        // Множественное добавление в корзину (каталог таблицей)
        container.on('click', '[data-add2basket-multiple]', (e) => {
            e.preventDefault();
            let button = $(e.currentTarget);
            let handler = button.data('add2basket-multiple');
            this.addToCartMultiple(handler, button);
        });

    },

    addToCartMultiple: function(handler, button) {
        let $items = $('[data-catalog-select-item]');
        let obItems = {};
        $items.each(function () {
            let item = $(this);
            if (item.prop('checked') === true || item.attr('checked') === 'checked') {

                let val = parseInt($('[data-input-count-input][data-itemId="' + item.data('item-id') + '"]').val());
                let newItem = {
                    id: item.data('item-id'),
                    price: item.data('item-price'),
                    quantity: val,
                };
                obItems[newItem.id] = newItem;
            }
        });

        let send_data = {};
        send_data.add_item_multiple = 'Y';
        send_data.items = obItems;
        send_data.isAjaxAction = 'Y';
        send_data.action = 'add2cartMultiple';

        if (Object.keys(send_data.items).length > 0) {
            $.ajax({
                type: "POST",
                url: "/",
                data: send_data,
                dataType: "json",
                beforeSend: function () {
                    BX.showWait();
                },
                complete: function () {
                    BX.closeWait();
                },
                success: (data) => {
                    this.buyMultipleProductTable(data, $items, button, obItems);
                    this.showModalMultiplySuccess(send_data);

                    BX.onCustomEvent('OnBasketChange');
                }
            });
        }
    },

    showModalMultiplySuccess: function (send_data) {
        let itemIds = Object.keys(send_data.items);

        Am.modals.showDialog('/local/include/modals/basket_modal.php?IDS=' + itemIds.join(','));
    },

    buyMultipleProductTable: function (data, $items, button, obItems) {
        if (data !== null) {
            if ("STATUS" in data) {
                if (data.MESSAGE_EXT === null)
                    data.MESSAGE_EXT = '';
                if (data.STATUS === 'OK') {
                    $items.prop('checked', false).change();
                    $('[data-catalog-select-all]').prop('checked', false);
                    for (let key in obItems) {
                        $('[data-add2basket][data-itemid="' + key + '"]').html('В корзине').addClass('active');
                    }
                } else {
                    //this.showBasketError(BX.message(data.MESSAGE) + ' <br/>' + data.MESSAGE_EXT);
                }
            } else {
                //this.showBasketError(BX.message("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR"));
            }
        }
    },

    initCounterActions: function(container) {
        let self = this;
        let elements = container[0].querySelectorAll('[data-input-count-add2basket]');
        let buttonsQuantity = container[0].querySelectorAll('[data-max-quantity]');
        for (let i = 0; i < elements.length; i++) {
            new this.setCounter(elements[i], self, buttonsQuantity[i]);
        }
    },

    setCounter: function(element, self, buttonQuantity) {
        let buttons = element.querySelectorAll('[data-input-count-btn-add2basket]');
        let input = element.querySelector('[data-input-count-input]');
        let maxQuantity = buttonQuantity.dataset.maxQuantity;
        let idElem = buttonQuantity.parentNode.dataset.id;
        let isActive = document.querySelector(`#btn--transparent-${idElem}`)
        isActive = isActive.classList.contains('active');
        buttonQuantity.textContent = +maxQuantity - (+input.value % +maxQuantity);

        if (buttonQuantity.textContent == maxQuantity) {
            buttonQuantity.textContent = 0;
            document.querySelector(`#basket-item-upakovka-wrap-${idElem}`).style.display = 'none';
        }

        if (!isActive) {
            document.querySelector(`#basket-item-upakovka-wrap-${idElem}`).style.display = 'none';
        }

        setEvents();

        function setEvents() {
            for (let i = 0; i < buttons.length; i++) {
                if ($(buttons[i]).data('action-active') !== true) {
                    $(buttons[i]).data('action-active', true);
                    buttons[i].addEventListener('click', () => {
                        changeInput(buttons[i], self);
                    });
                }
            }

            input.addEventListener('change', () => {
                validateInput(input);
            });

            buttonQuantity.parentElement.addEventListener('click', () => {
                input.value = +input.value + +buttonQuantity.textContent;
                buttonQuantity.textContent = 0;
                changeInput(buttonQuantity.parentElement, self);
            });
        }

        function changeInput(button, self) {
            if (self.flagCounter) {
                return;
            }

            let itemId = $(input).attr('data-itemId');
            let buttonType = button.getAttribute('data-input-count-btn-add2basket');
            let currentValue = parseInt(input.value);

            if (buttonType === 'minus' && currentValue > 0) {
                input.value = --currentValue;
            }
            else if (buttonType === 'plus') {
                input.value = ++currentValue;
            }
            $(input).change();


            let sumInput = document.querySelector('[data-price-sum][data-itemid="'+itemId+'"]');
            if (sumInput) {
                let sumPrice = sumInput.getAttribute('data-price-sum');
                if (sumPrice) {
                    sumInput.val(sumPrice * input.value);
                }
            }

            if (!!self.timeout)
                clearTimeout(self.timeout);

            self.timeout = setTimeout(function () {
                self.flagCounter = true;
                if (itemId) {
                    let item = $('[data-add2basket][data-itemId="' + itemId + '"]');
                    let input = $('[data-input-count-input][data-itemId="' + itemId + '"]');
                    let quantity;
                    if (input) {
                        quantity = input.val();
                    } else {
                        quantity = 1;
                    }
                    self.add2basket(item, quantity, self);
                }
            }, 500);
        }

        function validateInput(input) {
            let inputValue = Number(input.value);

            if (isNaN(inputValue) || inputValue < 1) {
                input.value = 1;
                element.classList.add('error');
                setTimeout(() => {
                    element.classList.remove('error');
                }, 500)
            }
        }
    },

    /**
     * @param target
     * @param quantity
     * @param self
     * @param isReload
     */
    add2basket: function(target, quantity, self, isReload=false) {
        BX.showWait(target.closest('[data-catalog-item]'));

        let fill_prop = {};
        let item = target.attr('data-itemId');

        if (quantity) {
            fill_prop.quantity = quantity;
        }

        if (target.attr('data-add_item')) {
            fill_prop.add_item = 'Y';
        }

        fill_prop.item = item;
        fill_prop.isAjaxAction = 'Y';
        fill_prop.action = 'add2basket';

        if (target.data("empty_props") == "N") {
            if (self && typeof self.flagCounter !== 'undefined') {
                self.flagCounter = false;
            }
            BX.closeWait();

        } else {
            $.ajax({
                type: "POST",
                url: "/",
                data: fill_prop,
                dataType: "json",
                success: (data) => {
                    if (data !== null) {
                        if ("STATUS" in data) {
                            if (data.MESSAGE_EXT === null)
                                data.MESSAGE_EXT = '';
                            if (data.STATUS === 'OK') {
                                if (isReload) {
                                    window.location.reload();
                                }

                                let resQNT = parseInt(data.QUANTITY);
                                if (isNaN(resQNT)) {
                                    resQNT = 1;
                                }

                                if (resQNT === 0) {
                                    self.setDontActiveItem(item, resQNT);
                                } else {
                                    self.setActiveItem(item, resQNT);
                                }

                                /**
                                 * удаление на странице избранного
                                 */
                                if (target.data('remove-item')) {
                                    target.parents('[data-item-container="'+target.attr('data-itemId')+'"]').remove();

                                    if ($('[data-favourites-container]').find('[data-item-container]').length === 0) {
                                        $('[data-favourites-empty]').show();
                                    }
                                }


                                /**
                                 * выводим надпись "данное количество в корзине товара не кратно упаковке"
                                 */
                                target.parents('[data-item-container]').find('[data-kolvo-vupakovke][data-itemId="'+target.attr('data-itemId')+'"]').each(function () {
                                    let oTextVUpakovke = $(this);
                                    let cntUpakovka = $(this).data('kolvo-vupakovke');
                                    if (parseInt(cntUpakovka) && parseInt(resQNT)) {
                                        let ostatok = parseInt(resQNT) % parseInt(cntUpakovka);
                                        if (ostatok !== 0) {
                                            if (typeof window.KolvoVUpakovkeCounterTimer !== 'object') {
                                                window.KolvoVUpakovkeCounterTimer = [];
                                            }

                                            if (!!window.KolvoVUpakovkeCounterTimer[target.attr('data-itemId')]) {
                                                clearTimeout(window.KolvoVUpakovkeCounterTimer[target.attr('data-itemId')]);
                                            }

                                            oTextVUpakovke.fadeIn(200);
                                            window.KolvoVUpakovkeCounterTimer[target.attr('data-itemId')] = setTimeout(function (  ) {
                                                oTextVUpakovke.fadeOut(200);
                                            }, 3000);

                                        } else {
                                            oTextVUpakovke.fadeOut(200);
                                        }
                                    }
                                });

                                let quantityGoods = document.querySelector(`#basket-item-upakovka-cnt-${target.attr('data-itemId')}`);
                                let maxQuantityGoods = quantityGoods.dataset.maxQuantity;
                                let input = document.querySelector('#b-count-' + target.attr('data-itemId') + ' input');

                                quantityGoods.textContent = +maxQuantityGoods - (+input.value % +maxQuantityGoods);
                                if (quantityGoods.textContent == maxQuantityGoods) {
                                    quantityGoods.textContent = 0;
                                }
                                if (quantityGoods.textContent == 0) {
                                    document.querySelector(`#basket-item-upakovka-wrap-${target.attr('data-itemId')}`).style.display = 'none';
                                } else {
                                    document.querySelector(`#basket-item-upakovka-wrap-${target.attr('data-itemId')}`).style.display = 'flex';
                                }

                            } else {
                                //this.showBasketError(BX.message(data.MESSAGE) + ' <br/>' + data.MESSAGE_EXT);
                            }
                        } else {
                            //this.showBasketError(BX.message("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR"));
                        }
                    } else {
                        // Если data === null
                    }

                    BX.onCustomEvent('OnBasketChange');
                },
                complete: function () {
                    BX.closeWait();
                    if (self && typeof self.flagCounter !== 'undefined') {
                        self.flagCounter = false;
                    }
                }
            });
        }
    },


    /**
     * double for \Citfact\Sitecore\Order\Basket::setBasketItemsOnLoad
     *
     * @param $id
     * @param $qnt
     */
    setActiveItem: function($id, $qnt) {
        $('[data-input-count-input][data-itemId="' + $id + '"]').not('[data-input-count-not-val]').val($qnt);

        let catalogItemWrap = $('[data-add2basket][data-itemId="'+$id+'"]');

        if (catalogItemWrap.data('detail') !== 'undefined') {
            catalogItemWrap.addClass('active').html('<span>В корзине</span>');
        } else {
            catalogItemWrap.addClass('active').html('В корзине');
        }

    },

    setDontActiveItem: function($id, $qnt) {
        $('[data-input-count-input][data-itemId="' + $id + '"]').not('[data-input-count-not-val]').val($qnt);

        let catalogItemWrap = $('[data-add2basket][data-itemId="'+$id+'"]');

        if (catalogItemWrap.data('detail') !== 'undefined') {
            catalogItemWrap.removeClass('active').html('<span>Купить</span>');
        } else {
            catalogItemWrap.removeClass('active').html('Купить');
        }
    },
};

module.exports = CatalogActions;