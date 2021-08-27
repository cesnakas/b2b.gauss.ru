$(document).on('click', '[data-cart-delete]', deleteCart);
$(document).on('click', '[data-cart-add]', addCart);
$(document).on('click', '[data-cart-restore]', restoreCart);
$(document).on('click', '[data-cart-change]', changeCart);

function deleteCart() {
    let savedCartId = $(this).attr('data-savedcart-id');

    var data = {
        'isAjaxAction': 'Y',
        'action': 'deleteCart',
        'savedCartId': savedCartId
    };

    BX.showWait();

    $.ajax({
        type: 'POST',
        url: window.location.href,
        data: data,
        success: function (data) {
            BX.closeWait();
            window.location.reload();
        },
    });
}

function addCart() {
    let savedCartId = $(this).attr('data-savedcart-id');

    var data = {
        'isAjaxAction': 'Y',
        'action': 'addCart',
        'savedCartId': savedCartId
    };

    BX.showWait();

    $.ajax({
        type: 'POST',
        url: window.location.href,
        data: data,
        success: (data) => {
            BX.closeWait();
            Am.modals.showDialog('/local/include/modals/lk_saved_carts_message.php');
            $(this).removeAttr('data-cart-add');
            $(this).attr("href", "javascript:Am.modals.showDialog('/local/include/modals/lk_saved_carts_form.php?cart-id=" + savedCartId + "');");
        },
    });
}

function changeCart() {
    let savedCartId = $(this).attr('data-savedcart-id');

    var data = {
        'isAjaxAction': 'Y',
        'action': 'changeCart',
        'savedCartId': savedCartId
    };

    BX.showWait();

    $.ajax({
        type: 'POST',
        url: window.location.href,
        data: data,
        success: (data) => {
            BX.closeWait();
            Am.modals.showDialog('/local/include/modals/lk_saved_carts_message.php');
        },
    });
}

function restoreCart() {
    let savedCartId = $(this).attr('data-savedcart-id');

    var data = {
        'isAjaxAction': 'Y',
        'action': 'restoreCart',
        'savedCartId': savedCartId
    };

    BX.showWait();

    $.ajax({
        type: 'POST',
        url: window.location.href,
        data: data,
        success: function (data) {
            BX.closeWait();
            window.location.href = window.location.protocol + "//" + window.location.host + "/order/";
        },
    });
}

