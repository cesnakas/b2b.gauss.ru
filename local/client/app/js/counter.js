import SavedTemplate from './savedTemplate';

let counter = {
    run() {
        let elements = document.querySelectorAll('[data-input-count]');
        for (let i = 0; i < elements.length; i++) {
            new Counter(elements[i]);
        }

    }
};

function Counter(element) {
    const currency = 'RUB';
    let buttons = element.querySelectorAll('[data-input-count-btn]');
    let input = element.querySelector('[data-input-count-input]');

    if (input.value ==1)
    {
        addHover(input)
    }
    let elemId = input.dataset.itemid;
    let buttonAddQuantity = document.querySelector(`#button-quantity-${elemId}`);

    setEvents();

    function setEvents() {
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].onclick = function () {
                changeInput(buttons[i]);
            };
        }

        if (buttonAddQuantity) {
            buttonAddQuantity.addEventListener('click', () => addQuantity(buttonAddQuantity, input, elemId));
        }
        input.addEventListener('change', () => validateInput(input, buttonAddQuantity));
        input.addEventListener('focus', () => focusHundler(input));
    }
    
    function addQuantity(button, input, id) {
        let quantityGoods = button.querySelector('span [data-max-quantity]');
        removeHover(input)
        input.value = +input.value + +quantityGoods.textContent;
        quantityGoods.textContent = 0;
        document.querySelector(`#basket-item-upakovka-wrap-${id}`).style.display = 'none';
    }

    function changeInput(button) {
        removeHover(input)
        let buttonType = button.getAttribute('data-input-count-btn');
        let itemId = $(input).attr('data-itemId');
        let currentValue = parseInt(input.value);
        let quantityGoods = document.querySelector(`#basket-item-upakovka-cnt-${itemId}`);
        if(quantityGoods !== null) {
            let maxQuantityGoods = quantityGoods.dataset.maxQuantity;
        }
        if (buttonType === 'minus' && currentValue > 1) {
            input.value = --currentValue;

            if (input.value==1)
            {
                addHover(input)
            }

            if(quantityGoods !== null) {
                let maxQuantityGoods = quantityGoods.dataset.maxQuantity;
                if (quantityGoods.textContent == +maxQuantityGoods - 1) {
                    quantityGoods.textContent = 0;
                } else {
                    ++quantityGoods.textContent;
                }
            }
        }
        else if (buttonType === 'plus') {
            input.value = ++currentValue;
            if(quantityGoods !== null) {
                let maxQuantityGoods = quantityGoods.dataset.maxQuantity;
                if (quantityGoods.textContent == 0) {
                    quantityGoods.textContent = maxQuantityGoods - 1;
                } else {
                    --quantityGoods.textContent;
                }
            }
        }

        if(quantityGoods !== null) {
            if (quantityGoods.textContent == 0) {
                document.querySelector(`#basket-item-upakovka-wrap-${itemId}`).style.display = 'none';
            } else {
                document.querySelector(`#basket-item-upakovka-wrap-${itemId}`).style.display = 'flex';
            }
        }

        $(input).change();

        input.setAttribute('data-value', input.value);

        recalc(itemId);
        
    }

    function focusHundler(input) {
        var oldValueCountInput = input.value;
        input.value = '';

        input.onblur = function() {
            if (input.value === '') {
                input.value = oldValueCountInput;
            }
        }
    }

    function validateInput(input, button) {
        addHover(input)
        let itemId = $(input).attr('data-itemId');
        let inputValue = Number(input.value);
        if (isNaN(inputValue) || inputValue == 1) {
            addHover(input)
        }
        else
        {
            removeHover(input)
        }
        if (isNaN(inputValue) || inputValue < 1) {
            input.value = 1;
            element.classList.add('error');
            setTimeout(() => {
                element.classList.remove('error');
            },500)
        }

        if(button !== null){
            let quantityGoods = button.querySelector('span [data-max-quantity]');
            let maxQuantity = quantityGoods.dataset.maxQuantity;
            let remainder = +input.value % +maxQuantity;
            quantityGoods.textContent = +maxQuantity - remainder;
            if (quantityGoods.textContent == maxQuantity) {
                quantityGoods.textContent = 0;
            }
            if (quantityGoods.textContent == 0) {
                document.querySelector(`#basket-item-upakovka-wrap-${itemId}`).style.display = 'none';
            } else {
                document.querySelector(`#basket-item-upakovka-wrap-${itemId}`).style.display = 'flex';
            }
        }

        recalc(itemId);
    }

    function recalc(itemId) {
        let sumInput = document.querySelector('[data-price-sum][data-itemid="'+itemId+'"]');

        if (sumInput) {

            let cartContainer = sumInput.closest('[data-cart-container]');

            let sumPrice = sumInput.getAttribute('data-price-sum');

            if (sumPrice) {

                let productSumValue = sumPrice * input.value;

                sumInput.innerHTML = BX.Currency.currencyFormat(productSumValue, currency, true);
                sumInput.setAttribute('data-product-total-sum', productSumValue);

                if (null !== cartContainer) {
                    recalcSavedCart(cartContainer);
                }
            }
        }
    }

    function recalcSavedCart(cartContainer) {

        let totalSumCartBlock = cartContainer.querySelector('[data-total-sum]');

        const totalSumCartValue = [...cartContainer.querySelectorAll('[data-product-total-sum]')].reduce((acc, element) => {
            return acc + parseFloat(element.getAttribute('data-product-total-sum'));
        }, 0).toFixed(2);

        totalSumCartBlock.innerHTML = BX.Currency.currencyFormat(totalSumCartValue, currency, true);

        let savedCartTemplateId = cartContainer.getAttribute('data-template-id');

        let products = [...cartContainer.querySelectorAll('[data-input-count-input]')].map((element) => {

            let product = {};

            product.PRODUCT_ID = element.getAttribute('data-itemid');
            product.QUANTITY = element.getAttribute('data-value');

            return product;

        });

        if ([] !== products) {
            if (window.delayCartContainer) {
                clearTimeout(window.delayCartContainer);
            }
            window.delayCartContainer = setTimeout(() => {SavedTemplate.update(savedCartTemplateId, products)}, 1000);
        }

    }

    function addHover(input){
        var Id = input.getAttribute("data-itemId");
        $('[data-elemId="'+ Id + '"][data-input-count-btn="minus"]').addClass('no-hover');;
    }
    function removeHover(input){
        var Id = input.getAttribute("data-itemId");
        $('[data-elemId="'+ Id + '"][data-input-count-btn="minus"]').removeClass('no-hover');;
    }


    $('.b-count__input').keydown(function (event){
        if (!(!event.shiftKey //Disallow: any Shift+digit combination
            && !(event.keyCode < 48 || event.keyCode > 57) //Disallow: everything but digits
            || !(event.keyCode < 96 || event.keyCode > 105) //Allow: numeric pad digits
            || event.keyCode == 46 // Allow: delete
            || event.keyCode == 8  // Allow: backspace
            || event.keyCode == 9  // Allow: tab
            || event.keyCode == 27 // Allow: escape
            || event.keyCode == 13 // Allow: enter
            || (event.keyCode == 65 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+A
            || (event.keyCode == 67 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+C
            //Uncommenting the next line allows Ctrl+V usage, but requires additional code from you to disallow pasting non-numeric symbols
            //|| (event.keyCode == 86 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+Vpasting
            || (event.keyCode >= 35 && event.keyCode <= 39) // Allow: Home, End
        )) {
            event.preventDefault();
        }
    });

}

module.exports = counter;