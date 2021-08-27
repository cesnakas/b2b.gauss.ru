document.addEventListener('App.Ready', function (e) {

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

        $.ajax({
            type: "GET",
            url: window.location.href,
            data: data,
            success: function(data) {
                $btn.removeClass('active');

                var regItems = /<!-- items -->([\s\S]*.*)<!-- \/items -->/;
                var items = data.match(regItems);
                var regBtn = /<!-- btn pagination -->([\s\S]*.*)<!-- \/btn pagination -->/;
                var btn = data.match(regBtn);
                (typeof(btn) != "undefined" && btn != null) ? $("#btn_"+bx_ajax_id).replaceWith(btn[1]) : $("#btn_"+bx_ajax_id).remove();
                (typeof(items) != "undefined" && items !== null) ? $(block_id).append(items[1], Ac.lazy.ajaxLoad()) : null;
            }
        });
    });
});