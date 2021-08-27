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
            window.location.href = window.location.href;
        }
    });
}
