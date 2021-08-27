document.addEventListener('App.Ready', function (e) {

    $( document ).on( 'click', '[data-show-more]', loadMoreItems );

    function loadMoreItems() {
        var btn = $( 'a[data-show-more]' )
        var page = btn.attr( 'data-next-page' );
        var id = btn.attr( 'data-show-more' );
        var bx_ajax_id = btn.attr( 'data-ajax-id' );

        var data = {
            bxajaxid: bx_ajax_id
        };
        data[ 'PAGEN_' + id ] = page;

        $.ajax( {
            type: "GET",
            url: window.location.href,
            data: data,
            success: function ( data ) {
                let newElements = $( data ).find( '.ed-media__card' );
                let newButtonShowMore = $( data ).find( '#btn_' + bx_ajax_id );

                $( ".ed-media" ).append( newElements );
                $( "#btn_" + bx_ajax_id ).replaceWith( newButtonShowMore );
                Am.modals.run();
            }
        } );
    }

});