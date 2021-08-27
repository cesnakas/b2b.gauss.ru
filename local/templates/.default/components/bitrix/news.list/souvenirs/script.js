document.addEventListener('App.Ready', function (e) {
    $(document).on('click', '[data-show-more]', function(){
        var $btn = $(this);
        var page = $btn.attr('data-next-page');
        var id = $btn.attr('data-show-more');
        var bx_ajax_id = $btn.attr('data-ajax-id');
        var block_id = "#block_"+bx_ajax_id;

        var data = {
            bxajaxid: bx_ajax_id
        };
        data['PAGEN_'+id] = page;

        $btn.addClass('active');

        $.ajax({
            type: "GET",
            url: window.location.href,
            data: data,
            success: function(data) {
                $btn.removeClass('active');
                var items = $(data).find('.blocks__inner').html();
                var btn = $(data).find('.blocks__bottom').get(0);
                (typeof(btn) != "undefined" && btn != null) ? $("#btn_"+bx_ajax_id).replaceWith(btn) : $("#btn_"+bx_ajax_id).remove();
                (typeof(items) != "undefined" && items !== null) ? $(block_id).append(items, Ac.lazy.ajaxLoad()) : null;
            }
        });
    });
});
