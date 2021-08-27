'use strict';

let SavedTemplate = {
    update: function (id, products) {

        var data = {
            'isAjaxAction': 'Y',
            'action': 'updateCartTemplate',
            'savedCartId': id,
            'products': products
        };

        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: data,
            timeout: 3000
        });

    },
};

module.exports = SavedTemplate;