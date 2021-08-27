document.addEventListener('App.Ready', function (e) {
    $( document ).on( 'click', '[data-show-more]', loadMoreItems );

    var isLoading = false;

    /*$( window ).scroll( function () {
        if ( !isLoading && $( window ).scrollTop() >= ( $( document ).height() - $( window ).height() ) * 0.7 ) { //когда прокрутка достигает 70% высоты окна
            loadMoreItems();
        }
    } );*/

    function loadMoreItems() {
        isLoading = true;
        var btn = $( 'a[data-show-more]' )
        var page = btn.attr( 'data-next-page' );
        var id = btn.attr( 'data-show-more' );
        var bx_ajax_id = btn.attr( 'data-ajax-id' );
        var block_id = "#comp_" + bx_ajax_id;

        if ( !bx_ajax_id ) {
            return;
        }

        var data = {
            bxajaxid: bx_ajax_id
        };
        data[ 'PAGEN_' + id ] = page;

        // BX.showWait();

        $.ajax( {
            type: "GET",
            url: window.location.href,
            data: data,
            success: function ( data ) {
                // BX.closeWait();
                $( "#btn_" + bx_ajax_id ).remove();
                $( block_id + " .c-g" ).append( data );
                setItemsCount();
                isLoading = false;
                Am.tooltip.run();
                Am.modals.run();
                /*Am.CatalogActions.run();*/
                BX.onCustomEvent( 'OnBasketChange' );
            }
        } );
    }

//устанавливает количество товаров на странице
    function setItemsCount() {
        var itemsCount = $( '.p' ).length;
        $( '#items-count' ).html( itemsCount );
    }

});