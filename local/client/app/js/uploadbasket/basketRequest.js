import 'jquery';

export  class BasketRequest {
    send(method, data) {
        return $.when($.ajax({
            'url': '/personal/load_order/',
            'method': 'post',
            'data': {'method': method, 'data': data},
            'dataType': 'json'
        }));
    }

    sendFile(data) {
        return $.when($.ajax({
            'url': '/personal/load_order/',
            'method': 'POST',
            'data': data,
            'dataType' : 'json',
            'processData': false,
            'contentType': false
        }));
    }
}