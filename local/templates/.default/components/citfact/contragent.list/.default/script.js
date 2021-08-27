function setContragent(element) {
    var contragentXmlId = $(element).val();
    var userId = $(element).attr('data-user-id');

    var data = {
        'isAjaxAction': 'Y',
        'action': 'setContragent',
        'contragentXmlId': contragentXmlId,
        'userId': userId
    }
    $.ajax({
        type: "POST",
        url: window.location.href,
        data: data,
        success: function (data) {
            let isCartPage = '/cart/' === window.location.pathname;
            let currUrl = window.location.origin + window.location.pathname;

            if (isCartPage === true) {
                window.location.href = currUrl + '?action=setContragent';
            } else {
                location.reload();
            }
        }
    });
}
