document.addEventListener('App.Ready', function (e) {

    $( document ).on( 'click', '[data-show-more]', loadMoreItems );

    function loadMoreItems() {
        var btn = $( 'a[data-show-more]' )
        var page = btn.attr( 'data-next-page' );
        var id = btn.attr( 'data-show-more' );
        var bx_ajax_id = btn.attr( 'data-ajax-id' );
        var block_id = "#comp_" + bx_ajax_id;

        var data = {
            bxajaxid: bx_ajax_id
        };
        data[ 'PAGEN_' + id ] = page;

        $.ajax( {
            type: "GET",
            url: window.location.href,
            data: data,
            success: function ( data ) {
                let newElements = $( data ).find( '.blocks__card' );
                let newButtonShowMore = $( data ).find( '#btn_' + bx_ajax_id );

                $( block_id + " .blocks__inner" ).append( newElements );
                $( "#btn_" + bx_ajax_id ).replaceWith( newButtonShowMore );
                Am.modals.run();
            }
        } );
    }

});